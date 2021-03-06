<?php
require_once 'functions.php';

session_start();

$link = mysqli_connect('localhost', 'root', 'Daka242347', 'yeticave');

if (!$link) {
    $sql_error = mysqli_connect_error();
    print('Ошибка подключения к БД: ' . $sql_error);

} else {
    
    $category_sql = "SELECT id, cat_name FROM category";

    $result = mysqli_query($link, $category_sql);

  if($result) { 
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

  } else {
    $sql_error = mysqli_error($link);
    $page_content = '';
    print('Ошибка базы данных: ' . $sql_error);
  }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $lot = $_POST['lot'];
        
        $required = ['lot_name', 'cat_name', 'description', 'start_price', 'step_price', 'finish_lot'];
        
        $dict = [
        'lot_name' => 'Наименование',
        'cat_name' => 'Категория',
        'description' => 'Описание',
        'start_price' => 'Начальная цена',
        'step_price' => 'Шаг ставки',
        'finish_lot' => 'Дата окончания торгов',
        'file' => 'Лот'
    ];
        $errors = [];

        foreach ($required as $key) {
            if (empty($lot[$key])) {
                $errors[$key] = 'Необходимо заполнить поле';
            }
        }

        if  (isset($_FILES['lot_image']['name'])) {
            $tmp_name = $_FILES['lot_image']['tmp_name'];
            $path = $_FILES['lot_image']['name'];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($finfo, $tmp_name);
            
            if ($file_type == 'image/png' || $file_type == 'image/jpeg') {
                $filename = uniqid();
                $lot['lot_image'] = 'img/' . $filename . '.jpg';
                move_uploaded_file($tmp_name, $lot['lot_image']);
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

        if (!ctype_digit($lot['step_price']) || ((int)($lot['step_price']) < 0)) {
            $errors['step_price'] = 'Введите целое число больше 0';
        }

        if (count($errors)) {
            $page_content = renderTemplate('templates/add-lot.php', ['lot' => $lot, 'errors' => $errors, 'dict' => $dict, 'categories' => $categories]);
        } else {

            $sql = "INSERT INTO lots (created_at, lot_name, description, lot_image, start_price, finish_lot, step_price, user_id, category_id)
            VALUES (NOW(), ?, ?, ?, ?, ?, ?, 1, ?)";

            $stmt = db_get_prepare_stmt($link, $sql, [
                $lot['lot_name'], 
                $lot['description'], 
                $lot['lot_image'], 
                $lot['start_price'],
                $lot['finish_lot'], 
                $lot['step_price'], 
                $lot['cat_name']
            ]);

            $res = mysqli_stmt_execute($stmt);

            if ($res) {
                $lot_id = mysqli_insert_id($link);
                header("Location: lot.php?id=" . $lot_id);

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

if (!isset($_SESSION['user'])) {
    header('HTTP/1.1 403 Forbidden');
    header('Status: 403 Forbidden');
    
    $page_content = 'Страница 403, доступ запрещен';
}

$layout_content = renderTemplate('templates/layout.php', [
    'title' => 'Добавление нового лота',
    'is_auth' => $is_auth,
    'content' => $page_content,
    'categories' => $categories
]);

print($layout_content);

?>