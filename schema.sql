CREATE DATABASE IF NOT EXISTS yeticave DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE yeticave;

CREATE TABLE category (
  id int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  cat_name varchar(255) NOT NULL
);

CREATE TABLE lots (
  id int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  created_at datetime,
  category_id int(10) UNSIGNED,
  user_id int(10) UNSIGNED,
  rate_win_id int(10) UNSIGNED,
  lot_name varchar(255) NOT NULL,
  description text,
  lot_image tinytext,
  start_price int(10),
  finish_lot datetime,
  step_price int(11)
);

CREATE TABLE rate (
  id int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id int(10) UNSIGNED,
  lot_id int(10) UNSIGNED,
  rate_date datetime,
  rate_price int(10) NOT NULL,
  rate_win_id int(10)
);

CREATE TABLE users (
  id int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  reg_date datetime NOT NULL,
  email varchar(255) NOT NULL,
  user_name varchar(225) NOT NULL,
  password varchar(64) NOT NULL,
  user_foto tinytext NOT NULL,
  contact tinytext,
  lot_id int(10) UNSIGNED,
  rate_id int(10) UNSIGNED
);


ALTER TABLE category
  ADD UNIQUE KEY cat_name (cat_name);

ALTER TABLE lots
  ADD KEY created_at (created_at),
  ADD KEY lot_name (lot_name);

ALTER TABLE rate
  ADD KEY rate_date (rate_date),
  ADD KEY rate_win_id (rate_win_id);

ALTER TABLE users
  ADD UNIQUE KEY email (email);

ALTER TABLE lots
	ADD CONSTRAINT lots_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT lots_ibfk_2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT lots_ibfk_3 FOREIGN KEY (rate_win_id) REFERENCES rate (id) ON DELETE CASCADE ON UPDATE CASCADE;
 
 ALTER TABLE rate
	ADD CONSTRAINT rate_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT rate_ibfk_2 FOREIGN KEY (lot_id) REFERENCES lots (id) ON DELETE CASCADE ON UPDATE CASCADE;
 
 ALTER TABLE users
	ADD CONSTRAINT users_ibfk_1 FOREIGN KEY (lot_id) REFERENCES lots (id) ON DELETE CASCADE ON UPDATE CASCADE,
	ADD CONSTRAINT users_ibfk_2 FOREIGN KEY (rate_id) REFERENCES rate (id) ON DELETE CASCADE ON UPDATE CASCADE; 