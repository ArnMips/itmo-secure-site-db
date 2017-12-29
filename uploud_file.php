<?php require_once('_style/header.php'); show_header(3); ?>

<h1>Upload new file</h1>

<form class="form-load" action='_scripts/upload.php' method='post' enctype='multipart/form-data'> 
    <input type='file' name='file'>
    <button type="submit" name="submit_upload">Upload</button>
    <?php echo htmlentities($_GET["upload-message"], ENT_QUOTES, "UTF-8"); ?>
</form>

<?php require_once('_style/footer.php'); ?>