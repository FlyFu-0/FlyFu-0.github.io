<?php
session_start();

function db()
{
    static $connect = null;

    if ($connect === null) {
        $config = include $_SERVER['DOCUMENT_ROOT'] . '/config.php';
        $connect = mysqli_connect($config['host'], $config['user'], $config['password'], $config['dbname'], $config['port']) or die('Could not connect to the database server' . mysqli_connect_error());
    }

    return $connect;
}
