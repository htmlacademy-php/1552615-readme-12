<?php
require_once('helpers.php');

$is_auth = rand(0, 1);
$user_name = 'Ильнур'; // укажите здесь ваше имя
$posts = [
    [
        'title' => 'Цитата',
        'type' => 'post-quote',
        'content' => 'Мы в жизни любим только раз, а после ищем лишь похожих',
        'author_name' => 'Лариса',
        'avatar' => 'userpic-larisa-small.jpg',
        'published_at' => date_create(generate_random_date(0)),
    ],
    [
        'title' => 'Игра престолов',
        'type' => 'post-text',
        'content' => 'Не могу дождаться начала финального сезона своего любимого сериала!',
        'author_name' => 'Владик',
        'avatar' => 'userpic.jpg',
        'published_at' => date_create(generate_random_date(1)),
    ],
    [
        'title' => 'Наконец, обработал фотки!',
        'type' => 'post-photo',
        'content' => 'rock-medium.jpg',
        'author_name' => 'Виктор',
        'avatar' => 'userpic-mark.jpg',
        'published_at' => date_create(generate_random_date(2)),
    ],
    [
        'title' => 'Моя мечта',
        'type' => 'post-photo',
        'content' => 'coast-medium.jpg',
        'author_name' => 'Лариса',
        'avatar' => 'userpic-larisa-small.jpg',
        'published_at' => date_create(generate_random_date(3)),
    ],
    [
        'title' => 'Лучшие курсы',
        'type' => 'post-link',
        'content' => 'www.htmlacademy.ru',
        'author_name' => 'Владик',
        'avatar' => 'userpic.jpg',
        'published_at' => date_create(generate_random_date(4)),
    ],
];

function get_cut_text($text, $symbol_amount = 300)
{
    $link = '<a class="post-text__more-link" href="#">Читать далее</a>';
    if (mb_strlen($text) < $symbol_amount) {
        return "<p>$text</p>";
    } else {
        $words = explode(' ', $text);
        $result = 0;
        $new_words = [];
        foreach ($words as $word) {
            $result += (mb_strlen($word) + 1);
            array_push($new_words, $word);
            if (($result - 1) >= $symbol_amount) {
                break;
            };
        };
        array_pop($new_words);
        $trimmed_string = implode(' ', $new_words);
        return "<p>$trimmed_string...</p> $link";
    };
}

function get_date_interval_format($date) {
    $current_date = date_create('now');
    $date_interval = date_diff($date, $current_date);
    $week = floor($date_interval->d / 7);

    if ($date_interval->m >= 1) {
        return $date_interval->m . " " . get_noun_plural_form($date_interval->m, 'месяц', 'месяца', 'месяцев') . " назад";
    } elseif ($week >= 1) {
        return $week . " " . get_noun_plural_form($week, 'неделя', 'недели', 'недель') . " назад";
    } elseif ($date_interval->d >= 1) {
        return $date_interval->d . " " . get_noun_plural_form($date_interval->d, 'день', 'дня', 'дней') . " назад";
    } elseif ($date_interval->h >= 1 ) {
        return $date_interval->h . " " . get_noun_plural_form($date_interval->h, 'час', 'часа', 'часов') . " назад";
    };
    return $date_interval->i . " " . get_noun_plural_form($date_interval->i, 'минута', 'минуты', 'минут') . " назад";
}

$connect = mysqli_connect("localhost", "root", "root", "readme");
if ($connect == false) {
    die("Connection error: " . mysqli_connect_error());
};
mysqli_set_charset($connect, "utf8");

$scriptname = pathinfo(__FILE__, PATHINFO_BASENAME);
$url = "/" . $scriptname . "?";

if (isset($_GET['id'])) {
    $content_type_id = $_GET['id'];
    $sql_posts_query = "SELECT post.*, u.user_login, u.avatar, ct.classname
    FROM post LEFT JOIN user u ON user_id = u.id LEFT JOIN content_type ct ON type_id = ct.id WHERE type_id = $content_type_id ORDER BY watch_count LIMIT 6";
    $active_btn = 'filters__button--active';
    $active_btn_all = '';
} else {
    $content_type_id = '';
    $sql_posts_query = "SELECT post.*, u.user_login, u.avatar, ct.classname
    FROM post LEFT JOIN user u ON user_id = u.id LEFT JOIN content_type ct ON type_id = ct.id ORDER BY watch_count DESC LIMIT 6";
    $active_btn = '';
    $active_btn_all = 'filters__button--active';
};

$sql_types_query = "SELECT * FROM content_type";

$sql_types = db_get_query($connect, $sql_types_query);
$sql_posts = db_get_query($connect, $sql_posts_query);

$popular_content = include_template('main.php', ['posts' => $sql_posts, 'types' => $sql_types, 'url' => $url, 'active_btn' => $active_btn, 'active_btn_all' => $active_btn_all, 'content_type_id' => $content_type_id]);

$layout = include_template('layout.php', ['content' => $popular_content, 'title' => 'readme: популярное', 'is_auth' => $is_auth, 'user_name' => $user_name]);

print($layout);
