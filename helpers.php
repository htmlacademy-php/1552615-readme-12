<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } else {
                if (is_string($value)) {
                    $type = 's';
                } else {
                    if (is_double($value)) {
                        $type = 'd';
                    }
                }
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Функция проверяет доступно ли видео по ссылке на youtube
 * @param string $url ссылка на видео
 *
 * @return string Ошибку если валидация не прошла
 */
function check_youtube_url($url)
{
    $id = extract_youtube_id($url);

    set_error_handler(function () {
    }, E_WARNING);
    $headers = get_headers('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' . $id);
    restore_error_handler();

    if (!is_array($headers)) {
        return "Видео по такой ссылке не найдено. Проверьте ссылку на видео";
    }

    $err_flag = strpos($headers[0], '200') ? 200 : 404;

    if ($err_flag !== 200) {
        return "Видео по такой ссылке не найдено. Проверьте ссылку на видео";
    }

    return true;
}

/**
 * Возвращает код iframe для вставки youtube видео на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_video($youtube_url)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = "https://www.youtube.com/embed/" . $id;
        $res = '<iframe width="760" height="400" src="' . $src . '" frameborder="0"></iframe>';
    }

    return $res;
}

/**
 * Возвращает img-тег с обложкой видео для вставки на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_cover($youtube_url)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = sprintf("https://img.youtube.com/vi/%s/mqdefault.jpg", $id);
        $res = '<img alt="youtube cover" width="320" height="120" src="' . $src . '" />';
    }

    return $res;
}

/**
 * Извлекает из ссылки на youtube видео его уникальный ID
 * @param string $youtube_url Ссылка на youtube видео
 * @return array
 */
function extract_youtube_id($youtube_url)
{
    $id = false;

    $parts = parse_url($youtube_url);

    if ($parts) {
        if ($parts['path'] == '/watch') {
            parse_str($parts['query'], $vars);
            $id = $vars['v'] ?? null;
        } else {
            if ($parts['host'] == 'youtu.be') {
                $id = substr($parts['path'], 1);
            }
        }
    }

    return $id;
}

/**
 * @param $index
 * @return false|string
 */
function generate_random_date($index)
{
    $deltas = [['minutes' => 59], ['hours' => 23], ['days' => 6], ['weeks' => 4], ['months' => 11]];
    $dcnt = count($deltas);

    if ($index < 0) {
        $index = 0;
    }

    if ($index >= $dcnt) {
        $index = $dcnt - 1;
    }

    $delta = $deltas[$index];
    $timeval = rand(1, current($delta));
    $timename = key($delta);

    $ts = strtotime("$timeval $timename ago");
    $dt = date('Y-m-d H:i:s', $ts);

    return $dt;
}


// Функции студенческие

/**
 * Функция, которая обрезает текст по заданному максимальному кол-ву символов
 * @param $text - string непосредственно текст, который нужно обрезать
 * @param $symbol_amount - int максимальное количество символов
 */

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

/**
 * Функция, которая позволяет конвертировать дату в необходимый формат, в правильном падеже и т.п.
 * @param $date - непосредственно дата, которую нужно перевести
 * @param $word - слово, которое должно быть в конце сконвертированной даты, например "назад" и т.п.
 */

function get_date_interval_format($date, $word)
{
    $current_date = date_create('now');
    $date_interval = date_diff($date, $current_date);
    $week = floor($date_interval->d / 7);

    if ($date_interval->m >= 1) {
        return $date_interval->m . " " . get_noun_plural_form($date_interval->m, 'месяц', 'месяца', 'месяцев') . " " . $word;
    } elseif ($week >= 1) {
        return $week . " " . get_noun_plural_form($week, 'неделя', 'недели', 'недель') . " " . $word;
    } elseif ($date_interval->d >= 1) {
        return $date_interval->d . " " . get_noun_plural_form($date_interval->d, 'день', 'дня', 'дней') . " " . $word;
    } elseif ($date_interval->h >= 1) {
        return $date_interval->h . " " . get_noun_plural_form($date_interval->h, 'час', 'часа', 'часов') . " " . $word;
    };
    return $date_interval->i . " " . get_noun_plural_form($date_interval->i, 'минута', 'минуты', 'минут') . " " . $word;
}



/**
 * Возвращает двумерный массив с данными из базы данных если value = 'all'
 * и значение если 'assoc'
 * @param $value - требуемое значение на выходе - двумерный массив или ассоциативный массив
 * @param $connect - соединение с БД
 * @param $sql - sql запрос
 * @return array
 */
function db_get_query($value, $connect, $sql)
{
    $result = mysqli_query($connect, $sql);
    if (!$result) {
        die("Ошибка запроса:" . mysqli_error($connect));
    };
    if ($value == 'all') {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } elseif ($value == 'assoc') {
        return mysqli_fetch_assoc($result);
    } else {
        die("Уточните передаваемые данные");
    };
}

/**
 * Функция для получения общего количества
 * чего-либо из sql запроса (лайки, подписчики, публикации)
 * Возвращает либо значение общего количества,
 * либо 0 в случае пустого массива
 * @param $count - строка, поля которые нужно посчитать
 * @param $table - строка, таблица, из которой необходимо
 * вывести значения
 * @param $group_by - строка, поле по которому группируем
 * значения
 * @param $equals - строка, значение, которому равно значение
 * искомого поля
 * @param $sql_connect - созданное sql соединение
 * @return string
 */
function get_total_from_db($count, $table, $group_by, $equals, $sql_connect)
{
    $sql_total_posts_query = "SELECT COUNT($count) AS total
    FROM $table
    WHERE $group_by = $equals
    GROUP BY $group_by";
    $result = db_get_query('assoc', $sql_connect, $sql_total_posts_query);
    return $result['total'] ?? '0';
}

/**
 * Функция для сохранения написанного пользователем в форме
 * @param array $array - array, массив данных полученных ранее из формы
 * @param string $name - string, значение name для input
 * @return string
 *
 */
function getPostVal($array, $name)
{
    return $array[$name] ?? "";
}

/**
 * Функция проверки заполненности формы
 * @param string $name - значение соответствующего поля input
 * @return string - в случае неудачи возвращает строку с сообщением
 */
function validateFilled($name)
{
    if (empty($name)) {
        return 'Это поле должно быть заполнено';
    }
}

/**
 * Функция проверки правильности ссылки url
 * @param string $name - значение соответствующего поля input
 * @return string - в случае неудачи возвращает строку с сообщением
 */
function validateUrl($name)
{
    if (!filter_var($name, FILTER_VALIDATE_URL)) {
        return 'Ссылка должна быть корректной';
    }
}

/**
 * Функция валидации загружаемого пользователем файла
 * @param string $name - значение соответствующего поля input
 * @return string - в случае неудачи возвращает строку с сообщением
 */
function validateFile($name)
{
    if (!empty($name['name'])) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $tmp_name = $name['tmp_name'];
        $file_type = finfo_file($finfo, $tmp_name);
        $types = [
            'image/jpeg',
            'image/png',
            'image/gif'
        ];
        $res = in_array($file_type, $types);
        if (!$res) {
            return 'Загрузите картинку в формате JPEG, PNG или GIF';
        }
    }
}

