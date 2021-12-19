<?php
require_once('helpers.php');

$is_auth = rand(0, 1);
$user_name = 'Ильнур';
$connect = mysqli_connect("localhost", "root", "root", "readme");
if ($connect == false) {
    die('Connection error: ' . mysqli_connect_error());
};
mysqli_set_charset($connect, "utf8");

$scriptname = pathinfo(__FILE__, PATHINFO_BASENAME);
$add_url = '/' . $scriptname;
$errors = [];

$sql_types_query = "SELECT * FROM content_type";
$sql_types = db_get_query('all', $connect, $sql_types_query);

$content_type_id = '';
$classname = '';

if (isset($_GET['id'])) {
    $content_type_id = intval($_GET['id']);
} else {
    $content_type_id = '1';
};

foreach ($sql_types as $type) {
    if ($content_type_id == $type['id']) {
        $classname = $type['classname'];
    };
};
$form_errors = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['id'];
    $content_type_id = $post_id;
    //задаем правила валидации соответствующих полей
    $rules = [
        'heading' => function () {
            return validateFilled('heading');
        },
        'cite-text' => function () {
            return validateFilled('cite-text');
        },
        'quote-author' => function () {
            return validateFilled('quote-author');
        },
        'video-url' => function () {
            if (!validateFilled('video-url')) {
                return validateUrl('video-url');
            };
            return validateFilled('video-url');
        },
        'post-text' => function () {
            return validateFilled('post-text');
        },
        'post-link' => function () {
            if (!validateFilled('post-link')) {
                return validateUrl('post-link');
            };
            return validateFilled('post-link');
        },
        'photo-url' => function () {
            if (!validateFilledPhoto() && $_POST['photo-url']) {
                return validateUrl('photo-url');
            };
            return validateFilledPhoto('photo-url');
        },
        'userpic-file-photo' => function () {
            if (!validateFilledPhoto()) {
                return validateFile('userpic-file-photo');
            };
            return validateFilledPhoto();
        },
        'tags' => function () {
            return validateTags('tags');
        },
    ];
    //проходимся по всем полям формы и проверяем на заданные правила
    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        };
    };
    // если нет ошибок добавляем картинки из ссылки или загружаемые пользователем
    if (!empty($_POST['photo-url']) && !$errors['photo-url']) {
        $errors['photo-url'] = downloadFileFromUrl('photo-url');
    };
    if (!empty($_POST['userpic-file-photo']) && !$errors['userpic-file-photo']) {
        $errors['userpic-file-photo'] = uploadFile(('userpic-file-photo'));
    };
    if (!empty($_POST['video-url']) && !$errors['video-url']) {
        $errors['video-url'] = check_youtube_url($_POST['video-url']);
        if ($errors['video-url'] == '1' || $errors['video-url'] == 'true') {
            $errors['video-url'] = '';
        };
    };
    $errors = array_filter($errors);

    //если совсем нет ошибок, то добавляем пост в бд и отрисовываем на странице,
    //если есть ошибки - возращаемся обратно на страницу с формой
    $db_post = [];
    $db_field = '';
    $sql = '';

    if (empty($errors)) {
        $form_errors = '';
        $db_post['title'] = $_POST['heading'];
        $type_id = $post_id;
        if ($post_id == '1') {
            $db_field = 'text_content';
            $db_post['text_content'] = $_POST['post-text'];
            $user_id = 1;
        } elseif ($post_id == '2') {
            $db_field = 'picture';
            if (isset($_FILES['userpic-file-photo'])) {
                $db_post['picture'] = $_FILES['userpic-file-photo']['name'];
            } else {
                $db_post['picture'] = basename($_POST['photo-url']);
            };
            $user_id = 2;
        } elseif ($post_id == '3') {
            $db_field = 'video';
            $db_post['video'] = $_POST['video-url'];
            $user_id = 3;
        } elseif ($post_id == '4') {
            $db_field = 'link';
            $db_post['link'] = $_POST['post-link'];
            $user_id = 1;
        } elseif ($post_id == '5') {
            $db_field = 'text_content, quote_author';
            $db_post['text_content'] = $_POST['cite-text'];
            $db_post['quote_author'] = $_POST['quote-author'];
            $user_id = 2;
        };

        $sql = "INSERT INTO post (title, $db_field, user_id, type_id)
                VALUES (?, ?, $user_id, $type_id)";
        if ($post_id == '5') {
            $sql = "INSERT INTO post (title, $db_field, user_id, type_id)
                    VALUES (?, ?, ?, $user_id, $type_id)";
        };
        $stmt = db_get_prepare_stmt($connect, $sql, $db_post);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $db_post_id = mysqli_insert_id($connect);
            header("Location: post.php?post_id=" . $db_post_id);
        } else {
            die(print_r(mysqli_stmt_error_list($stmt)));
        };
    } else {
        $classname = $_POST['classname'];
        $form_errors = include_template('adding-post-forms/form-errors.php', ['errors' => $errors]);
    };
};

$form_header = include_template('adding-post-forms/add-header-form.php', ['errors' => $errors]);
$tag_form = include_template('adding-post-forms/add-tag-form.php', ['errors' => $errors]);
$active_form = include_template('adding-post-forms/adding-' . $classname . '-form.php', ['form_header' => $form_header, 'tag_form' => $tag_form, 'form_errors' => $form_errors, 'errors' => $errors]);

$adding_post = include_template('adding-post.php', ['active_form' => $active_form, 'types' => $sql_types, 'content_type_id' => $content_type_id, 'url' => $add_url]);

$add_post_layout = include_template('layout.php', ['content' => $adding_post, 'title' => 'readme: добавление публикации', 'is_auth' => $is_auth, 'user_name' => $user_name]);

print($add_post_layout);
