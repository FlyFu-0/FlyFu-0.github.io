<?php

namespace Controllers;

use Core\Controller;
use Models;
use Helpers\Tools;

class Auth extends Controller
{
	public function register()
	{
		$result = '';
		if (!empty($_POST)) {
			$model = new Models\Auth();
			$result = $model->register(
				$_POST['username'],
				$_POST['email'],
				$_POST['password'],
				Tools::get_ip(),
				$_SERVER['HTTP_USER_AGENT']
			);
		}

		$this->title = 'Register';
		$this->message = $result;

		return $this->render('auth/register');
	}

	public function login(): \Core\Page
	{
		$result = '';
		if (!empty($_POST)) {
			$model = new Models\Auth();
			$result = $model->login($_POST['username'], $_POST['password']);
		}

		$this->title = 'Login';
		$this->message = $result;

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
