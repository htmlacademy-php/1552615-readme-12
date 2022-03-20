<?php

session_start();
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $user_name = $user['user_login'];
    $user_avatar = $user['avatar'];
    $is_auth = 1;
} else {
    $is_auth = 0;
    header("Location: /index.php");
    exit();
}
