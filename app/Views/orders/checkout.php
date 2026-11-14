<h1>Checkout</h1>

<div class="two">
    <form method="post" action="/checkout" class="box form">
        <?= csrf_field() ?>
        <label>Delivery address</label>
        <textarea name="address" rows="3" required></textarea>
        <label>Mobile number</label>
        <input type="text" name="phone" placeholder="09171234567" required>
        <p class="muted">Payment: Cash on delivery</p>
        <button class="btn">Place order</button>
    </form>

    <div class="box">
        <h2>Order summary</h2>
        <?php foreach ($lines as $line): ?>
            <p><?= e($line['name']) ?> (<?= e($line['size']) ?>) &times; <?= $line['qty'] ?> &mdash; <?= peso($line['price'] * $line['qty']) ?></p>
        <?php endforeach; ?>
        <p class="price big">Total: <?= peso($total) ?></p>
    </div>
</div>
