<?php

include_once('helpers.php');

$is_auth = 0;
$connect = mysqli_connect("localhost", "root", "root", "readme");
if ($connect == false) {
    die('Connection error: ' . mysqli_connect_error());
}
mysqli_set_charset($connect, "utf8");

$scriptname = pathinfo(__FILE__, PATHINFO_BASENAME);
$post_url = '/' . $scriptname;

$errors = [];
$form_errors = '';
$post = [];

$sql_users = db_get_query('all', $connect, "SELECT * FROM user");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post = $_POST;
    // определяем правила для полей
    $rules = [
        'email' => function () {
            return validateFilled($_POST['email']);
        },
        'login' => function () {
            return validateFilled($_POST['login']);
        },
        'password' => function () {
            return validateFilled($_POST['password']);
        },
        'password-repeat' => function () {
            return validateFilled($_POST['password-repeat']);
        },
    ];
    //проходимся по всем полям формы и проверяем на заданные правила
    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    // проверяем на соответствие данных в полях пароля и повтора пароля
    if ($_POST['password'] != $_POST['password-repeat']) {
        !$errors['password'] = 'Пароли не совпадают';
        !$errors['password-repeat'] = 'Пароли не совпадают';
    }
    // проверяем на наличие передаваемого email в бд
    foreach ($sql_users as $user) {
        if ($_POST['email'] == $user['email']) {
            $errors['email'] = 'Пользователь с таким email уже зарегистрирован';
        }
        if ($_POST['login'] == $user['user_login']) {
            $errors['login'] = 'Пользователь с таким логином уже зарегистрирован';
        }
    }

    // валидируем и загружаем пользовательский аватар
    if (!empty($_FILES['userpic-file']['name'])) {
        $errors['userpic-file'] = validateFile($_FILES['userpic-file']);
    }
    if (empty($errors['userpic-file'])) {
        $errors['userpic-file'] = uploadFile($_FILES['userpic-file']);
    }

    $errors = array_filter($errors);
    $db_post = [];
    $sql = '';

    // если нет ошибок, то грузим данные в бд
    if (empty($errors)) {
        $form_errors = '';
        $db_post['email'] = $_POST['email'];
        $db_post['user_login'] = $_POST['login'];
        $db_post['user_password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $db_post['userpic-file'] = $_FILES['userpic-file']['name'] ?? null;

        $sql = "INSERT INTO user (email, user_login, user_password, avatar)
                VALUES (?, ?, ?, ?)";
        $stmt = db_get_prepare_stmt($connect, $sql, $db_post);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $db_post_id = mysqli_insert_id($connect);
            header("Location: main.php");

        } else {
            die(print_r(mysqli_stmt_error_list($stmt)));
        }
    } else {
        $form_errors = include_template('form-errors.php', ['errors' => $errors]);
    }

}

$reg_page = include_template('reg-page.php', ['form_errors' => $form_errors, 'post' => $post, 'errors' => $errors]);

$reg_page_layout = include_template('layout.php', ['content' => $reg_page, 'title' => 'readme: регистрация', 'is_auth' => $is_auth]);

print($reg_page_layout);
