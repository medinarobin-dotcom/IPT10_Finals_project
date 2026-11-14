<form method="post" action="/login" class="box form">
    <h1>Login</h1>
    <?= csrf_field() ?>
    <label>Email</label>
    <input type="email" name="email" required>
    <label>Password</label>
    <input type="password" name="password" required>
    <button class="btn">Login</button>
    <p>No account yet? <a href="/register">Register</a></p>
    <p class="muted small">Demo customer: juan@example.com / password123<br>Demo admin: admin@example.com / admin123</p>
</form>
