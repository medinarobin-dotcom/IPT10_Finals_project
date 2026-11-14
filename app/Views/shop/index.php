<h1>Shop</h1>

<form method="get" action="/shop" class="filters">
    <input type="text" name="q" placeholder="Search name or color" value="<?= e($keyword) ?>">
    <select name="category">
        <option value="">All categories</option>
        <?php foreach ($categories as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $categoryId == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
        <?php endforeach; ?>
    </select>
    <button class="btn">Search</button>
</form>

<?php if (!$products): ?>
    <p class="box">No products found.</p>
<?php endif; ?>

<div class="grid">
    <?php foreach ($products as $p) require __DIR__ . '/product-card.php'; ?>
</div>
