<?php 
require_once('functions.php');
require_once('sqlconnect.php');

session_start();

//подключение к базе данных
$link = mysqli_connect('localhost', 'root', 'Daka242347', 'yeticave');

//проверка подключения к базе данных
get_sqllink_info($link);

//список категорий
$categories = get_lot_cat($link);

//проверка существования GET запроса
$get_lot_id = intval($_GET['id']);
$get_lot_id = mysqli_real_escape_string($link, $get_lot_id);

$sql_lot_id = mysqli_query($link, "SELECT id FROM lots WHERE id = '$get_lot_id'");

$row_cnt = mysqli_num_rows($sql_lot_id);

$lot_info = [];
$price_info = [];
$rate_info = [];

if(isset($get_lot_id) && !is_null($get_lot_id) && $row_cnt > 0) {
    
    $lot_info = get_lot_info($link, $get_lot_id);

    $price_info = get_lotprice_info($link, $get_lot_id);

    $rate_info = get_rate_info($link, $get_lot_id);

    $rate_div_visible = true; //здесь мы определяем показывать блок с добавлением ставки или нет

    if (!isset($_SESSION['user'])) { //определяем авторизован пользователь или нет
        $rate_div_visible = false;
    }

    //была ли ставка от пользователя на этот лот?
    $rate_exist = is_user_rate($link, $_SESSION['user']['id'], $get_lot_id);
    if ($rate_exist) {
        $rate_div_visible = false;
    }

    //лот создан текущим пользователем?
    if ($lot_info['0']['user_id'] === $_SESSION['user']['id']) {
        $rate_div_visible = false;
    }

    //истек ли срок размещения лота?
    if (strtotime($lot_info['0']['finish_lot']) < time()) {
        $rate_div_visible = false;
    }

    $page_content = renderTemplate('templates/lot.php', [
        'lot_info' => $lot_info,
        'price_info' => $price_info,
        'rate_info' => $rate_info,
        'rate_div_visible' => $rate_div_visible,
        'categories' => $categories]);

} else {
  header('HTTP/1.1 404 Not Found');
  header('Status: 404 Not Found');
  
  $page_content = 'Страница 404';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rate = $_POST['rate'];
    $required = ['rate_price'];
    $dict = ['rate_price' => 'Ваша ставка'];
    $errors = [];

    //определяем минимально возможную ставку
    $min_rate = get_min_rate($price_info['0']['start_price'], $price_info['0']['max_rate'], $price_info['0']['step_price']);

    if (!ctype_digit($rate['rate_price']) || ((int)($rate['rate_price']) < $min_rate)) {
        $errors['rate_price'] = 'Введите корректную ставку';
    }

    if (empty($rate['rate_price'])) {
        $errors['rate_price'] = 'Это поле надо заполнить';
    }

    if (count($errors)) {
        $page_content = renderTemplate('templates/lot.php', [
            'lot_info' => $lot_info,
            'price_info' => $price_info,
            'rate_info' => $rate_info,
            'errors' => $errors,
            'dict' => $dict,
            'rate' => $rate,
            'rate_div_visible' => $rate_div_visible,
            'categories' => $categories]);
    } else {
    //добавляем ставку в БД
        add_user_rate($link, $rate['rate_price'], $_SESSION['user']['id'], $get_lot_id);
    }

    $page_content = renderTemplate('templates/lot.php', [
        'lot_info' => $lot_info,
        'price_info' => $price_info,
        'rate_info' => $rate_info,
        'errors' => $errors,
        'dict' => $dict,
        'rate' => $rate,
        'rate_div_visible' => $rate_div_visible,
        'categories' => $categories]);
}

$layout_content = renderTemplate('templates/layout.php', [
    'content' => $page_content,
    'title' => 'Просмотр лота',
    'categories' => $categories
]);

print($layout_content);

?>