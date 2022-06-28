<?php

session_start();
if (isset($_SESSION['user'])) {
    header("Location: /feed.php");
    exit();
}

require_once('helpers.php');

$connect = db_set_connection();
$errors = [];
$post = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post = $_POST;
    // Проверяем на заполнение обязательные поля
    $rules = [
        'login' => function () {
            return validateFilled($_POST['login']) ?? validateEmail($_POST['login']);
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

    $errors = array_filter($errors);

    $login = mysqli_real_escape_string($connect, $post['login']);
    $sql_query = "SELECT * FROM users WHERE email = '$login'";
    $result = mysqli_query($connect, $sql_query);
    $user = $result ? mysqli_fetch_array($result, MYSQLI_ASSOC) : null;

    if ($user && empty($errors)) {
        $pass_check = password_verify($post['password'], $user['password']);
        if ($pass_check) {
            $_SESSION['user'] = $user;
        } else {
            $errors['password'] = 'Неверный пароль';
        }
    }
    if (!$user && empty($errors['login'])) {
        $errors['login'] = 'Такой пользователь не найден';
    }

    if (isset($_SESSION['user'])) {
        header("Location: /feed.php");
        exit();
    }
}

$main_layout = include_template('main-layout.php', ['errors' => $errors, 'post' => $post]);
print($main_layout);
