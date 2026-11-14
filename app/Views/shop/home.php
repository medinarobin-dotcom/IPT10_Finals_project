<section class="hero">
    <div>
        <h1>Everyday clothes, made to last.</h1>
        <p>Tees, shirts, denim and dresses. Cash on delivery anywhere in the Philippines.</p>
        <a class="btn" href="/shop">Shop now</a>
    </div>
    <img src="/images/linen-midi-dress-terracotta.svg" alt="Linen dress">
</section>

<h2>Categories</h2>
<div class="chips">
    <?php foreach ($categories as $c): ?>
        <a class="chip" href="/shop?category=<?= $c['id'] ?>"><?= e($c['name']) ?></a>
    <?php endforeach; ?>
</div>

<h2>Featured</h2>
<div class="grid">
    <?php foreach ($featured as $p) require __DIR__ . '/product-card.php'; ?>
</div>
