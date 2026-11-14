<?php

class Product
{
    public static function categories()
    {
        return db()->query('SELECT * FROM categories ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
    }

    // Search the catalog. All user input goes through bound parameters.
    public static function search($categoryId = null, $keyword = '')
    {
        $sql = 'SELECT * FROM products WHERE 1=1';
        $params = [];

        if ($categoryId) {
            $sql .= ' AND category_id = ?';
            $params[] = $categoryId;
        }
        if ($keyword !== '') {
            $sql .= ' AND (name LIKE ? OR color LIKE ?)';
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }

        $stmt = db()->prepare($sql . ' ORDER BY id DESC');
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function featured()
    {
        return db()->query('SELECT * FROM products WHERE is_featured = 1 ORDER BY id DESC LIMIT 8')->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $stmt = db()->prepare('SELECT p.*, c.name AS category FROM products p JOIN categories c ON c.id = p.category_id WHERE p.id = ?');
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function save($data, $id = null)
    {
        $fields = [$data['category_id'], $data['name'], $data['color'], $data['description'],
                   $data['price'], $data['sizes'], $data['stock'], $data['image'], $data['is_featured']];

        if ($id) {
            $fields[] = $id;
            $sql = 'UPDATE products SET category_id=?, name=?, color=?, description=?, price=?, sizes=?, stock=?, image=?, is_featured=? WHERE id=?';
        } else {
            $sql = 'INSERT INTO products (category_id, name, color, description, price, sizes, stock, image, is_featured) VALUES (?,?,?,?,?,?,?,?,?)';
        }

        db()->prepare($sql)->execute($fields);
    }

    public static function delete($id)
    {
        db()->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);
    }
}
