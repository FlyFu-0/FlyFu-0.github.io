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
			throw new \Exception(
				'Username invalid format! Can contains only digits and letters'
			);
		}

		$email = htmlspecialchars($email);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			throw new \Exception('Email invalid format!');
		}

		if (strlen($password) < 5) {
			throw new \Exception(
				'Password must be at least 6 characters long!'
			);
		}

		$result = $this->getDB()
			->setSelect(['username'])
			->addWhere(['userName' => 'test'])
			->addWhere([
				[
					'field' => 'username',
					'operator' => '=',
					'value' => $username
				],
				['field' => 'email', 'operator' => '=', 'value' => $email],
			])
			->execute();

		if (count($result)) {
			throw new \Exception('Username or Email already taken');
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
			->addWhere(
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
			throw new \Exception('Username is incorrect');
		}

		if (!password_verify($password, $user['passwordHash'])) {
			throw new \Exception('Password is incorrect');
		}

		if (password_needs_rehash(
			$user['passwordHash'],
			PASSWORD_DEFAULT
		)
		) {
			$newHash = password_hash(
				$_POST['passwordHash'],
				PASSWORD_DEFAULT
			);
			$this->getDB()
				->setUpdate($this->getTable(), ['passwordHash' => $newHash])
				->addWhere(
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
		header('Location: /');
	}

	protected function getTable(): string
	{
		return 'user';
	}
}
