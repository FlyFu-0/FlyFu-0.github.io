<?php

namespace Models;

use Core;

class Auth extends Core\DBBuilder
{
	public function register(
		string $username,
		string $email,
		string $password,
		string $ip,
		string $browser
	): void {
		$username = htmlspecialchars($username);
		if (!ctype_alnum($username)) {
			flash(
				'Username invalid format! Can contains only digits and letters.'
			);
			header('Location: /register/');
			die;
		}

		$email = htmlspecialchars($email);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			flash('Email invalid format!');
			header('Location: /register/');
			die;
		}

		if (strlen($password) < 5) {
			flash('Password must be at least 6 characters long!');
			header('Location: /register/');
			die;
		}

		$result = $this->getDB()
			->setSelect(['username'])
			->setWhere(['userName' => 'test'])
			->setWhere([
				[
					'field' => 'username',
					'operator' => '=',
					'value' => $username
				],
				['field' => 'email', 'operator' => '=', 'value' => $email],
			])
			->execute();

		if (count($result)) {
			flash('Username or Email already taken.');
			header('Location: /register/');
			die;
		}

		$password = htmlspecialchars($password);
		$passwordHash = password_hash($password, PASSWORD_DEFAULT);

		$this->getDB()
			->setInsert()
			->setInsertData([
				'username' => $username,
				'email' => $email,
				'passwordHash' => $passwordHash,
				'register_ip' => $ip,
				'browser' => $browser,
			])
			->execute();

		header('Location: /login/');
	}

	public function login(string $username, string $password): void
	{
		$username = htmlspecialchars($username);

		$user = $this->getDB()
			->setSelect(['id', 'username', 'email', 'passwordHash'])
			->setWhere(
				[
					[
						'field' => 'username',
						'operator' => '=',
						'value' => $username
					]
				]
			)
			->execute()[0];

		if (!isset($user)) {
			flash('Username is incorrect.');
			header('Location: /login/');
			die;
		}

		if (password_verify($password, $user['passwordHash'])) {
			if (password_needs_rehash(
				$user['passwordHash'],
				PASSWORD_DEFAULT
			)
			) {
				$newHash = password_hash(
					$_POST['passwordHash'],
					PASSWORD_DEFAULT
				);
				$hashUpdate = $this->getDB()
					->setUpdate($this->getTable(), ['passwordHash' => $newHash])
					->setWhere(
						[
							[
								'field' => 'username',
								'operator' => '=',
								'value' => $username
							]
						]
					)
					->execute();
			}
			$_SESSION['user_id'] = $user['id'];
			$_SESSION['user_name'] = $user['username'];
			$_SESSION['user_email'] = $user['email'];
			flash('Login successfully!');
			header('Location: /');
			die;
		}

		flash('Password is incorrect.');
		header('Location: /login/');
	}

	function getTable(): string
	{
		return 'user';
	}
}
