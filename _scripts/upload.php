<?php
include 'check.php';

checkUser($userdata);
    
$target_dir = "../_uploads/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);
$uploadOk = 1;
$message;

// Check if  file is a actual file or fake file
if(isset($_POST['submit_upload'])) {
    if($_FILES['file']['type'] == 'text/plain') {
        echo "File is a text/plain<br>";
        $uploadOk = 1;
    } else {
        $message = "File is not text/plain<br>";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    $message = "Sorry, file already exists.<br>";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["file"]["size"] > 500000) {
    $message = "Sorry, your file is too large.<br>";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    $message = "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $message = "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.<br>";

        $link = mysql_connect("localhost", "faust", "123456");
        if (!$link) {
            $message = 'Ошибка соединения: ' . mysql_error();
            return;
        }
        $db_selected = mysql_select_db("blockdb");
        if (!$db_selected) {
            $message = 'Не удалось выбрать базу foo: ' . mysql_error();
            return;
        }
        mysql_query("INSERT INTO files SET file_name='".$_FILES["file"]["name"]."', user_id='".$userdata[user_id]."'");
        //header("Refresh:4; url=../uploud_file.php"); exit();
        
    } else {
        $message = "Sorry, there was an error uploading your file.";
    }
}

header("Location: ../uploud_file.php?upload-message=".$message); exit();
?>