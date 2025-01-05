<?php
function db()
{

    static $connect = null;

    if ($connect === null) {

        $host = "127.0.0.1";
        $port = 3306;
        $user = "php_user";
        $password = "php_user";
        $dbname = "chat_app";

        $connect = mysqli_connect($host, $user, $password, $dbname, $port)
            or die('Could not connect to the database server' . mysqli_connect_error());
    }

    return $connect;
}
