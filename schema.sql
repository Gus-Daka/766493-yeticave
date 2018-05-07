CREATE DATABASE IF NOT EXISTS yeticave DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE yeticave;

CREATE TABLE category (
  id int(11) NOT NULL,
  cat_name char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE lots (
  lot_id int(11) NOT NULL,
  creat_date datetime NOT NULL,
  category_id int(10) UNSIGNED NOT NULL,
  user_id int(10) UNSIGNED NOT NULL,
  rate_win_id int(10) UNSIGNED NOT NULL,
  lot_name char(255) NOT NULL,
  description text NOT NULL,
  lot_image tinytext NOT NULL,
  start_price tinyint(4) NOT NULL,
  finish_lot datetime NOT NULL,
  step_price datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE rate (
  rate_id int(11) NOT NULL,
  user_id int(10) UNSIGNED NOT NULL,
  lot_id int(10) UNSIGNED NOT NULL,
  rate_date datetime NOT NULL,
  rate_price tinyint(4) NOT NULL,
  rate_win_id tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE users (
  user_id int(11) NOT NULL,
  reg_date datetime NOT NULL,
  email char(255) NOT NULL,
  user_name tinytext NOT NULL,
  password char(64) NOT NULL,
  user_foto tinytext NOT NULL,
  contact tinytext NOT NULL,
  lot_id int(10) UNSIGNED NOT NULL,
  rate_id int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE category
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY cat_name (cat_name),
  ADD KEY cat_name_2 (cat_name) USING BTREE;

ALTER TABLE lots
  ADD PRIMARY KEY (lot_id),
  ADD UNIQUE KEY creat_date (creat_date),
  ADD UNIQUE KEY lot_name (lot_name);

ALTER TABLE rate
  ADD PRIMARY KEY (rate_id),
  ADD UNIQUE KEY rate_date (rate_date),
  ADD KEY rate_win_id (rate_win_id),
  ADD KEY rate_win_id_2 (rate_win_id);

ALTER TABLE users
  ADD PRIMARY KEY (user_id),
  ADD UNIQUE KEY email (email);


ALTER TABLE lots
  ADD CONSTRAINT lots_ibfk_1 FOREIGN KEY (lot_id) REFERENCES `users` (user_id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT lots_ibfk_2 FOREIGN KEY (lot_id) REFERENCES category (id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT lots_ibfk_3 FOREIGN KEY (lot_id) REFERENCES rate (rate_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE rate
  ADD CONSTRAINT rate_ibfk_1 FOREIGN KEY (rate_id) REFERENCES `users` (user_id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT rate_ibfk_2 FOREIGN KEY (rate_id) REFERENCES lots (lot_id);

ALTER TABLE users
  ADD CONSTRAINT users_ibfk_1 FOREIGN KEY (user_id) REFERENCES lots (lot_id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT users_ibfk_2 FOREIGN KEY (user_id) REFERENCES rate (rate_id) ON DELETE CASCADE ON UPDATE CASCADE;