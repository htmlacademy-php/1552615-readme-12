<?php

require_once('auth.php');
require_once('helpers.php');
require_once('hashtags.php');

$path = (pathinfo(__FILE__, PATHINFO_BASENAME));
$url = "/" . $path;
$type_classname = '';

$connect = db_set_connection();
if (isset($_GET['type'])) {
    $type_classname = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_SPECIAL_CHARS);
};

if ($type_classname) {
    $query_condition = "AND classname = '$type_classname'";
} else {
    $type_classname = '';
    $query_condition = "";
};

$sql_types_query = "SELECT * FROM content_types";
$sql_types = db_get_query('all', $connect, $sql_types_query);

$sql_posts_query = "SELECT posts.*, u.login, u.avatar, ct.classname, sub.to_user_id,
(SELECT COUNT(id) FROM comments WHERE comments.post_id = posts.id) AS total_comm,
(SELECT COUNT(id) FROM likes WHERE likes.post_id = posts.id) AS total_likes
FROM posts
    LEFT JOIN subscriptions sub ON posts.user_id = sub.to_user_id
    LEFT JOIN users u ON posts.user_id = u.id
    LEFT JOIN content_types ct ON type_id = ct.id
WHERE sub.user_id = '$user_id' $query_condition
    ORDER BY posts.published_at DESC";
$sql_posts = db_get_query('all', $connect, $sql_posts_query);

$feed_layout = include_template('feed-layout.php', ['types' => $sql_types, 'posts' => $sql_posts, 'hashtags' => $hashtags, 'type_classname' => $type_classname, 'url' => $url, 'path' => $path]);

$layout = include_template('layout.php', ['content' => $feed_layout, 'title' => 'readme: моя лента', 'is_auth' => $is_auth, 'user_name' => $user_name, 'avatar' => $user_avatar, 'path' => $path, 'user_id' => $user_id]);

print($layout);
