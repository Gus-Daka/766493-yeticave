CREATE DATABASE IF NOT EXISTS yeticave DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE yeticave;

CREATE TABLE category (
  id int(11) NOT NULL,
  cat_name char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE lots (
  id int(11) NOT NULL,
  created_at int(11) NOT NULL,
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
  id int(11) NOT NULL,
  user_id int(10) UNSIGNED NOT NULL,
  lot_id int(10) UNSIGNED NOT NULL,
  rate_date datetime NOT NULL,
  rate_price tinyint(4) NOT NULL,
  rate_win_id tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE users (
  id int(11) NOT NULL,
  reg_date datetime NOT NULL,
  email char(255) NOT NULL,
  user_name varchar(225) NOT NULL,
  password char(64) NOT NULL,
  user_foto tinytext NOT NULL,
  contact tinytext NOT NULL,
  lot_id int(10) UNSIGNED NOT NULL,
  rate_id int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE category
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY cat_name (cat_name);

ALTER TABLE lots
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY created_at (created_at),
  ADD UNIQUE KEY lot_name (lot_name);

ALTER TABLE rate
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY rate_date (rate_date),
  ADD KEY rate_win_id (rate_win_id);

ALTER TABLE users
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY email (email);


ALTER TABLE lots
  ADD CONSTRAINT lots_ibfk_1 FOREIGN KEY (id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT lots_ibfk_2 FOREIGN KEY (id) REFERENCES category (id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT lots_ibfk_3 FOREIGN KEY (id) REFERENCES rate (id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE rate
  ADD CONSTRAINT rate_ibfk_1 FOREIGN KEY (id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT rate_ibfk_2 FOREIGN KEY (id) REFERENCES lots (id);

ALTER TABLE users
  ADD CONSTRAINT users_ibfk_1 FOREIGN KEY (id) REFERENCES lots (id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT users_ibfk_2 FOREIGN KEY (id) REFERENCES rate (id) ON DELETE CASCADE ON UPDATE CASCADE;