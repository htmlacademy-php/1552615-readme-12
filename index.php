<?php
require_once('helpers.php');

$connect = mysqli_connect("localhost", "root", "root", "readme");
if ($connect == false) {
    die('Connection error: ' . mysqli_connect_error());
}
mysqli_set_charset($connect, "utf8");

$errors = [];
$login = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post = $_POST;
    $rules = [
        'login' => function () {
            return validateFilled($_POST['login']);
        },
        'password' => function () {
            return validateFilled($_POST['password']);
        }
    ];

    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }
    // var_dump($errors);
    // die();
    if (empty($errors)) {
        $login = mysqli_real_escape_string($connect, $post['login']);
        $sql_query = "SELECT * FROM user WHERE user_login = '$login'";
        $result = mysqli_query($connect, $sql_query);
        $user = $result ? mysqli_fetch_array($result, MYSQLI_ASSOC) : null;
    }
    // var_dump($user);
    // die();
	if (empty($errors) and $user) {
        if (password_verify($post['password'], $user['password'])) {
            $_SESSION['user'] = $user;
		}
		else {
            $errors['password'] = 'Неверный пароль';
            var_dump('Wrong password');
		}
	}
	else {
        $errors['login'] = 'Такой пользователь не найден';
        // var_dump('User not found');
	}
}

// die();
if (isset($_SESSION)) {
    header("Location: feed.php");
} else {
    $main_layout = include_template('main-layout.php', ['errors' => $errors]);
    print($main_layout);
}
