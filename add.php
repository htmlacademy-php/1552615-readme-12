<?php
require_once('helpers.php');

$connect = mysqli_connect("localhost", "root", "root", "readme");
if ($connect == false) {
    die('Connection error: ' . mysqli_connect_error());
};
mysqli_set_charset($connect, "utf8");

$scriptname = pathinfo(__FILE__, PATHINFO_BASENAME);
$add_url = '/' . $scriptname;

if (isset($_GET['id'])) {
    $content_type_id = intval($_GET['id']);
};

$sql_types_query = "SELECT * FROM content_type";
$sql_types = db_get_query('all', $connect, $sql_types_query);

if ($content_type_id) {
    $sql_one_type_query = "SELECT * FROM content_type
    WHERE id = $content_type_id";
    $sql_one_type = db_get_query('assoc', $connect, $sql_one_type_query);
    $form_header = include_template('adding-post-forms/add-header-form.php', ['one_type' => $sql_one_type]);
    $tag_form = include_template('adding-post-forms/add-tag-form.php', ['one_type' => $sql_one_type]);
    $active_form = include_template('adding-post-forms/adding-' . $sql_one_type['classname'] . '-form.php', ['form_header' => $form_header, 'tag_form' => $tag_form]);
} else {
    $active_form = '';
};

$required_fields = [$sql_one_type['classname'] . '-heading', 'cite-text', 'quote-author', 'video-heading', 'post-text', 'post-link'];
$errors = [];

foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $errors[$field] = 'Поле не заполнено';
    };
};

foreach ($_POST as $key => $value) {
    if ($key == "post-link" || $key == "video-heading") {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $errors[$key] = 'Ссылка должна быть корректной';
        };
    };
};



$adding_post = include_template('adding-post.php', ['active_form' => $active_form, 'types' => $sql_types, 'content_type_id' => $content_type_id, 'one_type' => $sql_one_type, 'url' => $add_url]);

$add_post_layout = include_template('layout.php', ['content' => $adding_post, 'title' => 'readme: добавление публикации', 'is_auth' => $is_auth, 'user_name' => $user_name]);

print($add_post_layout);
