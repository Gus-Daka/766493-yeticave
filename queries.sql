INSERT INTO category (id, cat_name)
VALUES 	(1, 'Диски и лыжи'), 
		(2, 'Крепления'), 
		(3, 'Ботинки'), 
		(4, 'Одежда'), 
		(5, 'Инструменты'),
		(6, 'Разное');

INSERT INTO users (reg_date, email, user_name, password, user_foto, contact, lot_id, rate_id)
VALUES 	('2018-05-05', 'user1@mail.ru', 'user1', 'secret', 'img/foto1.jpg', '8(999) 495 95 95'),
		('2018-05-06', 'user2@mail.ru', 'user2', 'secret', 'img/foto2.jpg', '8(999) 490 90 90');

INSERT INTO lots (created_at, category_id,user_id, lot_name, description, lot_image, start_price)
VALUES	('2018-05-05', 1, 1,'2014 Rossignol District Snowboard', 
		'Здесь описание товара, насколько хорош для покупателя, плюсы, минусы, характеристики и т.д.', 
		'img/lot-1.jpg', 10999),
		('2018-05-05', 1, 1, 'DC Ply Mens 2016/2017 Snowboard', 
		'Здесь описание товара, насколько хорош для покупателя, плюсы, минусы, характеристики и т.д.', 
		'img/lot-2.jpg', 159999),
		('2018-05-05', 2, 1,'Крепления Union Contact Pro 2015 года размер L/XL', 
		'Здесь описание товара, насколько хорош для покупателя, плюсы, минусы, характеристики и т.д.', 
		'img/lot-3.jpg', 8000), 
		('2018-05-06', 3, 2, 'Ботинки для сноуборда DC Mutiny Charocal', 
		'Здесь описание товара, насколько хорош для покупателя, плюсы, минусы, характеристики и т.д.', 
		'img/lot-4.jpg', 10999), 
		('2018-05-06', 4, 2, 'Куртка для сноуборда DC Mutiny Charocal', 
		'Здесь описание товара, насколько хорош для покупателя, плюсы, минусы, характеристики и т.д.', 
		'img/lot-5.jpg', 7500),
		('2018-05-06', 6, 2, 'Маска Oakley Canopy', 
		'Здесь описание товара, насколько хорош для покупателя, плюсы, минусы, характеристики и т.д.', 
		'img/lot-6.jpg', 5400);

INSERT INTO rate (user_id, lot_id, rate_date, rate_price)
VALUES	(1, 2, '2018-05-05', 165000),
		(2, 4, '2018-05-06', 15000);

SELECT * FROM category; /* Получить все категории */

SELECT lot_name, start_price, lot_image, rate_price, rate.id, cat_name 
FROM lots
LEFT JOIN rate ON lots.id = rate.id
LEFT JOIN category ON lots.id = category.id
WHERE lots.created_at ORDER BY id DESC; /* получить самые новые, открытые лоты.  */

SELECT lot_name, start_price, lot_image, rate_price, rate.id, cat_name 
FROM lots
LEFT JOIN rate ON lots.id = rate.id
LEFT JOIN category ON lots.id = category.id
WHERE ORDER BY created_at ASC;

SELECT lots.id, cat_name FROM lots
LEFT JOIN category
ON lots.id = category.id; /* показать лот по его id. Получите также название категории, к которой принадлежит лот  */

UPDATE lots SET lot_name = 'New name of Lot'
WHERE id = 1; /* обновить название лота по его идентификатору */

SELECT lots.id, rate_date FROM lots 
LEFT JOIN rate ON lots.id = rate.id
WHERE lots.created_at ORDER BY id DESC; /* список самых свежих ставок для лота по его идентификатору; */
