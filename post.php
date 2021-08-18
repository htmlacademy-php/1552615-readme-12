<?php
require_once('helpers.php');

$connect = mysqli_connect("localhost", "root", "root", "readme");
if ($connect == false) {
    die("Connection error: " . mysqli_connect_error());
};
mysqli_set_charset($connect, "utf8");

$scriptname = pathinfo(__FILE__, PATHINFO_BASENAME);
$post_url = '/' . $scriptname;
// $sql_total_posts_query = "SELECT COUNT(id) as total_posts FROM post";
// $sql_total_posts = call_user_func_array('array_merge', db_get_query($connect, $sql_total_posts_query));

if (isset($_GET['post_id'])) {
    $post_id = intval($_GET['post_id']);
};

if ($post_id) {
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
$sql_post = call_user_func_array('array_merge', db_get_query($connect, $sql_post_query));

$current_user = $sql_post['user_id'];
$sql_total_posts_query = "SELECT COUNT(id) AS total_posts
FROM post
    WHERE user_id = $current_user
GROUP BY user_id";
$sql_total_posts = call_user_func_array('array_merge', db_get_query($connect, $sql_total_posts_query));

$sql_total_likes_query = "SELECT COUNT(id) AS total_likes
FROM likes
WHERE post_id = $post_id
GROUP BY post_id";
$sql_total_likes = call_user_func_array('array_merge', db_get_query($connect, $sql_total_likes_query));

$active_post = include_template('post-' . $sql_post['classname'] . '.php', ['post' => $sql_post]);

$post_details = include_template('post-details.php', ['active_post' => $active_post, 'post' => $sql_post, 'totalpost' => $sql_total_posts, 'likes' => $sql_total_likes]);

$post_layout = include_template('layout.php', ['content' => $post_details, 'title' => 'readme: публикация', 'is_auth' => $is_auth, 'user_name' => $user_name]);

print($post_layout);


