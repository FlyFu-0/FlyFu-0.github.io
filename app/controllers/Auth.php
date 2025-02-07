<?php

namespace Controllers;

use Models;
use Helpers\Tools;

class Auth
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

		require $_SERVER['DOCUMENT_ROOT']
			. '/app/views/authentication/register.php';
	}

	public function login()
	{
		if (!empty($_POST)) {
			$model = new Models\Auth();
			$model->login($_POST['username'], $_POST['password']);
		}

		require $_SERVER['DOCUMENT_ROOT'] . '/app/views/authentication/login.php';
	}

	public function logout()
	{
		session_start();
		unset($_SESSION['user_id']);
		unset($_SESSION['user_name']);
		unset($_SESSION['user_email']);
		flash('You have successfully logged out.');
		header('Location: index/');
	}
}
