<?php
require_once('helpers.php');
require_once('auth.php');

$connect = db_set_connection();
$profile_user_id = filter_input(INPUT_GET, 'user_id') ?? null;
$referer = $_SERVER['HTTP_REFERER'];

if ($profile_user_id) {
    $sql_user_check = "SELECT id FROM user WHERE id = '$profile_user_id'";
    $user_check = db_get_query('assoc', $connect, $sql_user_check) ?? null;
    $sql_subs_check = "SELECT * FROM subscribtions WHERE user_id = '$user_id' AND to_user_id = '$profile_user_id'";
    $subs_check = db_get_query('assoc', $connect, $sql_subs_check) ?? null;
    if ($user_check && !$subs_check) {
        $add_subscriber = "INSERT INTO subscribtions (user_id, to_user_id) VALUES ('$user_id', '$profile_user_id')";
        $stmt = db_get_prepare_stmt($connect, $add_subscriber);
        $result = mysqli_stmt_execute($stmt);
    }
    if ($subs_check) {
        $delete_subscriber = "DELETE FROM subscribtions WHERE user_id = '$user_id' AND to_user_id = '$profile_user_id'";
        $stmt = db_get_prepare_stmt($connect, $delete_subscriber);
        $result = mysqli_stmt_execute($stmt);
    }
    if (!$referer) {
        header("Location: /profile.php?user_id=$profile_user_id&tab=posts");
        exit;
    }
    header("Location: $referer");
    exit;
}




