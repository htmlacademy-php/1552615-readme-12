CREATE DATABASE readme DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
USE readme;
CREATE TABLE users (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email VARCHAR(45) NOT NULL,
    UNIQUE INDEX email_ind (email),
    login VARCHAR(45) NOT NULL,
    UNIQUE INDEX user_login_ind (login),
    password VARCHAR(200),
    avatar VARCHAR(100)
);
CREATE TABLE content_types (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    title VARCHAR(30) NOT NULL,
    classname VARCHAR(30) NOT NULL
);
CREATE TABLE hashtags (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    hashtag VARCHAR(30),
    INDEX hashtag_ind (hashtag)
);
CREATE TABLE posts (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    title VARCHAR(45) NOT NULL,
    text_content VARCHAR(200),
    quote_author VARCHAR(45),
    picture VARCHAR(100),
    video VARCHAR(100),
    link VARCHAR(100),
    watch_count INT,
    user_id INT NOT NULL,
    INDEX user_ind (user_id),
    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    type_id INT NOT NULL,
    INDEX type_ind (type_id),
    FOREIGN KEY (type_id)
        REFERENCES content_types(id)
        ON DELETE CASCADE
);
CREATE TABLE hashtags_posts (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    hashtag_id INT,
    INDEX hashtag_ind (hashtag_id),
    FOREIGN KEY (hashtag_id)
        REFERENCES hashtags(id)
        ON DELETE CASCADE,
    post_id INT NOT NULL,
    INDEX post_ind (post_id),
    FOREIGN KEY (post_id)
        REFERENCES posts(id)
        ON DELETE CASCADE
);
CREATE TABLE comments (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    comment TEXT,
    user_id INT NOT NULL,
    INDEX user_ind (user_id),
    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    post_id INT NOT NULL,
    INDEX post_ind (post_id),
    FOREIGN KEY (post_id)
        REFERENCES posts(id)
        ON DELETE CASCADE
);
CREATE TABLE likes (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    INDEX user_ind (user_id),
    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    post_id INT NOT NULL,
    INDEX post_ind (post_id),
    FOREIGN KEY (post_id)
        REFERENCES posts(id)
        ON DELETE CASCADE,

    UNIQUE INDEX user_post_ind (user_id, post_id)
);
CREATE TABLE subscriptions (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    INDEX user_ind (user_id),
    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    to_user_id INT NOT NULL,
    INDEX to_user_id_ind (to_user_id),
    FOREIGN KEY (to_user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);
CREATE TABLE user_messages (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_message TEXT,
    user_id INT NOT NULL,
    INDEX user_ind (user_id),
    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    to_user_id INT NOT NULL,
    INDEX to_user_ind (to_user_id),
    FOREIGN KEY (to_user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);


ALTER TABLE likes ADD liked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE posts ADD is_repost INT DEFAULT 0;

ALTER TABLE posts ADD original_author_id INT DEFAULT NULL;
ALTER TABLE posts ADD INDEX original_author_ind(original_author_id);
ALTER TABLE posts ADD
FOREIGN KEY (original_author_id)
  REFERENCES users(id)
  ON DELETE CASCADE;

ALTER TABLE posts ADD repost_count INT DEFAULT 0;

ALTER TABLE posts ADD original_post_id INT DEFAULT NULL;
