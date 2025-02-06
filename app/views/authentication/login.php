<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/views/partials/header.php';
?>
<div class="container">
    <div class="nav">
        <a href="<?= $_SERVER['HTTP_REFERER'] ?>">Back</a>
    </div>
    <?php flash(); ?>
    <form action="?url=login" method="post" class="auth-form">
        <input type="text" placeholder="Username" name="username" required autofocus />
        <input type="password" placeholder="Password" name="password" required />

        <input type="submit" value="Login" name="Login">
    </form>
</div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/views/partials/footer.php';
?>