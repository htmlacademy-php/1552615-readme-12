<?php

require_once('auth.php');
require_once('helpers.php');
require_once('mail.php');

$connect = db_set_connection();

$path = (pathinfo(__FILE__, PATHINFO_BASENAME));

$add_url = '/' . $path;
$errors = [];
$classname = 'text';
$content_type_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) ?? '';
$form_errors = '';
$oldData = [];

$sql_types = db_get_query('all', $connect, "SELECT * FROM content_types");

foreach ($sql_types as $type) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($_POST['classname'] === $type['classname']) {
            $content_type_id = $type['id'];
        }

        $classname = $_POST['classname'];
    } else {
        if ($content_type_id === $type['id']) {
            $classname = $type['classname'];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            return validateFilled($_POST['video-url']) ?? validateUrl($_POST['video-url']);
        },
        'post-text' => function () {
            return validateFilled($_POST['post-text']);
        },
        'post-link' => function () {
            return validateFilled($_POST['post-link']) ?? validateUrl($_POST['post-link']);
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
            $errors['userpic-file-photo'] = uploadFile($_FILES['userpic-file-photo'], 'uploads');
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
        mysqli_query($connect, "START TRANSACTION");
        // Добавляем в бд хэштеги
        $tags = explode(' ', $_POST['tags']);
        $db_tags_id = [];
        foreach ($tags as $tag) {
            $sql = "INSERT INTO hashtags (hashtag) VALUES (?)";
            $tag_stmt = db_get_prepare_stmt($connect, $sql, [$tag]);
            $tag_result = mysqli_stmt_execute($tag_stmt);
            array_push($db_tags_id, mysqli_insert_id($connect));
        }
        // Добавляем в бд пост, если нет ошибок
        $form_errors = '';
        $db_post['title'] = $_POST['heading'];
        $db_post['text_content'] = null;
        $db_post['quote_author'] = $_POST['quote-author'] ?? null;
        $db_post['picture'] = null;
        $db_post['video'] = $_POST['video-url'] ?? null;
        $db_post['link'] = $_POST['post-link'] ?? null;
        $db_post['user_id'] = $user_id;
        $db_post['type_id'] = $content_type_id;

        if (isset($_POST['post-text'])) {
            $db_post['text_content'] = $_POST['post-text'];
        } elseif (isset($_POST['cite-text'])) {
            $db_post['text_content'] = $_POST['cite-text'];
        }

        if (isset($_FILES['userpic-file-photo']) && empty($_POST['photo-url'])) {
            $db_post['picture'] = $_FILES['userpic-file-photo']['name'];
        } elseif (!empty($_POST['photo-url'])) {
            $db_post['picture'] = basename($_POST['photo-url']);
        }

        $sql = "INSERT INTO posts (title, text_content, quote_author, picture, video, link, user_id, type_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $post_stmt = db_get_prepare_stmt($connect, $sql, $db_post);
        $post_result = mysqli_stmt_execute($post_stmt);
        $db_post_id = mysqli_insert_id($connect);
        // Добавляем в таблицу связи хэштегов с постами id поста и тега
        foreach ($db_tags_id as $tag_id) {
            $hp_query = mysqli_query($connect, "INSERT INTO hashtags_posts (hashtag_id, post_id) VALUES ('$tag_id', '$db_post_id')");
        }

        if ($hp_query) {
            mysqli_query($connect, "COMMIT");
            send_notice_to_subs($sender, $mailer, $user_id, $connect, $user_name);
            header("Location: post.php?post_id=" . $db_post_id);
            exit();
        } else {
            mysqli_query($connect, "ROLLBACK");
            die(print_r(mysqli_stmt_error_list($post_stmt)));
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

$layout = include_template('layout.php', ['content' => $adding_post, 'title' => 'readme: добавление публикации', 'is_auth' => $is_auth, 'user_name' => $user_name, 'avatar' => $user_avatar, 'path' => $path, 'user_id' => $user_id]);

print($layout);
