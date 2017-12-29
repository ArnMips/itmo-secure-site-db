<?php
    unset($_COOKIE['hash']);
    unset($_COOKIE['id']);
    setcookie('hash', null, -1, '/');
    setcookie('id', null, -1, '/');
    header("Location: ../index.php");
    exit();
?>