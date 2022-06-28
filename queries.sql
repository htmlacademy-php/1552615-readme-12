
INSERT INTO content_types (title, classname)
    VALUES ('Текст', 'text'), ('Картинка', 'photo'), ('Видео', 'video'), ('Ссылка', 'link'), ('Цитата', 'quote');


INSERT INTO users (created_at, email, login, password, avatar)
    VALUES (DATE_ADD(now(),INTERVAL 10 DAY), 'pikachu@gmail.com', 'pikachu', 'pikapika777', 'userpic-mark.jpg'), (DATE_ADD(now(),INTERVAL 2 MONTH), 'petr_petrovich@gmail.com', 'petr_petrovich', 'vodka_4ever', 'userpic.jpg'), (DATE_ADD(now(),INTERVAL 2 YEAR), 'valiko@yandex.ru', 'valiko', 'mimino555', 'userpic-larisa-small.jpg');


INSERT INTO posts (published_at, title, text_content, quote_author, watch_count, user_id, type_id)
    VALUES (DATE_ADD(now(),INTERVAL 10 DAY), 'Цитата', 'Мы в жизни любим только раз, а после ищем лишь похожих', 'Лариса', 20, 1, 5);
INSERT INTO posts (published_at, title, text_content, watch_count, user_id, type_id)
    VALUES (DATE_ADD(now(),INTERVAL 30 DAY), 'Игра престолов', 'Не могу дождаться начала финального сезона своего любимого сериала!', 12, 3, 1);
INSERT INTO posts (published_at, title, picture, watch_count, user_id, type_id)
    VALUES (DATE_ADD(now(),INTERVAL 4 MONTH), 'Наконец, обработал фотки!', 'rock-medium.jpg', 22, 2, 2);
INSERT INTO posts (published_at, title, picture, watch_count, user_id, type_id)
    VALUES (DATE_ADD(now(),INTERVAL 2 YEAR), 'Моя мечта', 'coast-medium.jpg', 101, 2, 2);
INSERT INTO posts (title, link, watch_count, user_id, type_id)
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


SELECT posts.*, u.user_login, ct.classname
FROM posts
    LEFT JOIN users u ON user_id = u.id
    LEFT JOIN content_types ct ON type_id = ct.id
ORDER BY watch_count DESC;


SELECT posts.*, watch_count, u.user_login
FROM posts
  LEFT JOIN users u ON user_id = u.id
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


INSERT INTO subscriptions
  VALUES (NULL, 2, 3);
INSERT INTO subscriptions
  VALUES (NULL, 1, 3);
INSERT INTO subscriptions
  VALUES (NULL, 3, 2);
INSERT INTO subscribtions
  VALUES (NULL, 3, 1);


CREATE FULLTEXT INDEX post_ft_search ON posts(title, text_content);


INSERT INTO hashtags
  VALUES (NULL, 'шикарныйвид');
INSERT INTO hashtags
  VALUES (NULL, 'крутосказано');
INSERT INTO hashtags
  VALUES (NULL, 'полезнаяссылка');
INSERT INTO hashtags
  VALUES (NULL, 'крутойсериал');
INSERT INTO hashtags
  VALUES (NULL, 'игрпрестолов');
INSERT INTO hashtags
  VALUES (NULL, 'лучшеефото');
INSERT INTO hashtags
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
