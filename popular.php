<?php

require_once('auth.php');
require_once('helpers.php');

$connect = db_set_connection();

$type_classname = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'all';
$sort_by = filter_input(INPUT_GET, 'sort_by', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'popular';
$cur_page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT) ?? 1;
$max_posts = 9;
$page_items = 6;
$order_by = "";

$path = (pathinfo(__FILE__, PATHINFO_BASENAME));
$url = '/' . $path . '?';

$sql_total_posts_query = "SELECT COUNT(id) AS total_posts FROM posts";
$sql_total_posts = db_get_query('assoc', $connect, $sql_total_posts_query);
$total_posts = (int) $sql_total_posts['total_posts'];
$pages_count = ceil($total_posts / $page_items);
$offset = ($cur_page - 1) * $page_items;
$pages = range(1, $pages_count);

if ($type_classname !== 'all') {
    $query_condition = "WHERE classname = '$type_classname'";
} else {
    $query_condition = "";
};

$sorts_by = [
    'popular' => 'Популярность',
    'likes' => 'Лайки',
    'data' => 'Дата'
];

switch ($sort_by) {
    case 'popular':
        $order_by = "watch_count";
        break;
    case 'likes':
        $order_by = "total_likes";
        break;
    case 'data':
        $order_by = "published_at";
        break;
}

$condition = ($total_posts > $max_posts) ? "LIMIT $page_items OFFSET $offset" : "";

$sql_types_query = "SELECT * FROM content_types";
$sql_posts_query = "SELECT posts.*, u.login, u.avatar, ct.classname,
(SELECT COUNT(id) FROM comments WHERE comments.post_id = posts.id) AS total_comm,
(SELECT COUNT(id) FROM likes WHERE likes.post_id = posts.id) AS total_likes
    FROM posts
        LEFT JOIN users u ON user_id = u.id
        LEFT JOIN content_types ct ON type_id = ct.id
    $query_condition ORDER BY $order_by DESC $condition";

$sql_type_posts = "SELECT posts.*, u.login, u.avatar, ct.classname,
(SELECT COUNT(id) FROM comments WHERE comments.post_id = posts.id) AS total_comm,
(SELECT COUNT(id) FROM likes WHERE likes.post_id = posts.id) AS total_likes
    FROM posts
        LEFT JOIN users u ON user_id = u.id
        LEFT JOIN content_types ct ON type_id = ct.id
    $query_condition";

$sql_types = db_get_query('all', $connect, $sql_types_query);
$sql_posts = db_get_query('all', $connect, $sql_posts_query);
$posts_of_type = count(db_get_query('all', $connect, $sql_type_posts));

$popular_content = include_template('popular-page.php', ['posts' => $sql_posts, 'types' => $sql_types, 'url' => $url, 'path' => $path, 'type_classname' => $type_classname, 'sort_by' => $sort_by, 'sorts_by' => $sorts_by, 'total_posts' => $total_posts, 'posts_of_type' => $posts_of_type, 'max_posts' => $max_posts, 'cur_page' => $cur_page, 'pages' => $pages, 'page_items' => $page_items]);

$layout = include_template('layout.php', ['content' => $popular_content, 'title' => 'readme: популярное', 'is_auth' => $is_auth, 'user_name' => $user_name, 'avatar' => $user_avatar, 'path' => $path, 'user_id' => $user_id]);

print($layout);
