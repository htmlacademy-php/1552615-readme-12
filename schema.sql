CREATE DATABASE readme DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
USE readme;
CREATE TABLE user (
    user_id INT PRIMARY KEY NOT NULL,
    reg_date DATETIME,
    email VARCHAR(45),
    UNIQUE INDEX email_ind (email),
    user_login VARCHAR(45),
    UNIQUE INDEX user_login_ind (user_login),
    user_password VARCHAR(45),
    avatar VARCHAR(100)
);
CREATE TABLE content_type (
    content_type_id INT PRIMARY KEY NOT NULL,
    title VARCHAR(30),
    classname VARCHAR(30) NOT NULL,
    INDEX classname_ind (classname)
);
CREATE TABLE hashtag (
    hashtag_id INT PRIMARY KEY NOT NULL,
    hashtag VARCHAR(30),
    INDEX hashtag_ind (hashtag)
);
CREATE TABLE post (
    post_id INT PRIMARY KEY NOT NULL,
    published_date DATETIME,
    title VARCHAR(45),
    author VARCHAR(45),
    picture VARCHAR(100),
    video VARCHAR(100),
    link VARCHAR(100),
    watch_count INT,
    user_id INT NOT NULL,
    INDEX user_ind (user_id),
    FOREIGN KEY (user_id)
        REFERENCES user(user_id)
        ON DELETE CASCADE,

    content_id INT NOT NULL,
    INDEX content_ind (content_id),
    FOREIGN KEY (content_id)
        REFERENCES content_type(content_type_id)
        ON DELETE CASCADE,

    hashtag_id INT,
    INDEX hashtag_ind (hashtag_id),
    FOREIGN KEY (hashtag_id)
        REFERENCES hashtag(hashtag_id)
        ON DELETE CASCADE
);
CREATE TABLE comment (
    comment_id INT PRIMARY KEY,
    create_date DATETIME,
    comment TEXT,
    author INT NOT NULL,
    INDEX author_ind (author),
    FOREIGN KEY (author)
        REFERENCES user(user_id)
        ON DELETE CASCADE,

    post_id INT NOT NULL,
    INDEX post_ind (post_id),
    FOREIGN KEY (post_id)
        REFERENCES post(post_id)
        ON DELETE CASCADE
);
CREATE TABLE likes (
    likes_id INT PRIMARY KEY,
    user_id INT NOT NULL,
    INDEX user_ind (user_id),
    FOREIGN KEY (user_id)
        REFERENCES user(user_id)
        ON DELETE CASCADE,

    post_id INT NOT NULL,
    INDEX post_ind (post_id),
    FOREIGN KEY (post_id)
        REFERENCES post(post_id)
        ON DELETE CASCADE
);
CREATE TABLE subscribe (
    subscribe_id INT PRIMARY KEY,
    author INT NOT NULL,
    INDEX author_ind (author),
    FOREIGN KEY (author)
        REFERENCES user(user_id)
        ON DELETE CASCADE,

    subscriber INT NOT NULL,
    INDEX subscriber_ind (subscriber),
    FOREIGN KEY (subscriber)
        REFERENCES user(user_id)
        ON DELETE CASCADE
);
CREATE TABLE user_message (
    message_id INT PRIMARY KEY,
    send_date DATETIME,
    INDEX send_date_ind (send_date),
    user_message TEXT,
    sender INT NOT NULL,
    INDEX sender_ind (sender),
    FOREIGN KEY (sender)
        REFERENCES user(user_id)
        ON DELETE CASCADE,

    reciever INT NOT NULL,
    INDEX reciever_ind (reciever),
    FOREIGN KEY (reciever)
        REFERENCES user(user_id)
        ON DELETE CASCADE
);
