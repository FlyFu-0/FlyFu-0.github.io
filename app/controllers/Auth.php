<?php

namespace Controllers;

use Core;
use Models;
use Helpers\Tools;

class Auth extends Core\Controller
{
    public function register(): \Core\Page
    {
        $this->title = 'Register';

        if (!empty($_POST)) {
            try {
                $model = new Models\Auth();
                $model->register(
                    $_POST['username'],
                    $_POST['email'],
                    $_POST['password'],
                    Tools::get_ip(),
                    $_SERVER['HTTP_USER_AGENT']
                );
            } catch (\Exception $e) {
                $this->error = $e->getMessage();
            }
        }

        return $this->render('auth/register');
    }

    public function login(): \Core\Page
    {
        $this->title = 'Login';
        if (!empty($_POST)) {
            try {
                $model = new Models\Auth();
                $model->login($_POST['username'], $_POST['password']);
            } catch (\Exception $e) {
                $this->error = $e->getMessage();
            }
        }

        return $this->render('auth/login');
    }

    public function logout(): void
    {
        session_start();
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_email']);
        header('Location: /');
    }
}
