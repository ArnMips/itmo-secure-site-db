
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Registration Form</title>

	<link rel="stylesheet" href="_style/demo.css">
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>

	<header>
		<h1>Welcome to Alexander Alexandrovich Blok library</h1>
    </header>

    <ul>    </ul>


    <div class="main-content">

        <form class="form-register" action="_scripts/register.php" method="POST" >

            <div class="form-register-with-email">

                <div class="form-white-background">

                    <div class="form-title-row">
                        <h1>Create an account</h1>
                    </div>

                    <div class="form-row">
                        <?php echo "<p>".htmlentities($_GET["register-message"], ENT_QUOTES, "UTF-8")."</p>"; ?>
                    </div>

                    <div class="form-row">
                        <label>
                            <span>Name</span>
                            <input type="text" name="name" required>
                        </label>
                    </div>

                    <div class="form-row">
                        <label>
                            <span>Login</span>
                            <input type="text" name="login" required>
                        </label>
                    </div>

                    <div class="form-row">
                        <label>
                            <span>Password</span>
                            <input type="password" name="password">
                        </label>
                    </div>

                    <div class="form-row" >
                        <div class="g-recaptcha" data-sitekey="6LfaUD4UAAAAAFRb2QAPbTVH317_jHn_jSIZbru_" 
                        style="transform:scale(0.77);-webkit-transform:scale(0.77);transform-origin:0 0;-webkit-transform-origin:0 0;"
                        ></div>
                    </div>

                    <div class="form-row">
                        <button type="submit" name="submit_register">Register</button>
                    </div>

                </div>
                <a> Already have an account? &middot; </a>
                <a href="index.php" class="form-log-in-with-existing">Login here &rarr;</a>

            </div>


        </form>

    </div>
<?php
$f = fopen(__DIR__."\_add\popular_password_100000.txt","r");
$password = "123456";
    if ($f) {
        while(!feof($f)) { 
            $str = trim(fgets($f));
            if($str && stristr($password, $str)){
                echo "NO";
            }
	    }
        echo "YES";
    }
    echo "NO";
        ?>
</body>

</html>
