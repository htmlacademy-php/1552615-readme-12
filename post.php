<?php
require('helpers.php');
require('index.php');

$scriptname = pathinfo(__FILE__, PATHINFO_BASENAME);
$post_url = "/" . $scriptname . "?";

$post_details = include_template('post-details.php', ['posts' => $sql_posts, 'types' => $sql_types]);

$layout = include_template('layout.php', ['popular_content' => $post_details, 'title' => 'readme: публикация', 'is_auth' => $is_auth, 'user_name' => $user_name]);
