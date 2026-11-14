<?php

// The shopping cart is kept in the session: ["productId|size" => quantity]
class Cart
{
    public static function add($productId, $size, $qty)
    {
        $key = $productId . '|' . $size;
        $_SESSION['cart'][$key] = ($_SESSION['cart'][$key] ?? 0) + $qty;
    }

    public static function update($key, $qty)
    {
        if ($qty <= 0) {
            unset($_SESSION['cart'][$key]);
        } else {
            $_SESSION['cart'][$key] = $qty;
        }
    }

    public static function count()
    {
        return array_sum($_SESSION['cart'] ?? []);
    }

    // Cart lines with the CURRENT price from the database.
    public static function lines()
    {
        $lines = [];

        foreach ($_SESSION['cart'] ?? [] as $key => $qty) {
            [$id, $size] = explode('|', $key);
            $product = Product::find($id);

            if ($product) {
                $lines[] = [
                    'key' => $key, 'id' => $product['id'], 'name' => $product['name'],
                    'image' => $product['image'], 'size' => $size, 'qty' => $qty,
                    'price' => $product['price'], 'stock' => $product['stock'],
                ];
            }
        }

        return $lines;
    }

    public static function total($lines)
    {
        $total = 0;
        foreach ($lines as $line) {
            $total += $line['price'] * $line['qty'];
        }

        return $total;
    }

    public static function clear()
    {
        unset($_SESSION['cart']);
    }
}
