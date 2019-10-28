<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('database.php');

$create_db = 'CREATE DATABASE IF NOT EXISTS '.$DB_NAME;

$select_db = 'USE '.$DB_NAME;

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

$create_comments_table = 'CREATE TABLE IF NOT EXISTS comments
                          (id_comment int(11) NOT NULL AUTO_INCREMENT,
                           id_image varchar(15) NOT NULL,
                           id_user int(11) NOT NULL,
                           publication_date datetime NOT NULL,
                           comment text NOT NULL,
                           PRIMARY KEY (id_comment),
                           CONSTRAINT FK_comments_id_user FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE ON UPDATE CASCADE,
                           CONSTRAINT FK_comments_id_image FOREIGN KEY (id_image) REFERENCES likes(id_image) ON DELETE CASCADE ON UPDATE CASCADE
                          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';

$DB = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$DB->exec($create_db);
$DB->exec($select_db);
$DB->exec($create_users_table);
$DB->exec($create_likes_table);
$DB->exec($create_comments_table);
echo '<p>Installation complete.'
?>
