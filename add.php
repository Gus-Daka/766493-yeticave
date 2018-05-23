<?php
require_once 'functions.php';

$link = mysqli_connect('localhost', 'root', 'Daka242347', 'yeticave');

if (!$link) {
    $sql_error = mysqli_connect_error();
    print('Ошибка подключения к БД: ' . $sql_error);

} else {
    
    $category_sql = "SELECT * FROM category";

    $result = mysqli_query($link, $category_sql);
    $categories = [];

  if($result) { 
    $cats = mysqli_fetch_all($result, MYSQLI_ASSOC);
        foreach ($cats as $cat) {
        $categories[] = $cat['cat_name'];
    }

  } else {
    $sql_error = mysqli_error($link);
    $content = '';
    print('Ошибка базы данных: ' . $sql_error);
  }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $lot = $_POST['lot'];
        
        $required = ['lot_name', 'cat_name', 'description', 'start_price', 'rate_price', 'finish_lot'];
        
        $dict = [
        'lot_name' => 'Наименование',
        'cat_name' => 'Категория',
        'description' => 'Описание',
        'start_price' => 'Начальная цена',
        'rate_price' => 'Шаг ставки',
        'finish_lot' => 'Дата окончания торгов',
        'file' => 'Лот'
    ];
        $errors = [];

        foreach ($required as $key) {
            if (empty($_POST[$key])) {
                $errors[$key] = 'Необходимо заполнить поле';
            }
        }

        if  (isset($_FILES['lot_img']['name'])) {
            $tmp_name = $_FILES['lot_img']['tmp_name'];
            $path = $_FILES['lot_img']['name'];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($finfo, $tmp_name);
            
            if ($file_type == 'image/png' || $file_type == 'image/jpeg') {
                $filename = uniqid();
                $lot['lot_image'] = 'img/' . $filename . '.jpg';
                move_uploaded_file($tmp_name, 'img/' . $path);
                $lot['path'] = $path;
            } 
            else {
                $errors['file'] = 'Загрузите картинку в формате JPEG или PNG';
            }
        } 
        else {
            $errors['file'] = 'Вы не загрузили файл';
        }

        if (!is_numeric($lot['start_price']) || ($lot['start_price'] <= 0)) {
            $errors['start_price'] = 'Введите число больше 0';
        }

        $form_date = strtotime($lot['finish_lot']);
        
        if (!is_numeric($form_date) || ($form_date < strtotime('+1 day'))) {
            $errors['finish_lot'] = 'Введите корректную дату';
        }

        if (!ctype_digit($lot['rate_price']) || ((int)($lot['rate_price']) < 0)) {
            $errors['rate_price'] = 'Введите целое число больше 0';
        }

        if (count($errors)) {
            $page_content = renderTemplate('templates/add-lot.php', ['lot' => $lot, 'errors' => $errors, 'dict' => $dict, 'categories' => $categories]);
        } else {

            $sql = 'INSERT INTO lots (created_at, lot_name, description, lot_image, start_price, finish_lot, rate_price, user_id, category_id)
            VALUES (NOW(), ?, ?, ?, ?, ?, ?, 1, ?)';

            $stmt = db_get_prepare_stmt($link, $sql, [
                $lot['lot_name'], 
                $lot['description'], 
                $lot['lot_image'], 
                $lot['start_price'],
                $lot['finish_lot'], 
                $lot['rate_price'], 
                $lot['category_id']
            ]);

            $res = mysqli_stmt_execute($stmt);

            if ($res) {
                $lot_id = mysqli_insert_id($link);
                header("Location: lot.php?lot_id=" . $lot_id);

            } else {
                $sql_error = mysqli_error($link);
                $page_content = '';
                print('Ошибка базы данных: ' . $sql_error);
            }
        }
    } else {
        $page_content = renderTemplate('templates/add-lot.php', ['categories' => $categories]);
    }
}

$layout_content = renderTemplate('templates/layout.php', [
    'title' => 'Добавление нового лота',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
    'content' => $page_content,
    'categories' => $categories
]);

print($layout_content);

?>