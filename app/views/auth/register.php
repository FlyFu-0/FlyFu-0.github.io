<div class="container">
    <div class="nav">
        <a href="<?= $_SERVER['HTTP_REFERER'] ?>">Back</a>
    </div>
    <form action="register" method="post" class="auth-form">
        <input type="text" placeholder="Username" name="username" required autofocus maxlength="50" />
        <input type="email" placeholder="Email" name="email" required maxlength="100" />
        <input type="password" placeholder="Password" name="password" required maxlength="32" />

        <input type="submit" value="Register" name="register">
    </form>
</div>