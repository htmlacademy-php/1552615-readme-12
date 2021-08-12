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
    $sql_posts_query = "SELECT post.*, u.user_login, u.avatar, ct.classname
    FROM post LEFT JOIN user u ON user_id = u.id
    LEFT JOIN content_type ct ON type_id = ct.id
    WHERE $post_id = post.id
    ORDER BY watch_count DESC LIMIT 6";
};

$sql_posts = db_get_query($connect, $sql_posts_query);

$active_post = '';
if($sql_posts['classname'] == 'quote') {
    $active_post = include_template('post-quote.php');
} elseif ($sql_posts['classname'] == 'text') {
    $active_post = include_template('post-text.php');
} elseif ($sql_posts['classname'] == 'link') {
    $active_post = include_template('post-link.php');
} elseif ($sql_posts['classname'] == 'video') {
    $active_post = include_template('post-video.php');
};

$post_details = include_template('post-details.php', ['active_post' => $active_post]);

$post_layout = include_template('layout.php', ['content' => $post_details, 'title' => 'readme: публикация', 'is_auth' => $is_auth, 'user_name' => $user_name]);

print($post_layout);
