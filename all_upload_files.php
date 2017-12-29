<?php require_once('_style/header.php'); show_header(2); ?>

<h1>All your uploaded files</h1>


<?php 
    require_once('_scripts/load_all_uploaders.php');
    
    if (count($all_uploaders) > 0) {

    echo "<table cellspacing='0'> 
            <thead><tr> 
                <th>File ID</th> 
                <th>File Name</th> 
                <th>Author</th> 
            </tr></thead>
            
            <tbody>";

    foreach ($all_uploaders as &$file) {
        echo "<tr >
              <td>" . $file['id'] . "</td>
              <td><a href='/_uploads/" . $file['name'] . "' target='_blank'>" . $file['name'] . "</td>
              <td>" . $file['author'] . "</td>
              </tr>";
    }
    echo "</tbody></table>";
    } else {
        echo "<br>0 results";
    }
 ?>
 
<?php require_once('_style/footer.php'); ?>