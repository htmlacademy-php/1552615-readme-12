<?php

require_once('auth.php');
require_once('helpers.php');

$connect = db_set_connection();
$path = (pathinfo(__FILE__, PATHINFO_BASENAME));

$search = trim(filter_input(INPUT_GET, 'q', FILTER_SANITIZE_SPECIAL_CHARS)) ?? null;

$posts = [];
if ($search) {
    $is_hashtag = ((substr($search, 0, 1)) === '#') ? substr($search, 1) : null;

    $sql_query = "SELECT posts.*, u.login, u.avatar, ct.classname,
    (SELECT COUNT(id) FROM comments WHERE comments.post_id = posts.id) AS total_comm,
    (SELECT COUNT(id) FROM likes WHERE likes.post_id = posts.id) AS total_likes
    FROM posts
        LEFT JOIN users u ON posts.user_id = u.id
        LEFT JOIN content_types ct ON type_id = ct.id
    WHERE MATCH(posts.title, posts.text_content) AGAINST('$search')";

    if ($is_hashtag) {
        $sql_query = "SELECT hp.post_id, posts.*, u.login, u.avatar, ct.classname,
        (SELECT COUNT(id) FROM comments
            WHERE comments.post_id = posts.id) AS total_comm,
        (SELECT COUNT(id) FROM likes
            WHERE likes.post_id = posts.id) AS total_likes
        FROM hashtags
            LEFT JOIN hashtags_posts hp ON hashtags.id = hp.hashtag_id
            LEFT JOIN posts ON hp.post_id = posts.id
            LEFT JOIN users u ON posts.user_id = u.id
            LEFT JOIN content_types ct ON posts.type_id = ct.id
        WHERE hashtags.hashtag LIKE '$is_hashtag'
        ORDER BY published_at";
    }

    $posts = db_get_query('all', $connect, $sql_query);
}

$search_content = include_template('search-layout.php', ['search' => $search, 'posts' => $posts]);

$layout = include_template('layout.php', ['content' => $search_content, 'title' => 'readme: страница результатов поиска', 'is_auth' => $is_auth, 'user_name' => $user_name, 'avatar' => $user_avatar, 'path' => $path, 'search' => $search, 'user_id' => $user_id]);

print($layout);
