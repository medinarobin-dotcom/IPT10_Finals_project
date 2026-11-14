<?php $user = current_user(); $currency = $_SESSION['currency'] ?? 'PHP'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Threadline Clothing Store</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header class="header">
    <div class="wrap header-row">
        <a href="/" class="logo">Threadline<span>.</span></a>
        <nav class="nav">
            <a href="/shop">Shop</a>
            <?php if ($user): ?>
                <a href="/orders">My Orders</a>
                <?php if ($user['role'] === 'admin'): ?><a href="/admin">Admin</a><?php endif; ?>
            <?php else: ?>
                <a href="/login">Login</a>
            <?php endif; ?>
            <a href="/cart">Cart (<?= Cart::count() ?>)</a>
        </nav>
        <form method="post" action="/currency" class="currency">
            <?= csrf_field() ?>
            <select name="currency" onchange="this.form.submit()" aria-label="Currency">
                <?php foreach (CurrencyService::CURRENCIES as $code => $symbol): ?>
                    <option value="<?= $code ?>" <?= $code === $currency ? 'selected' : '' ?>><?= $code ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <?php if ($user): ?>
            <form method="post" action="/logout"><?= csrf_field() ?><button class="link">Logout (<?= e($user['name']) ?>)</button></form>
        <?php endif; ?>
    </div>
</header>

<main class="wrap">
    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="flash <?= $_SESSION['flash']['type'] ?>"><?= e($_SESSION['flash']['message']) ?></div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <?= $content ?>
</main>

<footer class="footer wrap">
    Threadline Clothing Store &middot; Built with the CorePHP framework &middot; Currency rates by Frankfurter API
</footer>
</body>
</html>
