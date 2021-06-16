<?php
require('helpers.php');

$is_auth = rand(0, 1);
$user_name = 'Ильнур'; // укажите здесь ваше имя
$posts = [
    [
        'title' => 'Цитата',
        'type' => 'post-quote',
        'content' => 'Мы в жизни любим только раз, а после ищем лишь похожих',
        'author_name' => 'Лариса',
        'avatar' => 'userpic-larisa-small.jpg',
    ],
    [
        'title' => 'Игра престолов',
        'type' => 'post-text',
        'content' => 'Не могу дождаться начала финального сезона своего любимого сериала!',
        'author_name' => 'Владик',
        'avatar' => 'userpic.jpg',
    ],
    [
        'title' => 'Наконец, обработал фотки!',
        'type' => 'post-photo',
        'content' => 'rock-medium.jpg',
        'author_name' => 'Виктор',
        'avatar' => 'userpic-mark.jpg',
    ],
    [
        'title' => 'Моя мечта',
        'type' => 'post-photo',
        'content' => 'coast-medium.jpg',
        'author_name' => 'Лариса',
        'avatar' => 'userpic-larisa-small.jpg',
    ],
    [
        'title' => 'Лучшие курсы',
        'type' => 'post-link',
        'content' => 'www.htmlacademy.ru',
        'author_name' => 'Владик',
        'avatar' => 'userpic.jpg',
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

function get_random_date($key) {
    return date_create(generate_random_date($key));
}

function get_date_interval($key) {
    $current_date = date_create('now');
    $random_date = get_random_date($key);
    return $date_interval = date_diff($random_date, $current_date);
}

function get_date_interval_format($key) {
    $date_interval = get_date_interval($key);
    $week = floor($date_interval->d / 7);

    if ($week > 5 || $date_interval->m >= 1) {
        return $date_interval->m . " " . get_noun_plural_form($date_interval->m, 'месяц', 'месяца', 'месяцев') . " назад";
    } elseif ($week >= 1 && $week <= 5) {
        return $week . " " . get_noun_plural_form($week, 'неделя', 'недели', 'недель') . " назад";
    } elseif ($date_interval->h === 24 || $date_interval->d >= 1 && $date_interval->d < 7) {
        return $date_interval->d . " " . get_noun_plural_form($date_interval->d, 'день', 'дня', 'дней') . " назад";
    } elseif ($date_interval->h >= 1 ) {
        return $date_interval->h . " " . get_noun_plural_form($date_interval->h, 'час', 'часа', 'часов') . " назад";
    } elseif ($date_interval->i >= 1 || $date_interval->i < 60) {
        return $date_interval->i . " " . get_noun_plural_form($date_interval->i, 'минута', 'минуты', 'минут') . " назад";
    };
}

$popular_content = include_template('main.php', ['posts' => $posts]);

$layout = include_template('layout.php', ['popular_content' => $popular_content, 'title' => 'readme: популярное', 'is_auth' => $is_auth, 'user_name' => $user_name]);

print($layout);
