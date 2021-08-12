<?php
require_once('helpers.php');

$connect = mysqli_connect("localhost", "root", "root", "readme");
if ($connect == false) {
    die("Connection error: " . mysqli_connect_error());
};
mysqli_set_charset($connect, "utf8");

$scriptname = pathinfo(__FILE__, PATHINFO_BASENAME);
$post_url = '/' . $scriptname . '?';

if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
    $sql_post_query = "SELECT post.*, u.user_login, u.avatar, ct.classname, comm.*, likes.*, um.*, sub.*, SUM(sub.to_user_id) AS total_subs, SUM(likes.post_id) AS total_likes, SUM(comm.id)
    FROM post
        LEFT JOIN user u ON user_id = u.id
        LEFT JOIN content_type ct ON type_id = ct.id
        LEFT JOIN comments comm ON post.id = comm.post_id
        LEFT JOIN likes ON id = likes.post_id
        LEFT JOIN user_message um ON user_id = um.user_id
        LEFT JOIN subscribtions sub ON user_id = sub.user_id
    WHERE post.id = $post_id";
};

$sql_post = call_user_func_array('array_merge', db_get_query($connect, $sql_post_query));

$active_post = include_template('post-' . $sql_post['classname'] . '.php', ['post' => $sql_post]);

$post_details = include_template('post-details.php', ['active_post' => $active_post, 'post' => $sql_post]);

$post_layout = include_template('layout.php', ['content' => $post_details, 'title' => 'readme: публикация', 'is_auth' => $is_auth, 'user_name' => $user_name]);

print($post_layout);
