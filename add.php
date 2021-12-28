<?php
require_once('helpers.php');

$is_auth = rand(0, 1);
$user_name = 'Ильнур';
$connect = mysqli_connect("localhost", "root", "root", "readme");
if ($connect == false) {
    die('Connection error: ' . mysqli_connect_error());
}
mysqli_set_charset($connect, "utf8");

$scriptname = pathinfo(__FILE__, PATHINFO_BASENAME);
$add_url = '/' . $scriptname;
$errors = [];
$classname = 'text';
$content_type_id = intval($_GET['id']) ?? '';
$form_errors = '';
$oldData = [];

$sql_types = db_get_query('all', $connect, "SELECT * FROM content_type");

foreach ($sql_types as $type) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['classname'] == $type['classname']) {
            $content_type_id == $type['id'];
        }

        $classname = $_POST['classname'];
    } else {
        if ($content_type_id == $type['id']) {
            $classname = $type['classname'];
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $oldData = $_POST;
    //задаем правила валидации соответствующих полей
    $rules = [
        'heading' => function () {
            return validateFilled($_POST['heading']);
        },
        'cite-text' => function () {
            return validateFilled($_POST['cite-text']);
        },
        'quote-author' => function () {
            return validateFilled($_POST['quote-author']);
        },
        'video-url' => function () {
            if (!validateFilled($_POST['video-url'])) {
                return validateUrl($_POST['video-url']);
            }
            return validateFilled($_POST['video-url']);
        },
        'post-text' => function () {
            return validateFilled($_POST['post-text']);
        },
        'post-link' => function () {
            if (!validateFilled($_POST['post-link'])) {
                return validateUrl($_POST['post-link']);
            };
            return validateFilled($_POST['post-link']);
        },
        'photo-url' => function () {
            if (!empty($_POST['photo-url'])) {
                return validateUrl($_POST['photo-url']);
            }
            return validateFilledPhoto();
        },
        'tags' => function () {
            return validateTags($_POST['tags']);
        },
    ];
    //проходимся по всем полям формы и проверяем на заданные правила
    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    //валидируем пользовательский файл
    if (isset($_FILES['userpic-file-photo'])) {
        $errors['userpic-file-photo'] = validateFilledPhoto();

        if (!empty($_FILES['userpic-file-photo']['name'])) {
            $errors['userpic-file-photo'] = validateFile($_FILES['userpic-file-photo']);
        }
        if (empty($errors['userpic-file-photo'])) {
            $errors['userpic-file-photo'] = uploadFile($_FILES['userpic-file-photo']);
        }
    }

    // если нет ошибок добавляем картинки из ссылки
    if (!empty($_POST['photo-url']) && empty($errors['photo-url'])) {
        $errors['photo-url'] = downloadFileFromUrl($_POST['photo-url']);
    }
    //если нет ошибок добавляем проверяем существует ли ссылка на видео в Youtube
    if (!empty($_POST['video-url']) && empty($errors['video-url'])) {
        $result = check_youtube_url($_POST['video-url']);
        if ($result !== true) {
            $errors['video-url'] = $result;
        }
    }
    $errors = array_filter($errors);

    //если совсем нет ошибок, то добавляем пост в бд и отрисовываем на странице,
    //если есть ошибки - возращаемся обратно на страницу с формой
    $db_post = [];
    $sql = '';

    if (empty($errors)) {
        $form_errors = '';
        $user_id = 2;
        $db_post['title'] = $_POST['heading'];
        $db_post['text_content'] = null;
        $db_post['quote_author'] = $_POST['quote-author'] ?? null;
        $db_post['picture'] = null;
        $db_post['video'] = $_POST['video-url'] ?? null;
        $db_post['link'] = $_POST['post-link'] ?? null;

        if (isset($_POST['post-text'])) {
            $db_post['text_content'] = $_POST['post-text'];
        } elseif (isset($_POST['cite-text'])) {
            $db_post['text_content'] = $_POST['cite-text'];
        }

        if (isset($_FILES['userpic-file-photo']) && empty($_POST['photo-url'])) {
            $db_post['picture'] = $_FILES['userpic-file-photo']['name'];
        } elseif(!empty($_POST['photo-url'])) {
            $db_post['picture'] = basename($_POST['photo-url']);
        }
        $sql = "INSERT INTO post (title, text_content, quote_author, picture, video, link, user_id, type_id)
                VALUES (?, ?, ?, ?, ?, ?, $user_id, $content_type_id)";
        $stmt = db_get_prepare_stmt($connect, $sql, $db_post);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $db_post_id = mysqli_insert_id($connect);
            header("Location: post.php?post_id=" . $db_post_id);

        } else {
            die(print_r(mysqli_stmt_error_list($stmt)));
        }
    } else {
        $classname = $_POST['classname'];
        $form_errors = include_template('form-errors.php', ['errors' => $errors]);
    }
}

$form_header = include_template('adding-post-forms/add-header-form.php', ['errors' => $errors, 'oldData' => $oldData]);
$tag_form = include_template('adding-post-forms/add-tag-form.php', ['errors' => $errors, 'oldData' => $oldData]);
$active_form = include_template('adding-post-forms/adding-' . $classname . '-form.php', ['form_header' => $form_header, 'tag_form' => $tag_form, 'form_errors' => $form_errors, 'errors' => $errors, 'oldData' => $oldData]);

$adding_post = include_template('adding-post.php', ['active_form' => $active_form, 'types' => $sql_types, 'url' => $add_url, 'classname' => $classname]);

$add_post_layout = include_template('layout.php', ['content' => $adding_post, 'title' => 'readme: добавление публикации', 'is_auth' => $is_auth, 'user_name' => $user_name]);

print($add_post_layout);
