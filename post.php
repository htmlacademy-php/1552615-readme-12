<?php
require_once('helpers.php');

$connect = mysqli_connect("localhost", "root", "root", "readme");
if ($connect == false) {
    die("Connection error: " . mysqli_connect_error());
};
mysqli_set_charset($connect, "utf8");

$scriptname = pathinfo(__FILE__, PATHINFO_BASENAME);
$post_url = '/' . $scriptname . '?';
$sql_total_posts_query = "SELECT COUNT(id) as total_posts FROM post";
$sql_total_posts = call_user_func_array('array_merge', db_get_query($connect, $sql_total_posts_query));

echo(var_dump($sql_total_posts));

if (isset($_GET['post_id']) && $_GET['post_id'] <= $sql_total_posts['total_posts']) {
    $post_id = $_GET['post_id'];
    $sql_post_query = "SELECT post.*, u.user_login, u.avatar, ct.classname, comm.*, likes.*, um.*, sub.*, COUNT(sub.to_user_id) AS total_subs, COUNT(likes.post_id) AS total_likes, COUNT(comm.id) AS total_comm, COUNT(um.id) AS total_um
    FROM post
        LEFT JOIN user u ON post.user_id = u.id
        LEFT JOIN content_type ct ON type_id = ct.id
        LEFT JOIN comments comm ON post.id = comm.post_id
        LEFT JOIN likes ON post.id = likes.post_id
        LEFT JOIN user_message um ON post.user_id = um.user_id
        LEFT JOIN subscribtions sub ON post.user_id = sub.user_id
    WHERE post.id = $post_id";
} else {
    http_response_code(404);
    print('Такой страницы не существует!');
};

$sql_post = call_user_func_array('array_merge', db_get_query($connect, $sql_post_query));

$active_post = include_template('post-' . $sql_post['classname'] . '.php', ['post' => $sql_post]);

$post_details = include_template('post-details.php', ['active_post' => $active_post, 'post' => $sql_post]);

$post_layout = include_template('layout.php', ['content' => $post_details, 'title' => 'readme: публикация', 'is_auth' => $is_auth, 'user_name' => $user_name]);

print($post_layout);
