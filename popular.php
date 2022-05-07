<?php

require_once('auth.php');
require_once('helpers.php');


$posts = [
    [
        'title' => 'Цитата',
        'type' => 'post-quote',
        'content' => 'Мы в жизни любим только раз, а после ищем лишь похожих',
        'author_name' => 'Лариса',
        'avatar' => 'userpic-larisa-small.jpg',
        'published_at' => date_create(generate_random_date(0)),
    ],
    [
        'title' => 'Игра престолов',
        'type' => 'post-text',
        'content' => 'Не могу дождаться начала финального сезона своего любимого сериала!',
        'author_name' => 'Владик',
        'avatar' => 'userpic.jpg',
        'published_at' => date_create(generate_random_date(1)),
    ],
    [
        'title' => 'Наконец, обработал фотки!',
        'type' => 'post-photo',
        'content' => 'rock-medium.jpg',
        'author_name' => 'Виктор',
        'avatar' => 'userpic-mark.jpg',
        'published_at' => date_create(generate_random_date(2)),
    ],
    [
        'title' => 'Моя мечта',
        'type' => 'post-photo',
        'content' => 'coast-medium.jpg',
        'author_name' => 'Лариса',
        'avatar' => 'userpic-larisa-small.jpg',
        'published_at' => date_create(generate_random_date(3)),
    ],
    [
        'title' => 'Лучшие курсы',
        'type' => 'post-link',
        'content' => 'www.htmlacademy.ru',
        'author_name' => 'Владик',
        'avatar' => 'userpic.jpg',
        'published_at' => date_create(generate_random_date(4)),
    ],
];

$connect = db_set_connection();

$type_classname = filter_input(INPUT_GET, 'type') ?? 'all';
$sort_by = filter_input(INPUT_GET, 'sort_by') ?? 'popular';
$cur_page = filter_input(INPUT_GET, 'page') ?? 1;
$max_posts = 9;
$page_items = 6;
$order_by = "";

$path = (pathinfo(__FILE__, PATHINFO_BASENAME));
$url = '/' . $path . '?';


$sql_total_posts_query = "SELECT COUNT(id) AS total_posts FROM post";
$total_posts = db_get_query('assoc', $connect, $sql_total_posts_query);

$pages_count = ceil($total_posts['total_posts'] / $page_items);
$offset = ($cur_page - 1) * $page_items;
$pages = range(1, $pages_count);

if ($type_classname != 'all') {
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

$offset_condition = ($offset > 0) ? "OFFSET $offset" : "";
$limit_condition = ($total_posts['total_posts'] > 9) ? "LIMIT $page_items" : "";

$sql_types_query = "SELECT * FROM content_type";
$sql_posts_query = "SELECT post.*, u.user_login, u.avatar, ct.classname,
(SELECT COUNT(id) FROM comments WHERE comments.post_id = post.id) AS total_comm,
(SELECT COUNT(id) FROM likes WHERE likes.post_id = post.id) AS total_likes
    FROM post
        LEFT JOIN user u ON user_id = u.id
        LEFT JOIN content_type ct ON type_id = ct.id
    $query_condition ORDER BY $order_by DESC $limit_condition $offset_condition";

$sql_types = db_get_query('all', $connect, $sql_types_query);
$sql_posts = db_get_query('all', $connect, $sql_posts_query);
$posts_on_page = count($sql_posts);

$popular_content = include_template('popular-page.php', ['posts' => $sql_posts, 'types' => $sql_types, 'url' => $url, 'path' => $path, 'type_classname' => $type_classname, 'sort_by' => $sort_by, 'sorts_by' => $sorts_by, 'total_posts' => $total_posts, 'posts_on_page' => $posts_on_page,'cur_page' => $cur_page, 'pages' => $pages, 'page_items' => $page_items]);

$layout = include_template('layout.php', ['content' => $popular_content, 'title' => 'readme: популярное', 'is_auth' => $is_auth, 'user_name' => $user_name, 'avatar' => $user_avatar, 'path' => $path, 'user_id' => $user_id]);

print($layout);
