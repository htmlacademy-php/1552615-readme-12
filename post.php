<?php
require_once('helpers.php');

$connect = mysqli_connect("localhost", "root", "root", "readme");
if ($connect == false) {
    die("Connection error: " . mysqli_connect_error());
};
mysqli_set_charset($connect, "utf8");

$scriptname = pathinfo(__FILE__, PATHINFO_BASENAME);
$post_url = '/' . $scriptname;

if (isset($_GET['post_id'])) {
    $post_id = intval($_GET['post_id']);
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

$current_user = $sql_post['user_id'];

$sql_total_posts = get_total_from_db ('id', 'post', 'user_id', $current_user, $connect);
$sql_total_likes = get_total_from_db ('id', 'likes', 'post_id', $post_id, $connect);
$sql_total_subs = get_total_from_db ('user_id', 'subscribtions', 'to_user_id', $current_user, $connect);

$active_post = include_template('post-' . $sql_post['classname'] . '.php', ['post' => $sql_post]);

$post_details = include_template('post-details.php', ['active_post' => $active_post, 'post' => $sql_post, 'totalpost' => $sql_total_posts, 'likes' => $sql_total_likes, 'subs' => $sql_total_subs]);

$post_layout = include_template('layout.php', ['content' => $post_details, 'title' => 'readme: публикация', 'is_auth' => $is_auth, 'user_name' => $user_name]);

print($post_layout);
