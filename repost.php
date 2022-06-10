<?php

require_once('helpers.php');
require_once('auth.php');

$connect = db_set_connection();
$post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT) ?? null;

if (!$post_id) {
    http_response_code(404);
    die('Такой страницы не существует!');
}

$sql_post_check = "SELECT id FROM post WHERE id = '$post_id'";
$post_check = db_get_query('assoc', $connect, $sql_post_check) ?? null;
if ($post_check) {
    mysqli_query($connect, "START TRANSACTION");
    $post_copy = mysqli_query($connect, "INSERT INTO post (is_repost, user_id, original_post_id, title, text_content, quote_author, picture, video, link, watch_count, type_id, original_author_id)
            SELECT '1', '$user_id', '$post_id', title, text_content, quote_author, picture, video, link, watch_count, type_id, user_id AS original_author_id
            FROM post WHERE id = '$post_id'");

    $repost_count_update = mysqli_query($connect, "UPDATE post SET repost_count = repost_count + 1 WHERE id = $post_id");
    if ($post_copy && $repost_count_update) {
        mysqli_query($connect, "COMMIT");
    } else {
        mysqli_query($connect, "ROLLBACK");
    }
}
header("Location: /profile.php?user_id=$user_id&tab=posts");
exit;
