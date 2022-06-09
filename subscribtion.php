<?php
require_once('helpers.php');
require_once('auth.php');
require_once('mail.php');

require_once('vendor/autoload.php');

$connect = db_set_connection();
$profile_user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT) ?? null;
$referer = $_SERVER['HTTP_REFERER'];
$path = (pathinfo(__FILE__, PATHINFO_BASENAME));
$url = "/" . $path;
$user_link = 'http://' . $_SERVER['HTTP_HOST'] . '/profile.php?user_id=' . $user_id;

if ($profile_user_id) {
    $sql_user_check = "SELECT id FROM user WHERE id = '$profile_user_id'";
    $user_check = db_get_query('assoc', $connect, $sql_user_check) ?? null;
    $sql_subs_check = "SELECT * FROM subscribtions WHERE user_id = '$user_id' AND to_user_id = '$profile_user_id'";
    $subs_check = db_get_query('assoc', $connect, $sql_subs_check) ?? null;
    if ($user_check && !$subs_check) {
        $add_subscriber = "INSERT INTO subscribtions (user_id, to_user_id) VALUES ('$user_id', '$profile_user_id')";
        $stmt = db_get_prepare_stmt($connect, $add_subscriber);
        $result = mysqli_stmt_execute($stmt);
        if ($result) {
            $sql_users_login = "SELECT user_login FROM user
                          WHERE id IN ($user_id, $profile_user_id)";
            $logins = db_get_query('all', $connect, $sql_users_login);
            $subscription_user = $logins[0]['user_login'];
            $subscriber = $logins[1]['user_login'];
            // Формирование сообщения уведомления о новом подписчике
            $message->to("keks@phpdemo.ru");
            $message->from("kilnur1988@rambler.ru");
            $message->subject("У вас новый подписчик");
            $message->text("Здравствуйте, " . $subscription_user . ". На вас подписался новый пользователь " . $subscriber . ". Вот ссылка на его профиль: " . $user_link);
            // Отправка сообщения
            $mailer->send($message);
        }
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




