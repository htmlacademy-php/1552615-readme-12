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
        mysqli_query($connect, "START TRANSACTION");
        $add_subscriber = mysqli_query($connect, "INSERT INTO subscribtions (user_id, to_user_id) VALUES ('$user_id', '$profile_user_id')");
        if ($add_subscriber) {
            mysqli_query($connect, "COMMIT");
        }
        else {
            mysqli_query($connect, "ROLLBACK");
        }
    }
    if ($subs_check) {
        mysqli_query($connect, "START TRANSACTION");
        $delete_subscriber = mysqli_query($connect, "DELETE FROM subscribtions WHERE user_id = '$user_id' AND to_user_id = '$profile_user_id'");
        if ($delete_subscriber) {
            mysqli_query($connect, "COMMIT");
        }
        else {
            mysqli_query($connect, "ROLLBACK");
        }
    }
    header("Location: $referer");
    exit;
}




