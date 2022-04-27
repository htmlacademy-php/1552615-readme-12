<?php
require_once('helpers.php');
require_once('auth.php');

$connect = db_set_connection();
$post_id = filter_input(INPUT_GET, 'post_id') ?? null;
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
    mysqli_query($connect, "START TRANSACTION");
    $add_like = mysqli_query($connect, "INSERT INTO likes (user_id, post_id) VALUES ('$user_id', '$post_id')");
    if ($add_like) {
        mysqli_query($connect, "COMMIT");
    }
    else {
        mysqli_query($connect, "ROLLBACK");
    }
}
elseif ($post_check && $likes_check) {
    mysqli_query($connect, "START TRANSACTION");
    $delete_like = mysqli_query($connect, "DELETE FROM likes WHERE user_id = '$user_id' AND post_id = '$post_id'");
    if ($delete_like) {
        mysqli_query($connect, "COMMIT");
    }
    else {
        mysqli_query($connect, "ROLLBACK");
    }
}
header("Location: $referer");
exit;

