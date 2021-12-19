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

    set_error_handler(function () {}, E_WARNING);
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

/**
 * Возвращает двумерный массив с данными из базы данных если value = 'all'
 * и значение если 'assoc'
 * @param $value - требуемое значение на выходе - двумерный массив или ассоциативный массив
 * @param $connect - соединение с БД
 * @param $sql - sql запрос
 * @return array
 */
function db_get_query($value, $connect, $sql) {
    $result = mysqli_query($connect, $sql);
    if (!$result) {
        die("Ошибка запроса:" . mysqli_error($connect));
    };
    if ($value == 'all') {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } elseif ($value == 'assoc') {
        return mysqli_fetch_assoc($result);
    } else {
        die ("Уточните передаваемые данные");
    };
};

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
function get_total_from_db ($count, $table, $group_by, $equals, $sql_connect) {
    $sql_total_posts_query = "SELECT COUNT($count) AS total
    FROM $table
    WHERE $group_by = $equals
    GROUP BY $group_by";
    $result = db_get_query('assoc', $sql_connect, $sql_total_posts_query);
    return $result['total'] ?? '0';
}

/**
 * Функция для сохранения написанного пользователем в форме
 * @param $name - string, атрибут name для input
 *
 */
function getPostVal($name) {
    return $_POST[$name] ?? "";
}

/**
 * Функция проверки заполненности формы
 * @param $name - атрибут name для input
 */
function validateFilled($name) {
    if (empty($_POST[$name])) {
        return 'Это поле должно быть заполнено';
    };
}

/**
 * Функция проверки правильности ссылки url
 * @param $name - значение соответствующего поля input
 */
function validateUrl($name) {
    if (!filter_input(INPUT_POST, $name, FILTER_VALIDATE_URL)) {
        return 'Ссылка должна быть корректной';
    };
}

/**
 * Функция валидации загружаемого пользователем файла
 * @param $name - атрибут name для input
 */
function validateFile($name) {
    if (isset($_FILES[$name])) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_name = $_FILES[$name]['tmp_name'];
        $file_type = finfo_file($finfo, $file_name);

        if ($file_type !== 'image/jpeg' || $file_type !== 'image/png' || $file_type !== 'image/gif') {
            return 'Загрузите картинку в формате JPEG, PNG или GIF';
        };
    };
}

/**
 * Функция загрузки файла пользователем
 * @param $name - атрибут name для input
 */
function uploadFile($name) {
    if (!empty($_FILES[$name])) {
        $_POST['photo-url'] = '';
        $file_name = $_FILES[$name]['name'];
        $file_path = __DIR__ . '/uploads/';
        move_uploaded_file($_FILES[$name]['tmp_name'], $file_path . $file_name);
    };
}

/**
 * Функция загрузки файла по указанной ссылке
 * @param $name - атрибут name для input
 */
function downloadFileFromUrl($name) {
    if (isset($_POST[$name])) {
        $file = file_get_contents($_POST[$name]);
        if ($file == false) {
            return 'Не удалось загрузить файл';
        };
        $file_path = __DIR__ . '/uploads/';
        file_put_contents($file_path, $file);
    };
}

/**
 * Функция валидации полей загрузки фото
 */
function validateFilledPhoto () {
    if (empty($_POST['photo-url']) && empty($_FILES['userpic-file-photo'])) {
        return 'Необходимо загрузить изображение или ввести ссылку';
    };
}

/**
 * Функция валидации хэштегов
 */
function validateTags ($name) {
    if(!empty($_POST[$name])) {
        $tags = explode(' ', htmlspecialchars($_POST[$name]));
        if (count($tags)) {
            return 'Должен быть хотя бы один тег';
        };
    };
}

/**
 * Функция перевода названия полей
 */
function translateInputName ($name) {
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
    }
    return $translatedName;
}

