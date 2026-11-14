<h1>Your Cart</h1>

<?php if (!$lines): ?>
    <p class="box">Your cart is empty. <a href="/shop">Go shopping</a></p>
<?php else: ?>
    <table class="table">
        <tr><th>Item</th><th>Size</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr>
        <?php foreach ($lines as $line): ?>
            <tr>
                <td><img class="thumb" src="/images/<?= e($line['image']) ?>" alt=""> <?= e($line['name']) ?></td>
                <td><?= e($line['size']) ?></td>
                <td><?= price($line['price']) ?></td>
                <td>
                    <form method="post" action="/cart/update" class="inline">
                        <?= csrf_field() ?>
                        <input type="hidden" name="key" value="<?= e($line['key']) ?>">
                        <input type="number" name="qty" value="<?= $line['qty'] ?>" min="0" max="<?= $line['stock'] ?>">
                        <button class="btn small">Update</button>
                    </form>
                </td>
                <td><?= price($line['price'] * $line['qty']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <p class="muted">Set quantity to 0 to remove an item.</p>

    <div class="total">
        <p>Total: <strong><?= price($total) ?></strong></p>
        <?php if (current_user()): ?>
            <a class="btn" href="/checkout">Checkout</a>
        <?php else: ?>
            <a class="btn" href="/login">Login to checkout</a>
        <?php endif; ?>
    </div>
<?php endif; ?>
