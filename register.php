<?php
require_once 'functions.php';

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
        $signup = $_POST['signup'];
        $required = ['email', 'user_name', 'password', 'contact'];
        $dict = [
        'email' => 'E-mail',
        'user_name' => 'Имя пользователя',
        'password' => 'Пароль',
        'contact' => 'Контакты',
        'file' => 'Аватар'];
        
    $errors = [];

        foreach ($required as $key) {
            if (empty($signup[$key])) {
                $errors[$key] = 'Это поле надо заполнить';
            }
        }
        $signup['user_foto'] = '';
        if  (($_FILES['user_foto']['name']) !== '') {
            $tmp_name = $_FILES['user_foto']['tmp_name'];
            $path = $_FILES['user_foto']['name'];
            $file_type = mime_content_type($tmp_name);
            if ($file_type == 'image/png' || $file_type == 'image/jpeg') {
                $filename = uniqid();
                $signup['user_foto'] = 'img/' . $filename . '.jpg';
                move_uploaded_file($tmp_name, $signup['user_foto']);
            } else {
                $errors['file'] = 'Загрузите картинку в формате PNG, JPG или JPEG';
            }
        }
        if (!filter_var($signup['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Введите корректный E-mail';
        }

        $email = mysqli_real_escape_string($link, $signup['email']);
        
        $sql_mail = "SELECT id FROM users WHERE email = '$email'";
        $result = mysqli_query($link, $sql_mail);
        if (mysqli_num_rows($result) > 0)  {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        }

        if (count($errors)) {
            $page_content = renderTemplate('templates/sign-up.php', ['signup' => $signup, 'errors' => $errors, 'dict' => $dict, 'categories' => $categories]);
        } else {

            $password = password_hash($signup['password'], PASSWORD_DEFAULT);
            $sql_user = 'INSERT INTO users (reg_date, email, user_name, password, user_foto, contact) VALUES (NOW(), ?, ?, ?, ?, ?)';
            
            $stmt = db_get_prepare_stmt($link, $sql_user, [$signup['email'], $signup['user_name'], $password, $signup['user_foto'], $signup['contact']]);
            
            $result = mysqli_stmt_execute($stmt);
            if ($result) {
                header("Location: index.php");

            } else {
                $sql_error = mysqli_error($link);
                $page_content = '';
                print('Ошибка базы данных: ' . $sql_error);
            }
        }
    } else {
        $page_content = renderTemplate('templates/sign-up.php', ['product_categories' => $product_categories]);
    }
}

$layout_content = renderTemplate('templates/layout.php', [
    'title' => 'Регистрация пользователя',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
    'content' => $page_content,
    'categories' => $categories
]);

print($layout_content);

?>