<?php

require_once('auth.php');
require_once('helpers.php');
require_once('hashtags.php');

$path = (pathinfo(__FILE__, PATHINFO_BASENAME));
$url = "/" . $path;
$type_classname = '';

$connect = db_set_connection();
if (isset($_GET['type'])) {
    $type_classname = htmlspecialchars($_GET['type']);
};

if ($type_classname) {
    $query_condition = "WHERE classname = '$type_classname'";
} else {
    $type_classname = '';
    $query_condition = "";
};

$sql_types_query = "SELECT * FROM content_type";
$sql_types = db_get_query('all', $connect, $sql_types_query);

$sql_posts_query = "SELECT post.*, u.user_login, u.avatar, ct.classname,
(SELECT COUNT(id) FROM comments WHERE comments.post_id = post.id) AS total_comm,
(SELECT COUNT(id) FROM likes WHERE likes.post_id = post.id) AS total_likes
FROM post
    LEFT JOIN user u ON post.user_id = u.id
    LEFT JOIN content_type ct ON type_id = ct.id $query_condition";
$sql_posts = db_get_query('all', $connect, $sql_posts_query);

$feed_layout = include_template('feed-layout.php', ['types' => $sql_types, 'posts' => $sql_posts, 'hashtags' => $hashtags, 'type_classname' => $type_classname, 'url' => $url, 'path' => $path]);

$layout = include_template('layout.php', ['content' => $feed_layout, 'title' => 'readme: моя лента', 'is_auth' => $is_auth, 'user_name' => $user_name, 'avatar' => $user_avatar, 'path' => $path]);

print($layout);
