<h1>Order #<?= $order['id'] ?></h1>
<p>Status: <span class="status <?= e($order['status']) ?>"><?= e($order['status']) ?></span> &middot; Placed <?= e($order['created_at']) ?></p>

<table class="table">
    <tr><th>Item</th><th>Size</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr>
    <?php foreach ($items as $item): ?>
        <tr>
            <td><?= e($item['name'] ?? 'Deleted product') ?></td>
            <td><?= e($item['size']) ?></td>
            <td><?= peso($item['price']) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td><?= peso($item['price'] * $item['quantity']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<div class="box">
    <p><strong>Total:</strong> <?= peso($order['total']) ?> (cash on delivery)</p>
    <p><strong>Deliver to:</strong> <?= e($order['customer']) ?>, <?= e($order['address']) ?>, <?= e($order['phone']) ?></p>
</div>
