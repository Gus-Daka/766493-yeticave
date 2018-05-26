<?php
//подключаем шаблонизатор
require_once 'functions.php';
require_once 'data.php';

session_start();

$link = mysqli_connect('localhost', 'root', 'Daka242347', 'yeticave');

if(!$link) {
    $sql_error = mysqli_connect_error();
    print('Ошибка подключения к БД: ' . $sql_error);
} else {

    $sql = "SELECT cat_name FROM category
            ORDER BY id";

    $result = mysqli_query($link, $sql);

    if($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC); 
}
    $sql = "SELECT lots.id, lot_name, start_price, lot_image, rate_price, rate.lot_id, cat_name 
        FROM lots
        LEFT JOIN rate ON lots.id = rate.lot_id
        LEFT JOIN category ON lots.id = category.id
        WHERE lots.created_at ORDER BY lots.created_at ASC";

    if($res = mysqli_query($link, $sql)) {
        $lots = mysqli_fetch_all($res, MYSQLI_ASSOC);
        $page_content = renderTemplate('templates/index.php', ['lots' => $lots]);
    }
}

$is_auth = (bool) rand(0, 1);

$user_name = 'Константин';
$user_avatar = 'img/user.jpg';

$layout_content = renderTemplate('templates/layout.php', [
    'title' => 'Главная',
    'is_auth' => $is_auth,
    'content' => $page_content,
    'categories' => $categories
]);

print($layout_content);

?>