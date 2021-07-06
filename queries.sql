INSERT INTO content_type VALUES (NULL, 'Текст', 'text'), (NULL, 'Картинка', 'photo'), (NULL, 'Видео', 'video'), (NULL, 'Ссылка', 'link'), (NULL, 'Цитата', 'quote');

INSERT INTO user VALUES (NULL, CURRENT_TIMESTAMP, 'pikachu@gmail.com', 'pikachu', 'pikapika777', 'img/avatar-pikachu.jpg'), (NULL, CURRENT_TIMESTAMP, 'petr_petrovich@gmail.com', 'petr_petrovich', 'vodka_4ever', 'img/drunk_petrovich.png'), (NULL, CURRENT_TIMESTAMP, 'valiko@yandex.ru', 'valiko', 'mimino555', 'img/valiko_and_larisa_sergeevna.jpg');

INSERT INTO comments VALUES (NULL, now(), 'Все супер!', 1, 2), (NULL, now(), 'Ну такое...', 3, 3);





