INSERT INTO content_type VALUES (NULL, 'Текст', 'text'), (NULL, 'Картинка', 'photo'), (NULL, 'Видео', 'video'), (NULL, 'Ссылка', 'link'), (NULL, 'Цитата', 'quote');

INSERT INTO user (id, email, user_login, user_password, avatar) VALUES (NULL, 'pikachu@gmail.com', 'pikachu', 'pikapika777', 'userpic-mark.jpg.jpg'), (NULL, 'petr_petrovich@gmail.com', 'petr_petrovich', 'vodka_4ever', 'userpic.jpg'), (NULL, 'valiko@yandex.ru', 'valiko', 'mimino555', 'userpic-larisa-small.jpg');

INSERT INTO post (id, title, text_content, quote_author, watch_count, user_id, type_id) VALUES (NULL, 'Цитата', 'Мы в жизни любим только раз, а после ищем лишь похожих', 'Лариса', 20, 1, 5);
INSERT INTO post (id, title, text_content, watch_count, user_id, type_id) VALUES (NULL, 'Игра престолов', 'Не могу дождаться начала финального сезона своего любимого сериала!', 12, 3, 1);
INSERT INTO post (id, title, picture, watch_count, user_id, type_id) VALUES (NULL, 'Наконец, обработал фотки!', 'rock-medium.jpg', 22, 2, 2);
INSERT INTO post (id, title, picture, watch_count, user_id, type_id) VALUES (NULL, 'Моя мечта', 'coast-medium.jpg', 101, 2, 2);
INSERT INTO post (id, title, link, watch_count, user_id, type_id) VALUES (NULL, 'Лучшие курсы', 'www.htmlacademy.ru', 45, 1, 4);

INSERT INTO comments (id, comment, user_id, post_id) VALUES
(NULL, 'Все супер!', 1, 1),
(NULL, 'Ну такое...', 3, 1),
(NULL, 'Очень даже неплохо!', 2, 2),
(NULL, 'Все тлен...', 1, 2),
(NULL, 'С точки зрения банальной эрудиции...', 3, 3),
(NULL, 'Всегда когда вижу ЭТО, всегда находят странные мысли...', 2, 3),
(NULL, 'Вчера казалось лучше', 3, 4),
(NULL, 'Прелестно, просто прелестно', 1, 4),
(NULL, 'Успехов тебе!', 2, 5),
(NULL, 'Отличненько!', 3, 5);

SELECT p.id, p.title, text_content, quote_author, picture, video, link, watch_count, u.user_login, ct.classname FROM post p LEFT JOIN user u ON user_id = u.id LEFT JOIN content_type ct ON type_id = ct.id ORDER BY watch_count;

SELECT p.id, p.title, text_content, quote_author, picture, video, link, watch_count, u.user_login FROM post p LEFT JOIN user u ON user_id = u.id WHERE u.user_login = 'pikachu';

SELECT c.id, comment, u.user_login FROM comments c LEFT JOIN user u ON user_id = u.id WHERE post_id = 1;

INSERT INTO likes VALUES (NULL, 2, 1);

INSERT INTO subscribtions VALUES (NULL, 2, 3);
