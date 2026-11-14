<div class="product">
    <img src="/images/<?= e($product['image']) ?>" alt="<?= e($product['name']) ?>">
    <div>
        <p class="muted"><?= e($product['category']) ?></p>
        <h1><?= e($product['name']) ?></h1>
        <p class="muted"><?= e($product['color']) ?></p>
        <p class="price big"><?= price($product['price']) ?></p>
        <p><?= e($product['description']) ?></p>

        <?php if ($product['stock'] > 0): ?>
            <form method="post" action="/cart/add" class="box">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                <label>Size</label>
                <div class="sizes">
                    <?php foreach (explode(',', $product['sizes']) as $size): ?>
                        <label class="size"><input type="radio" name="size" value="<?= e($size) ?>" required> <span><?= e($size) ?></span></label>
                    <?php endforeach; ?>
                </div>
                <label>Quantity</label>
                <input type="number" name="qty" value="1" min="1" max="<?= $product['stock'] ?>">
                <p class="muted"><?= $product['stock'] ?> in stock</p>
                <button class="btn">Add to cart</button>
            </form>
        <?php else: ?>
            <p class="box">Sold out</p>
        <?php endif; ?>
    </div>
</div>
