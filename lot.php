<?php 
require_once('functions.php');
require_once('data.php');

session_start();

$link = mysqli_connect('localhost', 'root', 'Daka242347', 'yeticave');

if(!$link) {
  $sql_error = mysqli_connect_error();
  print('Ошибка поключения: ' . $sql_error);
}
else {
    
    $category_sql = "SELECT cat_name FROM category
    ORDER BY id";

    $result = mysqli_query($link, $category_sql);

  if($result) { 
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

  } else {
    $sql_error = mysqli_error($link);
    $content = '';
    print('Ошибка базы данных: ' . $sql_error);
  }
}

// проверка существования GET запроса
$get_lot_id = $_GET['id'];
    
$sql_lot_id = mysqli_query($link, "SELECT id FROM lots WHERE id = '$get_lot_id'");
$row_cnt = mysqli_num_rows($sql_lot_id);

if (isset($get_lot_id) && !is_null($get_lot_id) && $row_cnt > 0) {
    //получаем информацию о лоте
    $lot_sql = "SELECT l.id AS id, l.lot_name AS lot_name, l.description AS description,
        l.lot_image AS lot_image, c.cat_name AS cat_name
        FROM lots l
        JOIN category c
        ON l.category_id = c.id
        WHERE l.id = '$get_lot_id'";
        if  ($lot_res = mysqli_query($link, $lot_sql)) {
            $lot_info = mysqli_fetch_all($lot_res, MYSQLI_ASSOC);
        } else {
            $sql_error = mysqli_error($link);
            $content = '';
            print('Ошибка базы данных: ' . $sql_error);
        }
        //получаем информацию о цене
        $price_sql = "SELECT l.id, l.start_price AS start_price, l.step_price AS step_price, MAX(b.rate_price) AS max_rate
        FROM lots l
        JOIN rate b
        ON l.id = b.id
        WHERE l.id = '$get_lot_id'";
        if ($price_res = mysqli_query($link, $price_sql)) {
            $price_info = mysqli_fetch_all($price_res, MYSQLI_ASSOC);
        } else {
            $sql_error = mysqli_error($link);
            $content = '';
            print('Ошибка базы данных: ' . $sql_error);
        }
        //получаем информацию о ставках
        $rate_sql = "SELECT b.rate_date AS rate_date, b.rate_price AS rate_price, u.user_name AS user_name
        FROM rate b
        JOIN users u
        ON u.id = b.user_id
        WHERE u.lot_id = '$get_lot_id'
        ORDER BY rate_date DESC LIMIT 9";
        if ($rate_res = mysqli_query($link, $rate_sql)) {
            $rate_info = mysqli_fetch_all($rate_res, MYSQLI_ASSOC);
        } else {
            $sql_error = mysqli_error($link);
            $content = '';
            print('Ошибка базы данных: ' . $sql_error);
        }

$content = renderTemplate('templates/lot.php', [
    'lot_info' => $lot_info,
    'price_info' => $price_info,
    'rate_info' => $rate_info,
    'categories' => $categories
]);
        
$layout_content = renderTemplate('templates/layout.php', [
    'content' => $content,
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
    'categories' => $categories
]);

} else {
  header('HTTP/1.1 404 Not Found');
  header('Status: 404 Not Found');
  
  $content = 'Страница 404';
  
  $layout_content = renderTemplate('templates/layout.php', [
    'content' => $content,
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
    'categories' => $categories
]);
}

print($layout_content);
?>