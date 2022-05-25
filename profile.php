<?php

require_once('auth.php');
require_once('helpers.php');
require_once('hashtags.php');
require_once('add_comment.php');

$path = (pathinfo(__FILE__, PATHINFO_BASENAME));
$url = "/" . $path;

$connect = db_set_connection();
$tab = filter_input(INPUT_GET, 'tab') ?? 'posts';
$profile_user_id = filter_input(INPUT_GET, 'user_id') ?? null;
$active_tab_layout = '';
$tab_data = [];

if (isset($tab)) {
    switch ($tab) {
        case 'posts':
            $active_tab_layout = 'profile-' . $tab;
            $sql_posts_query = "SELECT post.*, ct.classname, u.user_login AS author_login, u.avatar AS author_avatar,
                                (SELECT COUNT(id) FROM comments WHERE comments.post_id = post.id) AS total_comm,
                                (SELECT COUNT(id) FROM likes WHERE likes.post_id = post.id) AS total_likes
                                FROM post
                                    LEFT JOIN content_type ct ON type_id = ct.id
                                    LEFT JOIN user u ON original_author_id = u.id
                                    WHERE post.user_id = '$profile_user_id'";
            $tab_data = db_get_query('all', $connect, $sql_posts_query);

            break;

        case 'likes':
            $active_tab_layout = 'profile-' . $tab;
            $sql_likes_query = "SELECT l.user_id, l.post_id, l.liked_at, u.avatar, u.user_login, p.picture, p.user_id AS post_user_id, ct.classname
            FROM likes l
                LEFT JOIN user u ON l.user_id = u.id
                LEFT JOIN post p ON post_id = p.id
                LEFT JOIN content_type ct ON type_id = ct.id
                WHERE p.user_id = '$profile_user_id'";
            $tab_data = db_get_query('all', $connect, $sql_likes_query);

            break;

        case 'subscribtions':
            $active_tab_layout = 'profile-' . $tab;
            $sql_subscribtion_query = "SELECT user_id FROM subscribtions WHERE to_user_id = '$profile_user_id'";
            $sql_subs = db_get_query('all', $connect, $sql_subscribtion_query);
            foreach ($sql_subs as $sub) {
                $sub_id = $sub['user_id'];
                $sql_subscribtion_data_query = "SELECT u.id, u.created_at, u.user_login, u.avatar,
                                                (SELECT COUNT(id) FROM post WHERE user_id = '$sub_id') AS total_posts,
                                                (SELECT COUNT(user_id) FROM subscribtions WHERE to_user_id = '$sub_id') AS total_subs
                                                FROM user u
                                                    WHERE u.id = '$sub_id'";
                $sub_data = db_get_query('assoc', $connect, $sql_subscribtion_data_query);
                array_push($tab_data, $sub_data);
            }
            break;
    }
}
$user_data = [];
$user_subs = [];
$posts = [];
if (isset($profile_user_id)) {
    $sql_user_query = "SELECT created_at, user_login, avatar,
                       (SELECT COUNT(id) FROM post WHERE user_id = '$profile_user_id') AS total_posts,
                       (SELECT COUNT(user_id) FROM subscribtions WHERE to_user_id = '$profile_user_id') AS total_subs
                       FROM user
                            WHERE id = '$profile_user_id'";

    $user_data = db_get_query('assoc', $connect, $sql_user_query);
    if ($user_data) {
        $user_subs = get_subscribers($user_id, $connect);
    } else {
        http_response_code(404);
        die('Такого пользователя не существует!');
    }
} else {
    http_response_code(404);
    die('Такой страницы не существует!');
}

$active_tab = include_template('/profile-tabs/' . $active_tab_layout . '.php', ['tab_data' => $tab_data, 'profile_user_id' => $profile_user_id, 'hashtags' => $hashtags, 'errors' => $errors, 'user_id' => $user_id, 'user_subs' => $user_subs, 'connect' => $connect]);

$profile_layout = include_template('profile-layout.php', ['active_tab' => $active_tab, 'hashtags' => $hashtags, 'url' => $url, 'path' => $path, 'tab' => $tab, 'profile_user_id' => $profile_user_id, 'user_data' => $user_data, 'user_id' => $user_id, 'user_subs' => $user_subs]);

$layout = include_template('layout.php', ['content' => $profile_layout, 'title' => 'readme: профиль', 'is_auth' => $is_auth, 'user_name' => $user_name, 'avatar' => $user_avatar, 'path' => $path, 'user_id' => $user_id]);

print($layout);
