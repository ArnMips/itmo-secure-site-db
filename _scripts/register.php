<?php
require 'check.php';

// Страница регистрации нового пользователя
if (!checkCapcha_register($_POST['g-recaptcha-response'])){
    $message = "Вы робот?";
    header("Location: ../form_register.php?register-message=".$message); exit();
}

if(isset($_POST['submit_register'])) {
    $err = array();

    $login = $_POST['login'];      
    $name = $_POST['name'];
    $password = $_POST['password'];

    # Соединямся с БД
    if (!mysql_connect("localhost", "faust", "123456")) {
        $err[] = "Ошибка соединения";
    }
    if (!mysql_select_db("blockdb")) {
        $err[] = "Не удалось выбрать базу foo: ".mysql_error();      
    }
    # проверям логин, пароль, имя
    if(!preg_match("/^[a-zA-Z0-9]+$/",$login)) {
        $err[] = "Логин может состоять только из букв английского алфавита и цифр";
    }
    if(strlen($login) < 3 or strlen($login) > 30) {
        $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
    }
    if(!preg_match("/^[a-zA-Z0-9]+$/",$name)) {
        $err[] = "Имя может состоять только из букв английского алфавита и цифр";
    }
    if(strlen($name) > 30) {
        $err[] = "Имя должено быть не больше 30 символов";
    }
    if(strlen($password) < 3 or strlen($password) > 30) {
        $err[] = "Пароль должен быть не меньше 6-х символов и не больше 32";
    }
    if(!preg_match("/^[a-zA-Z0-9]+.*$/",$password)) {                          // <- ! Не работает !
        $err[] = "Пароль должен содержать буквы разного регистра, цифры";
    }
    if(!isPasswordHard($password) || stristr($password, $name) || stristr($password, $login)) {
        $err[] = "Пароль слишком простой";
    }
    
    
    # Если нет ошибок, начинаем работать с БД
    if(count($err) == 0) {       
        # проверяем, не сущестует ли пользователя с таким именем
        $query = mysql_query("SELECT COUNT(user_id) FROM users WHERE user_login='".$login."'");
        if(mysql_result($query, 0) > 0) {
            $err[] = "Пользователь с таким логином уже существует";
            header("Location: ../form_register.php?register-message=".$err[0]); exit();
        }

        # Убераем лишние пробелы и делаем двойное шифрование
        $password = md5(md5(trim($password)));

        mysql_query("INSERT INTO users SET user_login='".$login."', user_password='".$password."', user_name='".$name."'");
        header("Location: ../index.php"); exit();
    }
    else {
        header("Location: ../form_register.php?register-message=".$err[0]); exit();
    }
}

header("Location: ../form_register.php"); exit();
?>
