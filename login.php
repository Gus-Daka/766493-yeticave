<?php
require_once('functions.php');
require_once('data.php');

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
        $login = $_POST['login'];
        $required = ['email', 'password'];
        $dict = [
            'email' => 'E-mail', 
            'password' => 'Пароль'
        ];
        
        $errors = [];
        
        foreach ($required as $key) {
            if (empty($login[$key])) {
                $errors[$key] = 'Это поле надо заполнить';
            }
        }

        $email = mysqli_real_escape_string($link, $login['email']);
        
        $sql_email = "SELECT email, password, user_foto, user_name FROM users WHERE email = '$email'";
        
        $result = mysqli_query($link, $sql_email);
        
        $user = $result ? mysqli_fetch_array($result, MYSQLI_ASSOC) : null;
        
        if (!filter_var($login['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Введите корректный E-mail';
        }
        
        if (!count($errors) && $user) {
            if (password_verify($login['password'], $user['password'])) {
                $_SESSION['user'] = $user;
            } else {
                $errors['password'] = 'Вы ввели неверный пароль';
            }
        } else {
            $errors['email'] = 'Пользователь не найден';
        }
        if (count($errors)) {
            $page_content = renderTemplate('templates/login.php', ['login' => $login,
                'errors' => $errors, 'dict' => $dict, 'categories' => $categories]);
        } else {
            header("Location: index.php");
        }
    }
    else {
        if (isset($_SESSION['user'])) {
            header("Location: index.php");
        }
        else {
            $page_content = renderTemplate('templates/login.php', ['categories' => $categories]);
        }
    }
}

$layout_content = renderTemplate('templates/layout.php', [
    'content' => $page_content,
    'title' => 'Вход',
    'categories' => $categories
]);

print($layout_content);

?>