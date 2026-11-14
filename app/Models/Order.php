<?php

class Order
{
    const STATUSES = ['pending', 'shipped', 'delivered', 'cancelled'];

    // Save the order and reduce stock in ONE transaction:
    // if any item is out of stock, nothing is saved.
    public static function place($userId, $lines, $address, $phone)
    {
        $pdo = db();
        $pdo->beginTransaction();

        try {
            $total = Cart::total($lines);

            $stmt = $pdo->prepare('INSERT INTO orders (user_id, total, address, phone, status) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$userId, $total, $address, $phone, 'pending']);
            $orderId = $pdo->lastInsertId();

            foreach ($lines as $line) {
                // "stock >= ?" makes sure we never sell more than we have
                $update = $pdo->prepare('UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?');
                $update->execute([$line['qty'], $line['id'], $line['qty']]);

                if ($update->rowCount() === 0) {
                    throw new Exception("Sorry, {$line['name']} does not have enough stock.");
                }

                $item = $pdo->prepare('INSERT INTO order_items (order_id, product_id, size, quantity, price) VALUES (?, ?, ?, ?, ?)');
                $item->execute([$orderId, $line['id'], $line['size'], $line['qty'], $line['price']]);
            }

            $pdo->commit();

            return $orderId;
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public static function forUser($userId)
    {
        $stmt = db()->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC');
        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $stmt = db()->prepare('SELECT o.*, u.name AS customer FROM orders o JOIN users u ON u.id = o.user_id WHERE o.id = ?');
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function items($orderId)
    {
        $stmt = db()->prepare('SELECT i.*, p.name, p.image FROM order_items i LEFT JOIN products p ON p.id = i.product_id WHERE i.order_id = ?');
        $stmt->execute([$orderId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function all()
    {
        return db()->query('SELECT o.*, u.name AS customer FROM orders o JOIN users u ON u.id = o.user_id ORDER BY o.id DESC')->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function setStatus($id, $status)
    {
        db()->prepare('UPDATE orders SET status = ? WHERE id = ?')->execute([$status, $id]);
    }
}
