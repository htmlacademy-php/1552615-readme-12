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

$content_type_id = '1';
if (isset($_GET['id'])) {
    $content_type_id = intval($_GET['id']);
};

$sql_types_query = "SELECT * FROM content_type";
$sql_types = db_get_query('all', $connect, $sql_types_query);

if ($content_type_id) {
    $sql_one_type_query = "SELECT * FROM content_type
    WHERE id = $content_type_id";
};
$sql_one_type = db_get_query('assoc', $connect, $sql_one_type_query);
$form_header = include_template('adding-post-forms/add-header-form.php', ['one_type' => $sql_one_type]);
$tag_form = include_template('adding-post-forms/add-tag-form.php', ['one_type' => $sql_one_type]);
$active_form = include_template('adding-post-forms/adding-' . $sql_one_type['classname'] . '-form.php', ['form_header' => $form_header, 'tag_form' => $tag_form]);

$errors = [];

$rules = [
    $sql_one_type['classname'] . '-heading' => ['required', 'string'],
    'cite-text' => ['required', 'string'],
    'quote-author' => ['required', 'string'],
    'video-heading' => ['required', 'url'],
    'post-text' => ['required', 'string'],
    'post-link' => ['required', 'url'],
    'photo-url' => 'url',
    'video-url' => ['required', 'url'],
    'userpic-file-photo' => 'file',
    $sql_one_type['classname'] . '-tags' => ['tag'],
];

foreach ($_POST as $key => $value) {
    if (isset($rules[$key])) {
        if (in_array('required', $rules[$key])) {
            $errors[$key] = validateFilled($key);
        } elseif (in_array('url', $rules[$key])) {
            $errors[$key] = validateUrl($key);
        } elseif (in_array('file', $rules[$key])) {
            $errors[$key] = validateFile($key);
        } elseif (in_array('tag', $rules[$key])) {
            $errors[$key] = validateTags($key);
        };
    };
};
$errors = array_filter($errors);
var_dump($errors);


$adding_post = include_template('adding-post.php', ['active_form' => $active_form, 'types' => $sql_types, 'content_type_id' => $content_type_id, 'one_type' => $sql_one_type, 'url' => $add_url]);

$add_post_layout = include_template('layout.php', ['content' => $adding_post, 'title' => 'readme: добавление публикации', 'is_auth' => $is_auth, 'user_name' => $user_name]);

print($add_post_layout);
