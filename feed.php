<?php

require_once('auth.php');
require_once('helpers.php');

$path = (pathinfo(__FILE__, PATHINFO_BASENAME));

$connect = db_set_connection();
$user_name = $user['user_login'];
$sql_types_query = "SELECT * FROM content_type";
$sql_types = db_get_query('all', $connect, $sql_types_query);

$feed = include_template('feed-layout.php', ['types' => $sql_types]);
$feed_layout = include_template('layout.php', ['content' => $feed, 'title' => 'readme: моя лента', 'is_auth' => $is_auth, 'user_name' => $user_name, 'avatar' => $user_avatar, 'path' => $path]);

print($feed_layout);
