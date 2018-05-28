<?php
require_once('functions.php');
require_once('sqlconnect.php');

session_start();

$link = mysqli_connect('localhost', 'root', 'Daka242347', 'yeticave');

get_sqllink_info($link);

$categories = get_lot_cat($link);

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

        $cat = mysqli_real_escape_string($link, $lot['cat_name']);
        $cat_check = mysqli_query($link, "SELECT id FROM category WHERE id = '$cat'");

        $row_cnt = mysqli_num_rows($cat_check);

        if($row_cnt === 0) {
            $errors['cat_name'] = 'Выберите категорию';
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

            $res = mysqli_prepare($link, $sql);

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
                show_sql_err($link);
            }
        }
    } else {
        $page_content = renderTemplate('templates/add-lot.php', ['categories' => $categories]);
    }


if (!isset($_SESSION['user'])) {
    header('HTTP/1.1 403 Forbidden');
    header('Status: 403 Forbidden');
    
    $page_content = 'Страница 403, доступ запрещен';
}

$layout_content = renderTemplate('templates/layout.php', [
    'title' => 'Добавление нового лота',
    'content' => $page_content,
    'categories' => $categories
]);

print($layout_content);

?>