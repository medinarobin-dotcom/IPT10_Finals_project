<?php

/**
 * CurrencyService - external API integration.
 *
 * Uses the free Frankfurter API (no API key needed) to convert peso prices:
 *   GET https://api.frankfurter.dev/v2/rates?base=PHP&quotes=USD,EUR,JPY,KRW,SGD
 *   -> [{"date":"2026-11-08","base":"PHP","quote":"USD","rate":0.015868}, ...]
 *
 * Rates are saved in the currency_rates table and refreshed every 6 hours.
 * If the API cannot be reached, the saved rates are used instead.
 */
class CurrencyService
{
    const API_URL = 'https://api.frankfurter.dev/v2/rates?base=PHP&quotes=USD,EUR,JPY,KRW,SGD';
    const CURRENCIES = ['PHP' => '₱', 'USD' => '$', 'EUR' => '€', 'JPY' => '¥', 'KRW' => '₩', 'SGD' => 'S$'];
    const CACHE_HOURS = 6;

    private static $rates = null;

    public static function format($pesos, $currency)
    {
        $rate = $currency === 'PHP' ? 1 : (self::rates()[$currency] ?? null);

        if (!$rate) {
            return peso($pesos);
        }

        return self::CURRENCIES[$currency] . number_format($pesos * $rate, 2);
    }

    // Rates from the database, refreshed from the API when older than 6 hours.
    public static function rates($pdo = null, $fetch = null)
    {
        if (self::$rates !== null) {
            return self::$rates;
        }

        $pdo = $pdo ?? db();
        $rows = $pdo->query('SELECT currency, rate, updated_at FROM currency_rates')->fetchAll(PDO::FETCH_ASSOC);
        $oldest = $rows ? min(array_column($rows, 'updated_at')) : null;

        $isOld = !$oldest || strtotime($oldest) < time() - self::CACHE_HOURS * 3600;
        $canRetry = time() >= ($_SESSION['rates_retry_at'] ?? 0);

        if ($isOld && $canRetry) {
            try {
                $fresh = self::fetchFromApi($fetch);
                $save = $pdo->prepare('UPDATE currency_rates SET rate = ?, updated_at = ? WHERE currency = ?');
                foreach ($fresh as $currency => $rate) {
                    $save->execute([$rate, date('Y-m-d H:i:s'), $currency]);
                }
                $rows = $pdo->query('SELECT currency, rate, updated_at FROM currency_rates')->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                // API down or offline: keep using the saved rates,
                // and wait 10 minutes before trying again.
                $_SESSION['rates_retry_at'] = time() + 600;
                error_log('Currency API error: ' . $e->getMessage());
            }
        }

        self::$rates = array_column($rows, 'rate', 'currency');

        return self::$rates;
    }

    // Calls the API and returns ['USD' => 0.0158, ...]. Throws on any problem.
    public static function fetchFromApi($fetch = null)
    {
        $fetch = $fetch ?? [self::class, 'httpGet'];
        [$status, $body] = call_user_func($fetch, self::API_URL);

        if ($status === 429) {
            throw new Exception('Too many requests (rate limited)');
        }
        if ($status !== 200) {
            throw new Exception("API returned HTTP $status");
        }

        $data = json_decode($body, true);
        if (!is_array($data)) {
            throw new Exception('API returned invalid JSON');
        }

        $rates = [];
        foreach ($data as $row) {
            if (isset($row['quote'], $row['rate']) && isset(self::CURRENCIES[$row['quote']])) {
                $rates[$row['quote']] = (float) $row['rate'];
            }
        }

        if (!$rates) {
            throw new Exception('API returned no rates');
        }

        return $rates;
    }

    public static function httpGet($url)
    {
        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);

        // XAMPP ships a certificate bundle; use it so HTTPS works on Windows.
        $xamppCerts = 'C:/xampp/apache/bin/curl-ca-bundle.crt';
        if (is_file($xamppCerts)) {
            curl_setopt($curl, CURLOPT_CAINFO, $xamppCerts);
        }

        $body = curl_exec($curl);
        if ($body === false) {
            throw new Exception(curl_error($curl));
        }
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return [$status, $body];
    }

    public static function reset()
    {
        self::$rates = null;
    }
}
