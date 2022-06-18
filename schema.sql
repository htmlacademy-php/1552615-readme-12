CREATE DATABASE readme_second DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
USE readme_second;
CREATE TABLE user (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email VARCHAR(45) NOT NULL,
    UNIQUE INDEX email_ind (email),
    user_login VARCHAR(45) NOT NULL,
    UNIQUE INDEX user_login_ind (user_login),
    user_password VARCHAR(200),
    avatar VARCHAR(100)
);
CREATE TABLE content_type (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    title VARCHAR(30) NOT NULL,
    classname VARCHAR(30) NOT NULL
);
CREATE TABLE hashtag (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    hashtag VARCHAR(30),
    INDEX hashtag_ind (hashtag)
);
CREATE TABLE post (
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
        REFERENCES user(id)
        ON DELETE CASCADE,

    type_id INT NOT NULL,
    INDEX type_ind (type_id),
    FOREIGN KEY (type_id)
        REFERENCES content_type(id)
        ON DELETE CASCADE
);
CREATE TABLE hashtags_posts (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    hashtag_id INT,
    INDEX hashtag_ind (hashtag_id),
    FOREIGN KEY (hashtag_id)
        REFERENCES hashtag(id)
        ON DELETE CASCADE,
    post_id INT NOT NULL,
    FOREIGN KEY (post_id)
        REFERENCES post(id)
        ON DELETE CASCADE
);
CREATE TABLE comments (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    comment TEXT,
    user_id INT NOT NULL,
    INDEX user_ind (user_id),
    FOREIGN KEY (user_id)
        REFERENCES user(id)
        ON DELETE CASCADE,

    post_id INT NOT NULL,
    INDEX post_ind (post_id),
    FOREIGN KEY (post_id)
        REFERENCES post(id)
        ON DELETE CASCADE
);
CREATE TABLE likes (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    INDEX user_ind (user_id),
    FOREIGN KEY (user_id)
        REFERENCES user(id)
        ON DELETE CASCADE,

    post_id INT NOT NULL,
    INDEX post_ind (post_id),
    FOREIGN KEY (post_id)
        REFERENCES post(id)
        ON DELETE CASCADE,

    UNIQUE INDEX user_post_ind (user_id, post_id)
);
CREATE TABLE subscribtions (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    INDEX user_ind (user_id),
    FOREIGN KEY (user_id)
        REFERENCES user(id)
        ON DELETE CASCADE,

    to_user_id INT NOT NULL,
    INDEX to_user_id_ind (to_user_id),
    FOREIGN KEY (to_user_id)
        REFERENCES user(id)
        ON DELETE CASCADE
);
CREATE TABLE user_message (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_message TEXT,
    user_id INT NOT NULL,
    INDEX user_ind (user_id),
    FOREIGN KEY (user_id)
        REFERENCES user(id)
        ON DELETE CASCADE,

    to_user_id INT NOT NULL,
    INDEX to_user_ind (to_user_id),
    FOREIGN KEY (to_user_id)
        REFERENCES user(id)
        ON DELETE CASCADE
);


ALTER TABLE likes ADD liked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE post ADD is_repost INT DEFAULT 0;

ALTER TABLE post ADD original_author_id INT DEFAULT NULL;
ALTER TABLE post ADD INDEX original_author_ind(original_author_id);
ALTER TABLE post ADD
FOREIGN KEY (original_author_id)
  REFERENCES user(id)
  ON DELETE CASCADE;

ALTER TABLE post ADD repost_count INT DEFAULT 0;

ALTER TABLE post ADD original_post_id INT DEFAULT NULL;
