<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/views/partials/header.php';
?>
<div>
    <?php flash(); ?>
    <form action="?url=login" method="post">
        <input type="text" placeholder="Username" name="username" />
        <input type="password" placeholder="Password" name="password" />

        <input type="submit" value="Login" name="Login">
    </form>
</div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/views/partials/footer.php';
?>