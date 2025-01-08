<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/AuthModel.php';

class AuthController
{
    public function register()
    {
        if (!empty($_POST)) {
            $model = new AuthModel();
            $result = $model->register($_POST['username'], $_POST['email'], $_POST['password']);
        }

        require $_SERVER['DOCUMENT_ROOT'] . '/views/authentication/register.php';
    }

    public function login()
    {
        if (!empty($_POST)) {
            $model = new AuthModel();
            $result = $model->login($_POST['username'], $_POST['password']);
        }

        require $_SERVER['DOCUMENT_ROOT'] . '/views/authentication/login.php';
    }

    public function logout()
    {
        session_start();
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_email']);
        flash('You have successfully logged out.');
        header('Location: /');
    }
}
