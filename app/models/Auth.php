<?php

namespace Models;

use core\DB;
use PDO;

class Auth
{

	private PDO $db;

	public function __construct()
	{
		$this->db = DB::getInstance();
	}

	public function register(
		string $username,
		string $email,
		string $password,
		string $ip,
		string $browser
	) {
		$username = htmlspecialchars($username);
		if (!ctype_alnum($username)) {
			flash(
				'Username invalid format! Can contains only digits and letters.'
			);
			header('Location: ?url=register');
			die;
		}

		$email = htmlspecialchars($email);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			flash('Email invalid format!');
			header('Location: ?url=register');
			die;
		}

		if (strlen($password) < 5) {
			flash('Password must be at least 6 characters long!');
			header('Location: ?url=register');
			die;
		}

		$stmt = $this->db->prepare(
			"SELECT `username` FROM `user` WHERE `username` = :username OR `email` = :email"
		);
		$stmt->execute([
			'username' => $username,
			'email' => $email
		]);

		if ($stmt->rowCount() > 0) {
			flash('Username or Email already taken.');
			header('Location: ?url=register');
			die;
		}

		$password = htmlspecialchars($password);
		$passwordHash = password_hash($password, PASSWORD_DEFAULT);

		$stmt = $this->db->prepare(
			"INSERT INTO `user` (`username`, `email`, `passwordHash`, `register_ip`, `browser`) VALUES (:username, :email, :passwordHash, :ip, :browser)"
		);
		$stmt->execute([
			'username' => $username,
			'passwordHash' => $passwordHash,
			'email' => $email,
			'ip' => $ip,
			'browser' => $browser
		]);

		header('Location: ?url=login');
	}

	public function login(string $username, string $password)
	{
		$username = htmlspecialchars($username);

		$stmt = $this->db->prepare(
			"SELECT `id`, `username`, `email`, `passwordHash` FROM `user` WHERE `username` = :username"
		);
		$stmt->execute([
			'username' => $username
		]);
		if (!$stmt->rowCount()) {
			flash('Username is incorrect.');
			header('Location: ?url=login');
			die;
		}

		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		echo "<script>console.log('Okay')</script>";

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
				$hashUpdate = $this->db->prepare(
					"UPDATE `user` SET `passwordHash` = :newHash WHERE `username` = :username"
				);
				$hashUpdate->execute([
					'username' => $username,
					'passwordHash' => $newHash
				]);
			}
			$_SESSION['user_id'] = $user['id'];
			$_SESSION['user_name'] = $user['username'];
			$_SESSION['user_email'] = $user['email'];
			flash('Login successfully!');
			header('Location: /app/');
			die;
		}

		flash('Password is incorrect.');
		header('Location: ?url=login');
	}
}
