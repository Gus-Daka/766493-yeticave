<?php

$link = mysqli_connect('localhost', 'root', 'Daka242347', 'yeticave');

$sql = 'SELECT id, cat_name FROM category';

$result = mysqli_query($link, $sql);

if($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$sql = 'SELECT lot_name, start_price, lot_image, rate_price, rate.id, cat_name 
FROM lots
LEFT JOIN rate ON lots.id = rate.id
LEFT JOIN category ON lots.id = category.id
WHERE lots.created_at ORDER BY id DESC';

if($res = mysqli_query($link, $sql)) {
    $lots = mysqli_fetch_all($res, MYSQLI_ASSOC);
}

//подключаем шаблонизатор
require_once('functions.php');

$is_auth = (bool) rand(0, 1);

$user_name = 'Константин';
$user_avatar = 'img/user.jpg';

$page_content = renderTemplate('templates/index.php', [
    'lots' => $lots 
]);

$layout_content = renderTemplate('templates/layout.php', [
    'title' => 'Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
    'content' => $page_content,
    'categories' => $categories
]);

print($layout_content);

print include_tamplate('templates/layout.php', 
    ['title' => 'Главная', 
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
    'content' => $page_content,
    'categories' => $categories]);

?>