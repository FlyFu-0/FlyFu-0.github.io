<div class="container">
    <div class="nav">
        <a href="<?= $_SERVER['HTTP_REFERER'] ?>">Back</a>
    </div>
    <?php flash(); ?>
    <form action="/login/" method="post" class="auth-form">
        <input type="text" placeholder="Username" name="username" required autofocus />
        <input type="password" placeholder="Password" name="password" required />

        <input type="submit" value="Login" name="Login">
    </form>
</div>