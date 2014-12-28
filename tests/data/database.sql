CREATE DATABASE IF NOT EXISTS test;

USE test;

CREATE TABLE `relations_id` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `parent_id` int NULL
) ENGINE='InnoDB' COLLATE 'utf8_czech_ci';

ALTER TABLE `relations_id`
ADD INDEX `parent_id` (`parent_id`);

ALTER TABLE `relations_id`
ADD FOREIGN KEY (`parent_id`) REFERENCES `relations_id` (`id`);

INSERT INTO `relations_id` (`parent_id`)
VALUES (NULL);

CREATE TABLE `form_items` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE='InnoDB' COLLATE 'utf8_czech_ci';

INSERT INTO `form_items` (`title`, `name`)
VALUES ('John_title', 'John_name');
