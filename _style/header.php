<?php

$csp = "Content-Security-Policy: "
       . "default-src 'self';";
header($csp);


//0 - Регистрация /авторизация
function show_header($acive_page_number)
{
    include '/../_scripts/check.php';
    checkUser($userdata);


    echo '<!DOCTYPE html>
          <html>
          <head>
          
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <title>Blok A.A. - archive</title>
            <link rel="stylesheet" href="/../_style/demo.css">
          </head>
          <body>
          <header>
                <h1>Hello, '.$userdata["user_name"].'!</h1>
                <a href="/../_scripts/exit.php">Exit</a>
            </header>
            <ul>
                <li><a href="/../user_profile.php" '. ($acive_page_number==1 ? 'class="active"' : '' ).'>My Profile</a></li>
                <li><a href="/../all_upload_files.php" '. ($acive_page_number==2 ? 'class="active"' : '' ).'>All files</a></li>
                <li><a href="/../uploud_file.php" '.($acive_page_number==3 ? 'class="active"' : '' ).'>Upload file</a></li>
            </ul>
            <div class="main-content-pages">
        ';

    }
?>
            
            
            
        