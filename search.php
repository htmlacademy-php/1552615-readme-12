<?php

require_once('auth.php');
require_once('helpers.php');

$connect = db_set_connection();
$path = (pathinfo(__FILE__, PATHINFO_BASENAME));

$search = trim(filter_input(INPUT_GET, 'q', FILTER_SANITIZE_SPECIAL_CHARS)) ?? NULL;

$posts = [];
if ($search) {

    $is_hashtag = ((substr($search, 0, 1)) === '#') ? substr($search, 1) : NULL;

    $sql_query = "SELECT post.*, u.user_login, u.avatar, ct.classname,
    (SELECT COUNT(id) FROM comments WHERE comments.post_id = post.id) AS total_comm,
    (SELECT COUNT(id) FROM likes WHERE likes.post_id = post.id) AS total_likes
    FROM post
        LEFT JOIN user u ON post.user_id = u.id
        LEFT JOIN content_type ct ON type_id = ct.id
    WHERE MATCH(post.title, post.text_content) AGAINST('$search')";

    if ($is_hashtag) {
        $sql_query = "SELECT hp.post_id, post.*, u.user_login, u.avatar, ct.classname,
        (SELECT COUNT(id) FROM comments
            WHERE comments.post_id = post.id) AS total_comm,
        (SELECT COUNT(id) FROM likes
            WHERE likes.post_id = post.id) AS total_likes
        FROM hashtag
            LEFT JOIN hashtags_posts hp ON hashtag.id = hp.hashtag_id
            LEFT JOIN post ON hp.post_id = post.id
            LEFT JOIN user u ON post.user_id = u.id
            LEFT JOIN content_type ct ON post.type_id = ct.id
        WHERE hashtag.hashtag LIKE '$is_hashtag'
        ORDER BY published_at";
    }

    $posts = db_get_query('all', $connect, $sql_query);
}

$search_content = include_template('search-layout.php', ['search' => $search, 'posts' => $posts]);

$layout = include_template('layout.php', ['content' => $search_content, 'title' => 'readme: страница результатов поиска', 'is_auth' => $is_auth, 'user_name' => $user_name, 'avatar' => $user_avatar, 'path' => $path, 'search' => $search, 'user_id' => $user_id]);

print($layout);
