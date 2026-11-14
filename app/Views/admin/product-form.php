<?php $p = $product ?? []; ?>
<form method="post" action="/admin/product-save" class="box form">
    <h1><?= $product ? 'Edit' : 'Add' ?> Product</h1>
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= e($p['id'] ?? '') ?>">

    <label>Name</label>
    <input type="text" name="name" value="<?= e($p['name'] ?? '') ?>" required>

    <label>Category</label>
    <select name="category_id">
        <?php foreach ($categories as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($p['category_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Color</label>
    <input type="text" name="color" value="<?= e($p['color'] ?? '') ?>">

    <label>Description</label>
    <textarea name="description" rows="3"><?= e($p['description'] ?? '') ?></textarea>

    <label>Price (PHP)</label>
    <input type="number" step="0.01" name="price" value="<?= e($p['price'] ?? '') ?>" required>

    <label>Sizes (comma separated)</label>
    <input type="text" name="sizes" value="<?= e($p['sizes'] ?? 'S,M,L,XL') ?>" required>

    <label>Stock</label>
    <input type="number" name="stock" value="<?= e($p['stock'] ?? 10) ?>" min="0">

    <label>Image file name (inside public/images)</label>
    <input type="text" name="image" value="<?= e($p['image'] ?? 'placeholder.svg') ?>">

    <label class="check"><input type="checkbox" name="is_featured" value="1" <?= !empty($p['is_featured']) ? 'checked' : '' ?>> Show on home page</label>

    <button class="btn">Save</button>
    <a href="/admin/products">Cancel</a>
</form>
