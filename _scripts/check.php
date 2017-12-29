<?php
// Скрипт проверки. 
// в случае успешного подтверждения пользователя 
//      is_ok==ture, userdata==текущий user есд 
// в случае неуспешного 
//      is_ok==false
function checkUser(&$userdata, &$error = "")
{
    $err = array();

    if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {   
        $cookie_id = $_COOKIE['id'];      
        $cookie_hash = $_COOKIE['hash'];

        if (!mysql_connect("localhost", "faust", "123456")) {
            $err[] = "Ошибка соединения";
        }
        if (!mysql_select_db("blockdb")) {
            $err[] = "Не удалось выбрать базу foo: ".mysql_error();      
        }   

        if(!preg_match("/^[a-zA-Z0-9]+$/",$cookie_hash)
        || !preg_match("/^[0-9]+$/",$cookie_id)) {
        $err[] = "Куки имеют неверный формат";
        }

        # Если нет ошибок, начинаем работать с БД
        if(count($err) == 0) {
            $query = mysql_query("SELECT * FROM users WHERE user_id = '".intval($_COOKIE['id'])."' LIMIT 1");
            $userdata = mysql_fetch_assoc($query);
            if(($userdata['user_hash'] !== $cookie_hash) or ($userdata['user_id'] !== $cookie_id)) {
                setcookie("id", "", 0, '/');
                setcookie("hash", "", 0, '/');
                header("Location: ../index.php"); exit();
            }
            #Все ОК
            return;
        }
    }
    #Если все проверки неудачны
    header("Location: ../index.php");  exit();
}

//Считывает переданный g_recaptcha_response запрос с данными
// если все ОК, возвращает true
function checkCapcha_register($g_recaptcha_response) 
{
    if (!$g_recaptcha_response) return false;

    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $key = '6LfaUD4UAAAAAJnd4Nr7pfVYIAgF56BErN_dR24c';
    $query = $url.'?secret='.$key.'&response='.$g_recaptcha_response.'&remoteip='.$_SERVER['REMOTE_ADDR'];
    
    //Получаем ответ  от сервера Google и Преобразуем ответ из формата json 
    //Внимание, если появятся ощшибки, заглянуть в '_add/Unable to find the wrapper “https”'
    $data = json_decode(file_get_contents($query));

    //Возвращаем ответ 
    return $data->success;
}

//Считывает переданный g_recaptcha_response запрос с данными
// если все ОК, возвращает true
function checkCapcha_login($g_recaptcha_response) 
{
    if (!$g_recaptcha_response) return false;

    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $key = '6Ld-Wz4UAAAAACuPGWa3sRctpZ-nZZAAXQZSP28U';
    $query = $url.'?secret='.$key.'&response='.$g_recaptcha_response.'&remoteip='.$_SERVER['REMOTE_ADDR'];
    
    //Получаем ответ  от сервера Google и Преобразуем ответ из формата json 
    //Внимание, если появятся ощшибки, заглянуть в '_add/Unable to find the wrapper “https”'
    $data = json_decode(file_get_contents($query));



    //Возвращаем ответ 
    return $data->success;
}

function isPasswordHard($password){
    $f = fopen(__DIR__."\..\_add\popular_password_100000.txt","r");
    if ($f) {
        while(!feof($f)) { 
            $str = trim(fgets($f));
            if($str && stristr($password, $str)){
                return false;
            }
	    }
        return true;
    }
    return false;
}

?>