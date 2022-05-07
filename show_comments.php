<?php
require_once('helpers.php');
require_once('auth.php');


$connect = db_set_connection();
$comments = [];
$sql_comment_query = "SELECT comments.*, user.user_login AS comment_author, user.avatar AS comment_author_avatar
                        FROM comments
                            LEFT JOIN user ON comments.user_id = user.id
                            ORDER BY published_at DESC";
$comments = db_get_query('all', $connect, $sql_comment_query);
