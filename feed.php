<?php
session_start();
$user = $_SESSION['user'] ?? null;
$is_auth = 1;

require_once('helpers.php');


$connect = db_set_connection();
$user_name = $user['user_login'];
$sql_types_query = "SELECT * FROM content_type";
$sql_types = db_get_query('all', $connect, $sql_types_query);

$feed = include_template('feed-layout.php', ['types' => $sql_types]);
$feed_layout = include_template('layout.php', ['content' => $feed, 'title' => 'readme: моя лента', 'is_auth' => $is_auth, 'user_name' => $user_name]);

print($feed_layout);