/**
 * Функция загрузки файла пользователем
 * @param $name - значение соответствующего поля input
 * @param $dirName - название папки, в которую грузится файл
 */
function uploadFile($name, $dirName)
{
    if (!empty($name['name'])) {
        $_POST['photo-url'] = '';
        $file_name = $name['name'];
        $file_path = __DIR__ . '/' . $dirName . '/';
        $res = move_uploaded_file($name['tmp_name'], $file_path . $file_name);
        if (!$res) {
            return 'Не удалось загрузить файл';
        }
    }
}

/**
 * Функция загрузки файла по указанной ссылке
 * @param string $name - значение соответствующего поля input
 * @return string - в случае неудачи возвращает строку с сообщением
 */
function downloadFileFromUrl($name)
{
    if (isset($name)) {
        $file = file_get_contents($name);
        if ($file === false) {
            return 'Не удалось загрузить файл';
        }
        $file_name = pathinfo($name, PATHINFO_BASENAME);
        $file_path = __DIR__ . '/uploads/' . $file_name;
        $res = file_put_contents($file_path, $file);
        if (!$res) {
            return 'Не удалось загрузить файл';
        }
    }
}

/**
 * Функция валидации полей загрузки фото
 * @return string - в случае неудачи возвращает строку с сообщением
 */
function validateFilledPhoto()
{
    if (empty($_POST['photo-url']) && empty($_FILES['userpic-file-photo']['name'])) {
        return 'Необходимо загрузить изображение или ввести ссылку';
    }
}

/**
 * Функция валидации хэштегов
 * @param string $name - значение соответствующего поля input
 * @return string - в случае неудачи возвращает строку с сообщением
 */
