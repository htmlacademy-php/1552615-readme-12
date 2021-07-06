INSERT INTO content_type VALUES (NULL, 'Текст', 'text'), (NULL, 'Картинка', 'photo'), (NULL, 'Видео', 'video'), (NULL, 'Ссылка', 'link'), (NULL, 'Цитата', 'quote');

INSERT INTO user (id, email, user_login, user_password, avatar) VALUES (NULL, 'pikachu@gmail.com', 'pikachu', 'pikapika777', 'img/avatar-pikachu.jpg'), (NULL, 'petr_petrovich@gmail.com', 'petr_petrovich', 'vodka_4ever', 'img/drunk_petrovich.png'), (NULL, 'valiko@yandex.ru', 'valiko', 'mimino555', 'img/valiko_and_larisa_sergeevna.jpg');

INSERT INTO post (id, title, author, picture, video, link, user_id, type_id) VALUES
(NULL, 'Цитата', 'Лариса', '')

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





