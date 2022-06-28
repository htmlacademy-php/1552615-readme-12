<?php

require_once('helpers.php');
require_once('auth.php');
require_once('mail.php');

$connect = db_set_connection();
$profile_user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT) ?? null;
$referer = $_SERVER['HTTP_REFERER'];
$path = (pathinfo(__FILE__, PATHINFO_BASENAME));
$url = "/" . $path;


if ($profile_user_id) {
    $sql_user_check = "SELECT id FROM users WHERE id = '$profile_user_id'";
    $user_check = db_get_query('assoc', $connect, $sql_user_check) ?? null;
    $sql_subs_check = "SELECT * FROM subscriptions WHERE user_id = '$user_id' AND to_user_id = '$profile_user_id'";
    $subs_check = db_get_query('assoc', $connect, $sql_subs_check) ?? null;
    if ($user_check && !$subs_check) {
        $add_subscriber = "INSERT INTO subscriptions (user_id, to_user_id) VALUES (?, ?)";
        $stmt = db_get_prepare_stmt($connect, $add_subscriber, [$user_id, $profile_user_id]);
        $result = mysqli_stmt_execute($stmt);
        if ($result) {
            send_notice_about_new_sub($user_id, $user_name, $profile_user_id, $connect, $sender, $mailer);
        }
    }
    if ($subs_check) {
        $delete_subscriber = "DELETE FROM subscriptions WHERE user_id = ? AND to_user_id = ?";
        $stmt = db_get_prepare_stmt($connect, $delete_subscriber, [$user_id, $profile_user_id]);
        $result = mysqli_stmt_execute($stmt);
    }
    if (!$referer) {
        header("Location: /profile.php?user_id=$profile_user_id&tab=posts");
        exit;
    }
    header("Location: $referer");
    exit;
}
