<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/views/partials/header.php';
?>
<div>
    <?php flash(); ?>
    <form action="?url=register" method="post">
        <input type="text" placeholder="Username" name="username" />
        <input type="email" placeholder="Email" name="email" />
        <input type="password" placeholder="Password" name="password" />

        <input type="submit" value="Register" name="register">
    </form>
</div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/views/partials/footer.php';
?>