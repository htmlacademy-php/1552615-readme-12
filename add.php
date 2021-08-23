<?php
require_once('helpers.php');
$connect = mysqli_connect("localhost", "root", "root", "readme");
if ($connect == false) {
    die("Connection error: " . mysqli_connect_error());
};
mysqli_set_charset($connect, "utf8");

$scriptname = pathinfo(__FILE__, PATHINFO_BASENAME);
$url = "/" . $scriptname;

if (isset($_GET['id'])) {
    $type_id = intval($_GET['id']);
};

$sql_types_query = "SELECT * FROM content_type";
$sql_types = db_get_query('all', $connect, $sql_types_query);



$adding_post = include_template('adding-post.php', ['types' => $sql_types, 'type_id' => $type_id, 'url' => $url]);

$add_post_layout = include_template('layout.php', ['content' => $adding_post, 'title' => 'readme: добавление публикации', 'is_auth' => $is_auth, 'user_name' => $user_name]);

print($add_post_layout);
