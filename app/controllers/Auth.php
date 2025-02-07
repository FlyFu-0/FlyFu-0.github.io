<?php

namespace Controllers;

use Core\Controller;
use Models;
use Helpers\Tools;

class Auth extends Controller
{
	public function register()
	{
		if (!empty($_POST)) {
			$model = new Models\Auth();
			$model->register(
				$_POST['username'],
				$_POST['email'],
				$_POST['password'],
				Tools::get_ip(),
				$_SERVER['HTTP_USER_AGENT']
			);
		}

		$this->title = 'Register';

		return $this->render('auth/register');
	}

	public function login()
	{
		if (!empty($_POST)) {
			$model = new Models\Auth();
			$model->login($_POST['username'], $_POST['password']);
		}

		$this->title = 'Login';

		return $this->render('auth/login');
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
