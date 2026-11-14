<a class="card" href="/product?id=<?= $p['id'] ?>">
    <img src="/images/<?= e($p['image']) ?>" alt="<?= e($p['name']) ?>">
    <strong><?= e($p['name']) ?></strong>
    <span class="muted"><?= e($p['color']) ?></span>
    <span class="price"><?= price($p['price']) ?></span>
    <?php if ($p['stock'] == 0): ?><span class="badge">Sold out</span><?php endif; ?>
</a>
