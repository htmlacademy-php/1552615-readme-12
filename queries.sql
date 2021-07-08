-- добавляем записи в сущность content_type
INSERT INTO content_type (title, classname)
    VALUES ('Текст', 'text'), ('Картинка', 'photo'), ('Видео', 'video'), ('Ссылка', 'link'), ('Цитата', 'quote');

-- добавляем записи в сущность user
INSERT INTO user (email, user_login, user_password, avatar)
    VALUES ('pikachu@gmail.com', 'pikachu', 'pikapika777', 'userpic-mark.jpg.jpg'), ('petr_petrovich@gmail.com', 'petr_petrovich', 'vodka_4ever', 'userpic.jpg'), ('valiko@yandex.ru', 'valiko', 'mimino555', 'userpic-larisa-small.jpg');

-- добавляем записи в сущность post, отдельный запрос для каждого типа контента
INSERT INTO post (title, text_content, quote_author, watch_count, user_id, type_id)
    VALUES ('Цитата', 'Мы в жизни любим только раз, а после ищем лишь похожих', 'Лариса', 20, 1, 5);
INSERT INTO post (title, text_content, watch_count, user_id, type_id)
    VALUES ('Игра престолов', 'Не могу дождаться начала финального сезона своего любимого сериала!', 12, 3, 1);
INSERT INTO post (title, picture, watch_count, user_id, type_id)
    VALUES ('Наконец, обработал фотки!', 'rock-medium.jpg', 22, 2, 2);
INSERT INTO post (title, picture, watch_count, user_id, type_id)
    VALUES ('Моя мечта', 'coast-medium.jpg', 101, 2, 2);
INSERT INTO post (title, link, watch_count, user_id, type_id)
    VALUES ('Лучшие курсы', 'www.htmlacademy.ru', 45, 1, 4);

-- добавляем записи в сущность comments
INSERT
INTO comments (comment, user_id, post_id)
    VALUES
    ('Все супер!', 1, 1),
    ('Ну такое...', 3, 1),
    ('Очень даже неплохо!', 2, 2),
    ('Все тлен...', 1, 2),
    ('С точки зрения банальной эрудиции...', 3, 3),
    ('Всегда когда вижу ЭТО, всегда находят странные мысли...', 2, 3),
    ('Вчера казалось лучше', 3, 4),
    ('Прелестно, просто прелестно', 1, 4),
    ('Успехов тебе!', 2, 5),
    ('Отличненько!', 3, 5);

-- делаем запрос на получиение списка постов с сортировкой по популярности и вместе с именами авторов и типом контента
SELECT post.*, u.user_login, ct.classname
FROM post
    LEFT JOIN user u ON user_id = u.id
    LEFT JOIN content_type ct ON type_id = ct.id
ORDER BY watch_count DESC;

-- делаем запрос на получение списка постов для конкретного пользователя
SELECT post.*, watch_count, u.user_login
FROM post
  LEFT JOIN user u ON user_id = u.id
WHERE u.user_login = 'pikachu';

-- делаем запрос на получение списка комментариев для одного поста, в комментариях указан логин пользователя
SELECT c.id, comment, u.user_login
FROM comments c
  LEFT JOIN user u ON user_id = u.id
WHERE post_id = 1;

-- добавляем лайк к посту
INSERT INTO likes
  VALUES (NULL, 2, 1);

-- подписываемся на пользователя
INSERT INTO subscribtions
  VALUES (NULL, 2, 3);
