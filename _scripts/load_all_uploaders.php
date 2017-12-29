<?php
require_once('check.php');

checkUser($userdata);

$all_uploaders = array();

    $link = mysqli_connect("localhost", "faust", "123456", "blockdb");
    $query = mysqli_query($link, "SELECT * FROM files");
    if ($query->num_rows > 0)
    {
        // output data of each row
        while ($row = $query->fetch_assoc())
        {
            $query2 = mysql_query("SELECT * FROM users WHERE user_id='" . $row['user_id'] . "'");
            $author = mysql_fetch_assoc($query2);
            array_push($all_uploaders, array(
                "id" => $row["file_id"],
                "name" => $row["file_name"],
                "author" => $author['user_name']
                )
            );
        }
    }
?>