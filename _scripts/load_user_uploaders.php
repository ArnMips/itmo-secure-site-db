<?php
require_once('check.php');

checkUser($userdata);

$user_uploaders = array();

    $link = mysqli_connect("localhost", "faust", "123456", "blockdb");
    $query = mysqli_query($link, "SELECT * FROM files WHERE user_id =".$userdata['user_id']);
    if ($query->num_rows > 0) {
        // output data of each row
        while ($row = $query->fetch_assoc()) {
            array_push($user_uploaders, array(
                "id" => $row["file_id"],
                "name" => $row["file_name"],
                )
            );
        }
    }
?>