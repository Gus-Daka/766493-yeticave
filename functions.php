<?php

function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);
    
    if ($data) {
        $types = '';
        $stmt_data = [];
        
        foreach ($data as $value) {
            $type = null;
            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }
            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }
        $values = array_merge([$stmt, $types], $stmt_data);
        $func = 'mysqli_stmt_bind_param';
        $func(...$values);
    }
    return $stmt;
}

//шаблонизатор
function renderTemplate($tpath, $tdata) {
	$content = '';

	if (file_exists($tpath)) { 
		ob_start();
		extract($tdata);
		require($tpath);
		$content = ob_get_clean();
	}
	return $content;
}

//функция вывода цены с разделением на тысячные
function rurNumberFormat(int $price) {
	return number_format($price, 0, '.', ' ') . '<b class="rub">р</b>';
}

//временная зона по умолчанию
date_default_timezone_set("Europe/Moscow");

//функция показывает, сколько осталось до назначенной даты и времени
function timeToFinish() {
	$time = strtotime('tomorrow');
	$today = time();
	$day = $time - $today;
	$time_hours = floor($day / 3600);
	$time_minutes = floor(($day % 3600) / 60);
	$get_time = $time_hours . ':' . $time_minutes;

	return date('H:i', strtotime($get_time));
}

function get_lot_cat($link) {
    $sql_category = "SELECT id, cat_name FROM category
    ORDER BY id";
    $cat_res = mysqli_query($link, $sql_category);
    $categories = [];
    if ($cat_res) {
        $categories = mysqli_fetch_all($cat_res, MYSQLI_ASSOC);
    } else {
        $sql_error = mysqli_error($link);
        $page_content = '';
        print('Ошибка базф данных: ' . $sql_error);
    }
    return $categories;
}
function get_sqllink_info($link) {
    if (!$link) {
        $sql_error = mysqli_connect_error();
        print('Ошибка подключения к БД: ' . $sql_error);
        return;
    }
}
function show_sql_err($link) {
    $sql_error = mysqli_error($link);
    print('Ошибка базы данных: ' . $sql_error);
}
function get_lots($link) {
    $sql_lots = "SELECT lots.id, lot_name, start_price, lot_image, rate_price, rate.lot_id, category.cat_name 
        FROM lots
        LEFT JOIN rate ON lots.id = rate.lot_id
        LEFT JOIN category ON lots.id = category.id
        WHERE lots.created_at ORDER BY lots.created_at DESC";
        if ($res = mysqli_query($link, $sql_lots)) {
            $lots = mysqli_fetch_all($res, MYSQLI_ASSOC);
        return $lots;
    }
    show_sql_err($link);
}
function get_lot_info($link, $lot_id) {
    $lot_sql = "SELECT l.id AS id, l.lot_name AS lot_name, l.description AS description,
    l.lot_image AS lot_image, l.user_id AS user_id, l.finish_lot AS finish_lot, c.cat_name AS cat_name
    FROM lots l
    JOIN category c
    ON l.category_id = c.id
    WHERE l.id = '$lot_id'";
    if  ($lot_res = mysqli_query($link, $lot_sql)) {
        $lot_info = mysqli_fetch_all($lot_res, MYSQLI_ASSOC);
        return $lot_info;
    }
    return show_sql_err($link);
}
function get_lotprice_info($link, $lot_id) {
    $price_sql = "SELECT l.id AS id, l.start_price AS start_price, b.rate_price AS rate_price, MAX(b.rate_price) AS max_rate
    FROM lots l
    JOIN rate b
    ON l.id = b.lot_id
    WHERE l.id = '$lot_id'";
    if ($price_res = mysqli_query($link, $price_sql)) {
        $price_info = mysqli_fetch_all($price_res, MYSQLI_ASSOC);
        return $price_info;
    }
    return show_sql_err($link);
}
function get_rate_info($link, $lot_id) {
    $rate_sql = "SELECT b.rate_date AS rate_date, b.rate_price AS rate_price, u.user_name AS user_name
    FROM rate b
    JOIN users u
    ON u.id = b.user_id
    WHERE b.lot_id = '$lot_id'
    ORDER BY rate_date DESC LIMIT 10";
    if ($rate_res = mysqli_query($link, $rate_sql)) {
        $rate_info = mysqli_fetch_all($rate_res, MYSQLI_ASSOC);
        return $rate_info;
    }
    return show_sql_err($link);
}
function is_user_rate($link, $user_id, $lot_id) {
    $sql = "SELECT user_id, lot_id FROM rate
    WHERE user_id = '$user_id' AND lot_id = '$lot_id'";
    if ($res = mysqli_query($link, $sql)) {
        $rate_exist = mysqli_fetch_all($res, MYSQLI_ASSOC);
        return boolval($rate_exist);
    }
}
function get_min_rate ($start_price, $max_rate, $rate_price) {
    $max_price = max($primary_price, $max_rate);
    $min_rate = $max_price + $rate_price;
    return $min_rate;
}
function formated_rate_date($rate_date) {
    $rate_time = strtotime($rate_date);
    $time_after_rate = time() - $rate_time;
    if ($time_after_rate < 3600) {
        $result = ($time_after_rate / 60) % 60 . ' минут назад';
    } elseif ($time_after_rate < 86400) {
        $result = (($time_after_rate / 60) % 60) % 60 . ' часов назад';
    }else {
        $result = date('d.m.y в H:i', $time_after_rate);
    }
    return $result;
}
function add_user_rate($link, $rate_price, $user_id, $lot_id) {
    $sql = 'INSERT INTO rate (rate_date, rate_price, user_id, lot_id)
    VALUES (NOW(), ?, ?, ?)';
    $stmt = db_get_prepare_stmt($link, $sql, [$rate_price, $user_id, $lot_id]);
    $res = mysqli_stmt_execute($stmt);
    if ($res) {
        header("Location: lot.php?lot_id=" . $lot_id);
        return;
    }
    return show_sql_err($link);
}

?>