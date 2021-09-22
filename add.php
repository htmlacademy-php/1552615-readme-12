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

// $required_fields = [$sql_one_type['classname'] . '-heading', 'cite-text', 'quote-author', 'video-heading', 'post-text', 'post-link'];

$errors = [];

$rules = [
    $sql_one_type['classname'] . '-heading' => function() {
        return validateFilled($sql_one_type['classname'] . '-heading');
    },
    'cite-text' => function() {
        return validateFilled('cite-text');
    },
    'quote-author' => function() {
        return validateFilled('quote-author');
    },
    'video-heading' => function() {
        return validateFilled('video-heading');
    },
    'post-text' => function() {
        return validateFilled('post-text');
    },
    'post-link' => function() {
        return validateFilled('post-link');
    },
    'photo-url' => function() {
        return validateUrl('photo-url');
    },
    'userpic-file-photo' => function() {
        return validateFile('userpic-file-photo');
    },
    $sql_one_type['classname'] . '-tags' => function() {
        return validateTags($sql_one_type['classname'] . '-tags');
    }

];

foreach ($required_fields as $field) {
    validateFilled($field);
};

foreach ($_POST as $key => $value) {
    validateUrl($key);
};

$adding_post = include_template('adding-post.php', ['active_form' => $active_form, 'types' => $sql_types, 'content_type_id' => $content_type_id, 'one_type' => $sql_one_type, 'url' => $add_url]);

$add_post_layout = include_template('layout.php', ['content' => $adding_post, 'title' => 'readme: добавление публикации', 'is_auth' => $is_auth, 'user_name' => $user_name]);

print($add_post_layout);
