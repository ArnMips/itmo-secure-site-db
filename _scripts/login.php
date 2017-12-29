<?php
require 'check.php';

if (!checkCapcha_login($_POST['g-recaptcha-response'])){
    $message = "Вы робот?";
    header("Location: ../index.php?login-message=".$message); exit();
}

# Функция для генерации случайной строки
function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;  
    while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];  
    }
    return $code;
}
header("Location: ../index.php?login-message=".$message);

if(isset($_POST['login']) && isset($_POST['password']))
{
    $err = array();
    $login = $_POST['login'];      
    $password = $_POST['password'];

    # Соединямся с БД
    if (!mysql_connect("localhost", "faust", "123456")) {
        $err[] = "Ошибка соединения";
    }
    if (!mysql_select_db("blockdb")) {
        $err[] = "Не удалось выбрать базу foo: ".mysql_error();      
    }

    if(!preg_match("/^[a-zA-Z0-9]+$/",$login)
        || strlen($login) > 30
        || strlen($password) < 3 or strlen($password) > 30) {
        $err[] = "Вы ввели неправильный логин/пароль";
    }

    # Если нет ошибок, начинаем работать с БД
    if(count($err) == 0) {  
        # Вытаскиваем из БД запись, у которой логин равняеться введенному
        $query = mysql_query("SELECT user_id, user_password FROM users WHERE user_login='".$login."' LIMIT 1");
        $data = mysql_fetch_assoc($query);

        # Соавниваем пароли
        if($data['user_password'] === md5(md5($password)))
        {
            # Генерируем случайное число и шифруем его
            $hash = md5(generateCode(10));            

            # Записываем в БД новый хеш авторизации и IP
            mysql_query("UPDATE users SET user_hash='".$hash."'"." WHERE user_id='".$data['user_id']."'");

            # Ставим куки
            setcookie("id", $data['user_id'], 0, '/');
            setcookie("hash", $hash, 0, '/');

            # Переадресовываем браузер на страницу профия
            header("Location: ../user_profile.php"); exit();
        }
        else
        {
            $message = "Вы ввели неправильный логин/пароль";
            header("Location: ../index.php?login-message=".$message); exit();
        }
    }
    else{
        header("Location: ../index.php?login-message=".$err[0]); exit();
    }
}
header("Location: ../index.php"); exit();
?>
