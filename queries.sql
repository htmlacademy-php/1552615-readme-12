
INSERT INTO content_type (title, classname)
    VALUES ('Текст', 'text'), ('Картинка', 'photo'), ('Видео', 'video'), ('Ссылка', 'link'), ('Цитата', 'quote');


INSERT INTO user (email, user_login, user_password, avatar)
    VALUES ('pikachu@gmail.com', 'pikachu', 'pikapika777', 'userpic-mark.jpg'), ('petr_petrovich@gmail.com', 'petr_petrovich', 'vodka_4ever', 'userpic.jpg'), ('valiko@yandex.ru', 'valiko', 'mimino555', 'userpic-larisa-small.jpg');


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


SELECT post.*, u.user_login, ct.classname
FROM post
    LEFT JOIN user u ON user_id = u.id
    LEFT JOIN content_type ct ON type_id = ct.id
ORDER BY watch_count DESC;


SELECT post.*, watch_count, u.user_login
FROM post
  LEFT JOIN user u ON user_id = u.id
WHERE u.user_login = 'pikachu';


SELECT c.id, comment, u.user_login
FROM comments c
  LEFT JOIN user u ON user_id = u.id
WHERE post_id = 1;


INSERT INTO likes (user_id, post_id)
  VALUES (2, 1);
INSERT INTO likes (user_id, post_id)
  VALUES (3, 1);
INSERT INTO likes (user_id, post_id)
  VALUES (1, 2);
INSERT INTO likes (user_id, post_id)
  VALUES (3, 3);
INSERT INTO likes (user_id, post_id)
  VALUES (2, 5);
INSERT INTO likes (user_id, post_id)
  VALUES (3, 5);


INSERT INTO subscribtions
  VALUES (NULL, 2, 3);
INSERT INTO subscribtions
  VALUES (NULL, 1, 3);
INSERT INTO subscribtions
  VALUES (NULL, 3, 2);
INSERT INTO subscribtions
  VALUES (NULL, 3, 1);


CREATE FULLTEXT INDEX post_ft_search ON post(title, text_content);


INSERT INTO hashtag
  VALUES (NULL, 'шикарныйвид');
INSERT INTO hashtag
  VALUES (NULL, 'крутосказано');
INSERT INTO hashtag
  VALUES (NULL, 'полезнаяссылка');
INSERT INTO hashtag
  VALUES (NULL, 'крутойсериал');
INSERT INTO hashtag
  VALUES (NULL, 'игрпрестолов');
INSERT INTO hashtag
  VALUES (NULL, 'лучшеефото');
INSERT INTO hashtag
  VALUES (NULL, 'nature');


INSERT INTO hashtags_posts
  VALUES (NULL, 2, 1);
INSERT INTO hashtags_posts
  VALUES (NULL, 4, 2);
INSERT INTO hashtags_posts
  VALUES (NULL, 5, 2);
INSERT INTO hashtags_posts
  VALUES (NULL, 3, 5);
INSERT INTO hashtags_posts
  VALUES (NULL, 6, 3);
INSERT INTO hashtags_posts
  VALUES (NULL, 7, 3);
INSERT INTO hashtags_posts
  VALUES (NULL, 1, 4);
INSERT INTO hashtags_posts
  VALUES (NULL, 7, 4);


