<?php
//подключаем шаблонизатор
require_once 'functions.php';
require_once 'sqlconnect.php';

session_start();

$link = mysqli_connect('localhost', 'root', 'Daka242347', 'yeticave');

get_sqllink_info($link);

$lots = get_lots($link);

$page_content = renderTemplate('templates/index.php', ['lots' => $lots]);

$categories = get_lot_cat($link);

$layout_content = renderTemplate('templates/layout.php', [
    'title' => 'Главная',
    'content' => $page_content,
    'categories' => $categories
]);

print($layout_content);

?>