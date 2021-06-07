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

$popular_content = include_template('main.php', ['posts' => $posts]);

$layout = include_template('layout.php', ['popular_content' => $popular_content, 'title' => 'readme: популярное', 'is_auth' => $is_auth, 'user_name' => $user_name]);

print($layout);
?>
