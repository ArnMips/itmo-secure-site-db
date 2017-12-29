
<!DOCTYPE html>
<html>
<head>
    
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Login Form</title>

	<link rel="stylesheet" href="_style/demo.css">
    <script src="https://www.google.com/recaptcha/api.js"  defer></script>
</head>
	<header>
		<h1>Welcome to Alexander Alexandrovich Blok library</a>
    </header>

    <ul>  </ul>


    <div class="main-content">

        <form class="form-login" id="auth-form" action="_scripts/login.php" method="POST">

            <div class="form-log-in-with-email">

                <div class="form-white-background">

                    <div class="form-title-row">
                        <h1>Log in</h1>
                    </div>

                    <div class="form-row">
                        <?php echo "<p>".htmlentities($_GET["login-message"], ENT_QUOTES, "UTF-8")."</p>"; ?>
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
                            <input type="password" name="password" required>
                        </label>
                    </div>

                    <div class="form-row">
                        <button type="submit" class="g-recaptcha" data-sitekey="6Ld-Wz4UAAAAAC9O_1ay5ejaKEsn4uKxvdLnK8tD" data-callback='onSubmit'>Log in</button>
                    </div>

                </div>

                <a> No account? &middot; </a>
                <a href="form_register.php" class="form-create-an-account">Create an account &rarr;</a>

            </div>

        </form>

    </div>
     <script>
       function onSubmit(token) {
         document.getElementById("auth-form").submit();
         console.log('Invis test passed');
       }
     </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" defer></script>
</body>

</html>
