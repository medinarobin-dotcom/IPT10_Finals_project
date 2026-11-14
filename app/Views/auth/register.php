<form method="post" action="/register" class="box form">
    <h1>Create Account</h1>
    <?= csrf_field() ?>
    <label>Full name</label>
    <input type="text" name="name" required>
    <label>Email</label>
    <input type="email" name="email" required>
    <label>Password (at least 8 characters)</label>
    <input type="password" name="password" minlength="8" required>
    <button class="btn">Register</button>
    <p>Already registered? <a href="/login">Login</a></p>
</form>
