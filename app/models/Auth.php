<?php

namespace Models;

use core\DBConnector;
use PDO;

class Auth extends DBConnector
{
    public function register(
        string $username,
        string $email,
        string $password,
        string $ip,
        string $browser
    ): void
    {
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

        $result = $this->get(
            'user',
            ['username'],
            where: [
                ['field' => 'username', 'operator' => '=', 'value' => $username],
                ['field' => 'email', 'operator' => '=', 'value' => $email],
            ],
        );

        if (count($result)) {
            flash('Username or Email already taken.');
            header('Location: /register/');
            die;
        }

        $password = htmlspecialchars($password);
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $res = $this->insert('user',
            ['username' => $username,
                'email' => $email,
                'passwordHash' => $passwordHash,
                'register_ip' => $ip,
                'browser' => $browser,
            ]);

        header('Location: /login/');
    }

    public function login(string $username, string $password)
    {
        $username = htmlspecialchars($username);

        $user = $this->get('user',
            ['id', 'username', 'email', 'passwordHash'],
            where: ['username' => $username,])[0];

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
                $hashUpdate = $this->update('user', ['passwordHash' => $newHash], ['username' => $username]);
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
}
