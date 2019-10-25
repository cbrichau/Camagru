<?php
$DB_DSN = 'mysql:host=localhost';
$DB_USER = 'root';
$DB_PASSWORD = 'password';
$DB_NAME = 'cbrichau_camagru';

$create_db = 'CREATE DATABASE IF NOT EXISTS '.$DB_NAME;

$create_users_table = 'CREATE TABLE IF NOT EXISTS users
                       (id_user int(11) NOT NULL AUTO_INCREMENT,
                        email varchar(255) NOT NULL,
                        username varchar(255) NOT NULL,
                        password varchar(255) NOT NULL,
                        notifications_on tinyint(1) NOT NULL DEFAULT 1,
                        email_confirmed varchar(255) NOT NULL,
                        PRIMARY KEY (id_user)
                       ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';

$create_likes_table = 'CREATE TABLE IF NOT EXISTS likes
                       (id_image varchar(15) NOT NULL,
                        nb_likes int(11) NOT NULL,
                        PRIMARY KEY (id_image)
                       ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';

$create_comment_table = 'CREATE TABLE IF NOT EXISTS comments
                         (id_comment int(11) NOT NULL AUTO_INCREMENT,
                          id_image varchar(15) NOT NULL,
                          id_user int(11) NOT NULL,
                          publication_date datetime NOT NULL,
                          comment text NOT NULL,
                          PRIMARY KEY (id_comment),
                          FOREIGN KEY (FK_comments_id_user) REFERENCES users(id_user) ON DELETE CASCADE ON UPDATE CASCADE
                          FOREIGN KEY (FK_comments_id_image) REFERENCES likes(id_image) ON DELETE CASCADE ON UPDATE CASCADE
                         ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';

try
{
  $DB = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
  $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $DB->exec($create_db);
  $DB->exec($create_users_table);
  $DB->exec($create_likes_table);
  $DB->exec($create_comments_table);
}
catch (PDOException $e) { die('DB error: '.$e->getMessage()); }






/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
