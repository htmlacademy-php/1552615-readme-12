<?php

require_once('auth.php');
require_once('helpers.php');

$connect = db_set_connection();

$path = (pathinfo(__FILE__, PATHINFO_BASENAME));

$post_url = '/' . $path;

if (isset($_GET['post_id'])) {
    $post_id = intval($_GET['post_id']);
} else {
    http_response_code(404);
    die('Такой страницы не существует!');
};

$sql_post_id_query = "SELECT id FROM post WHERE id = $post_id";
$sql_post_id = db_get_query('assoc', $connect, $sql_post_id_query);

if ($post_id == $sql_post_id['id']) {
    $sql_post_query = "SELECT post.*, u.user_login, u.avatar, ct.classname, COUNT(DISTINCT comm.id) AS total_comm
    FROM post
        LEFT JOIN user u ON post.user_id = u.id
        LEFT JOIN content_type ct ON type_id = ct.id
        LEFT JOIN comments comm ON post.id = comm.post_id
    WHERE post.id = $post_id";
} else {
    http_response_code(404);
    die('Такой страницы не существует!');
};
$sql_post = db_get_query('assoc', $connect, $sql_post_query);

$sql_hashtag_query = "SELECT hashtag.*, hp.post_id
                      FROM hashtag
                            LEFT JOIN hashtags_posts hp ON hashtag.id = hp.hashtag_id";
$sql_hashtags = db_get_query('all', $connect, $sql_hashtag_query);

$current_user = $sql_post['user_id'];

$sql_total_posts = get_total_from_db ('id', 'post', 'user_id', $current_user, $connect);
$sql_total_likes = get_total_from_db ('id', 'likes', 'post_id', $post_id, $connect);
$sql_total_subs = get_total_from_db ('user_id', 'subscribtions', 'to_user_id', $current_user, $connect);

$active_post = include_template('post-' . $sql_post['classname'] . '.php', ['post' => $sql_post]);

$post_layout = include_template('post-layout.php', ['active_post' => $active_post, 'post' => $sql_post, 'totalpost' => $sql_total_posts, 'likes' => $sql_total_likes, 'subs' => $sql_total_subs, 'hashtags' => $sql_hashtags]);

$layout = include_template('layout.php', ['content' => $post_layout, 'title' => 'readme: публикация', 'is_auth' => $is_auth, 'user_name' => $user_name, 'avatar' => $user_avatar, 'path' => $path]);

print($layout);
