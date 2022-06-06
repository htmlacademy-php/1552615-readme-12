<?php
require_once('helpers.php');
require_once('auth.php');

$connect = db_set_connection();
$post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT) ?? null;
$referer = $_SERVER['HTTP_REFERER'];

if (!$post_id) {
    http_response_code(404);
    die('Такой страницы не существует!');
}

$sql_post_check = "SELECT id FROM post WHERE id = '$post_id'";
$post_check = db_get_query('assoc', $connect, $sql_post_check) ?? null;
$sql_likes_check = "SELECT * FROM likes WHERE user_id = '$user_id' AND post_id = '$post_id'";
$likes_check = db_get_query('assoc', $connect, $sql_likes_check) ?? null;
if ($post_check && !$likes_check) {
    $add_like = "INSERT INTO likes (user_id, post_id) VALUES ('$user_id', '$post_id')";
    $stmt = db_get_prepare_stmt($connect, $add_like);
    $result = mysqli_stmt_execute($stmt);
}
elseif ($post_check && $likes_check) {
    $delete_like = "DELETE FROM likes WHERE user_id = '$user_id' AND post_id = '$post_id'";
    $stmt = db_get_prepare_stmt($connect, $delete_like);
    $result = mysqli_stmt_execute($stmt);
}
if (!$referer) {
    header("Location: /post.php?post_id=$post_id");
    exit;
}
header("Location: $referer");
exit;

