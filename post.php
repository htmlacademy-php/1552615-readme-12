<?php

require_once('auth.php');
require_once('helpers.php');
require_once('hashtags.php');
require_once('add_comment.php');

$connect = db_set_connection();
$path = (pathinfo(__FILE__, PATHINFO_BASENAME));
$post_url = '/' . $path;

if (isset($_GET['post_id'])) {
    $post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);
} else {
    http_response_code(404);
    die('Такой страницы не существует!');
};

$sql_post_id_query = "SELECT id FROM posts WHERE id = $post_id";
$sql_post_id = db_get_query('assoc', $connect, $sql_post_id_query);

if ($post_id === $sql_post_id['id']) {
    $sql_post_query = "SELECT posts.*, u.login, u.avatar, u.created_at, ct.classname, COUNT(DISTINCT comm.id) AS total_comm
    FROM posts
        LEFT JOIN users u ON posts.user_id = u.id
        LEFT JOIN content_types ct ON type_id = ct.id
        LEFT JOIN comments comm ON posts.id = comm.post_id
    WHERE posts.id = $post_id";
    $post_watch_update = "UPDATE posts SET watch_count = watch_count + 1 WHERE id = ?";
    $stmt = db_get_prepare_stmt($connect, $post_watch_update, [$post_id]);
    $result = mysqli_stmt_execute($stmt);
} else {
    http_response_code(404);
    die('Такой страницы не существует!');
};
$sql_post = db_get_query('assoc', $connect, $sql_post_query);

$current_user = $sql_post['user_id'];
$user_subs = [];
$user_subs = get_subscribers($user_id, $connect);
$comments = show_comments($post_id, $connect);

$sql_total_posts = get_total_from_db('id', 'posts', 'user_id', $current_user, $connect);
$sql_total_likes = get_total_from_db('id', 'likes', 'post_id', $post_id, $connect);
$sql_total_subs = get_total_from_db('user_id', 'subscriptions', 'to_user_id', $current_user, $connect);

$active_post = include_template('post-' . $sql_post['classname'] . '.php', ['post' => $sql_post]);

$post_layout = include_template('post-layout.php', ['active_post' => $active_post, 'post' => $sql_post, 'totalpost' => $sql_total_posts, 'likes' => $sql_total_likes, 'subs' => $sql_total_subs, 'hashtags' => $hashtags, 'user_subs' => $user_subs, 'errors' => $errors, 'user_id' => $user_id, 'comments' => $comments]);

$layout = include_template('layout.php', ['content' => $post_layout, 'title' => 'readme: публикация', 'is_auth' => $is_auth, 'user_name' => $user_name, 'avatar' => $user_avatar, 'path' => $path, 'user_id' => $user_id]);

print($layout);
