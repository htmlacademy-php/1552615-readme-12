<?php

require_once('auth.php');
require_once('helpers.php');

$path = (pathinfo(__FILE__, PATHINFO_BASENAME));
$url = "/" . $path;

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
$sql_posts_query = "SELECT post.*, u.user_login, u.avatar, ct.classname,
(SELECT COUNT(id) FROM comments WHERE comments.post_id = post.id) AS total_comm,
(SELECT COUNT(id) FROM likes WHERE likes.post_id = post.id) AS total_likes
    FROM post
        LEFT JOIN user u ON user_id = u.id
        LEFT JOIN content_type ct ON type_id = ct.id
    $query_condition ORDER BY watch_count LIMIT 6";

$sql_types = db_get_query('all', $connect, $sql_types_query);
$sql_posts = db_get_query('all', $connect, $sql_posts_query);

$popular_content = include_template('popular-page.php', ['posts' => $sql_posts, 'types' => $sql_types, 'url' => $url, 'type_classname' => $type_classname]);

$layout = include_template('layout.php', ['content' => $popular_content, 'title' => 'readme: популярное', 'is_auth' => $is_auth, 'user_name' => $user_name, 'avatar' => $user_avatar, 'path' => $path]);

print($layout);
