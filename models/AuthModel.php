<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/db.php';

class AuthModel
{

    private $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function register($username, $email, $password, $ip, $browser)
    {
        $username = mysqli_escape_string($this->db, htmlspecialchars($username));
        $email = mysqli_escape_string($this->db, htmlspecialchars($email));

        $result = mysqli_query($this->db, "SELECT username FROM user WHERE username = '$username' OR email = '$email';");

        var_dump(mysqli_num_rows($result));
        if (mysqli_num_rows($result) > 0) {
            flash('Username or Email already taken.');
            header('Location: ?url=register');
            die;
        }

        $password = mysqli_escape_string($this->db, htmlspecialchars($password));
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        mysqli_query($this->db, "INSERT INTO `user` (`username`, `email`, `passwordHash`, `register_ip`, `browser`) VALUES ('$username', '$email', '$passwordHash', '$ip', '$browser')");

        header('Location: /');
    }

    public function login($username, $password)
    {
        $username = mysqli_escape_string($this->db, htmlspecialchars($username));

        $result = mysqli_query($this->db, "SELECT * FROM user WHERE username = '$username';");
        if (!mysqli_num_rows($result)) {
            flash('Username is incorrect.');
            header('Location: ?url=login');
            die;
        }

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['passwordHash'])) {
            if (password_needs_rehash($user['passwordHash'], PASSWORD_DEFAULT)) {
                $newHash = password_hash($_POST['passwordHash'], PASSWORD_DEFAULT);
                $hashUpdate = mysqli_query($this->db, "UPDATE `user` SET `passwordHash` = '$newHash' WHERE `username` = '$username';");
            }
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['user_email'] = $user['email'];
            flash('Login successfully!');
            header('Location: /');
            die;
        }

        flash('Password is incorrect.');
        header('Location: ?url=login');
    }
}