function validateTags($name)
{
    if (empty($name)) {
        return 'Должен быть хотя бы один тег';
    }
}

/**
 * Функция перевода названия полей
 * @param string $name - значение соответствующего поля input
 * @return string - возвращает переведенное значение
 */
function translateInputName($name)
{
    $translatedName = '';
    if ($name === 'heading') {
        $translatedName = 'Заголовок';
    } elseif ($name === 'cite-text') {
        $translatedName = 'Текст цитаты';
    } elseif ($name === 'quote-author') {
        $translatedName = 'Автор';
    } elseif ($name === 'video-url') {
        $translatedName = 'Ссылка Youtube';
    } elseif ($name === 'post-text') {
        $translatedName = 'Текст поста';
    } elseif ($name === 'post-link') {
        $translatedName = 'Ссылка';
    } elseif ($name === 'photo-url') {
        $translatedName = 'Ссылка из интернета';
    } elseif ($name === 'userpic-file-photo') {
        $translatedName = 'Картинка пользователя';
    } elseif ($name === 'tags') {
        $translatedName = 'Теги';
    } elseif ($name === 'login') {
        $translatedName = 'Логин';
    } elseif ($name === 'email') {
        $translatedName = 'Электронная почта';
    } elseif ($name === 'password') {
        $translatedName = 'Пароль';
    } elseif ($name === 'password-repeat') {
        $translatedName = 'Повтор пароля';
    } elseif ($name === 'userpic-file') {
        $translatedName = 'Аватар';
    }
    return $translatedName;
}

/**
 * Функция валидации email
 * @param string $name - значение соответствующего поля input
 * @return string - в случае неудачи возвращает строку с сообщением
 */
function validateEmail($name)
{
    if (!filter_var($name, FILTER_VALIDATE_EMAIL)) {
        return 'E-mail должен быть корректным';
    }
}

/**
 * Функция установления соединения с базой данных
 * @return $connect - возвращает соединение с БД
 */
function db_set_connection()
{
    $connect = mysqli_connect("localhost", "root", "root", "readme");
    if ($connect == false) {
        die('Connection error: ' . mysqli_connect_error());
    }
    mysqli_set_charset($connect, "utf8");
    return $connect;
}

/**
 * Функция получения массива подписчиков авторизованного пользователя
 * @param string $user_id - id текущего авторизованного пользователя
 * @param $connect - соединение с БД
 * @return array $user_subs - массив с подписчиками определенного пользователя
 */
function get_subscribers($user_id, $connect)
{
    $user_subs = [];
    $sql_user_subs_query = "SELECT * FROM subscribtions
    WHERE user_id = '$user_id'";
    $sql_user_subs = db_get_query('all', $connect, $sql_user_subs_query);
    if ($sql_user_subs) {
        foreach ($sql_user_subs as $user_sub) {
            array_push($user_subs, $user_sub['to_user_id']);
        }
        return $user_subs;
    }
}

/**
 * Функция проверки длины сообщения/комментария
 * @param string $text - текст или комментарий, который необходимо проверить на соответствующую длину
 * @param int $min_length - минимальное количество символов
 * @return string - предупреждение о том, что нужно больше символов
 */
function validateLength($text, $min_length)
{
    if (!empty($text)) {
        if (mb_strlen($text) < $min_length) {
            return 'Должно быть больше ' . $min_length . ' ' . get_noun_plural_form($min_length, 'символ', 'символа', 'символов');
        }
    }
}

/**
 * Функция генерации http запроса
 * @param $key - параметр запроса
 * @param $value - значение параметра запроса
 * @param $exclude - параметр, который необходимо обнулить
 * @return string - строка запроса
 */
function generate_http_query($key, $value, $exclude = null)
{
    $params = $_GET;
    if (!is_null($exclude)) {
        unset($params[$exclude]);
    }
    $params[$key] = $value;
    return http_build_query($params);
}

/**
 * Функция для отображения комментариев
 * @param $post_id - id искомого поста
 * @param $connect - подключение к бд
 * @return array - $comments список комментариев
 */
function show_comments($post_id, $connect)
{
    $comments = [];
    $sql_comment_query = "SELECT comments.*, user.user_login AS comment_author, user.avatar AS comment_author_avatar
                            FROM comments
                                LEFT JOIN user ON comments.user_id = user.id
                            WHERE comments.post_id IN ($post_id)
                                ORDER BY published_at";
    $comments = db_get_query('all', $connect, $sql_comment_query);
    return $comments;
}
