<?php
require_once('helpers.php');

$connect = mysqli_connect("localhost", "root", "root", "readme");
if ($connect == false) {
    die("Connection error: " . mysqli_connect_error());
};
mysqli_set_charset($connect, "utf8");

$scriptname = pathinfo(__FILE__, PATHINFO_BASENAME);
$post_url = '/' . $scriptname;
$sql_post_max_id_query = "SELECT MAX(id) AS max_id FROM post";
$sql_post_max_id = call_user_func_array('array_merge', db_get_query($connect, $sql_post_max_id_query));

if (isset($_GET['post_id'])) {
    $post_id = intval($_GET['post_id']);
};

if ($post_id && $post_id <= $sql_post_max_id['max_id']) {
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


function get_total_from_db ($count, $table, $group_by, $equals, $sql_connect) {
    $sql_total_posts_query = "SELECT COUNT($count) AS total
    FROM $table
    WHERE $group_by = $equals
    GROUP BY $group_by";
    return call_user_func_array('array_merge', db_get_query($sql_connect, $sql_total_posts_query));
}

$current_user = $sql_post['user_id'];

$sql_total_posts = get_total_from_db ('id', 'post', 'user_id', $current_user, $connect);
$sql_total_likes = get_total_from_db ('id', 'likes', 'post_id', $post_id, $connect);
$sql_total_subs = get_total_from_db ('user_id', 'subscribtions', 'to_user_id', $current_user, $connect);

$active_post = include_template('post-' . $sql_post['classname'] . '.php', ['post' => $sql_post]);

$post_details = include_template('post-details.php', ['active_post' => $active_post, 'post' => $sql_post, 'totalpost' => $sql_total_posts, 'likes' => $sql_total_likes, 'subs' => $sql_total_subs]);

$post_layout = include_template('layout.php', ['content' => $post_details, 'title' => 'readme: публикация', 'is_auth' => $is_auth, 'user_name' => $user_name]);

print($post_layout);
