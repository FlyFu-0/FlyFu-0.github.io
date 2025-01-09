<?php
session_start();

function pdo() : PDO
{
    static $pdo;

    if (!$pdo) {
        $config = include $_SERVER['DOCUMENT_ROOT'] . '/config.php';
        $dsn = 'mysql:dbname=' . $config['dbname'] . ';host=' . $config['host'];
        $pdo = new PDO($dsn, $config['user'], $config['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    return $pdo;
}
