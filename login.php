<?php session_start(); 	

    $msg = ['username' => 'password', 'username1' => 'password1','username2' => 'password2'];
	if(isset($_POST['Submit'])){
		$logins = array('Mantas' => '123456', 'username1' => 'password1','username2' => 'password2');
		$Username = isset($_POST['Username']) ? $_POST['Username'] : '';
		$Password = isset($_POST['Password']) ? $_POST['Password'] : '';		
		if (isset($logins[$Username]) && $logins[$Username] == $Password){
			$_SESSION['UserData']['Username']=$Username;
			$_SESSION['UserData']['Password']=$Password;
			header("location:index.php");
			// header("location:index.php?refresh=true");
			// reiketu pasijungti htaccess ir nerodytu paskutinio failo pavadinimo
            // RewriteEngine On
            // RewriteBase /
            
			exit;
		} else {
			$msg['username'] = 'Wrong Username or Password';
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    
    <style><?php include 'css/login.css' ?></style>

    <title>Admin Area</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-8 login-box">
                <div class="col-lg-12 login-key">
                    <i class="fa fa-key" aria-hidden="true"></i>
                </div>
                <div class="col-lg-12 login-title">
                    LOG ME IN
                </div>
                    <div class="col-lg-12 login-form">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" name="Login_Form">
                            <div class="form-group">
                                <label class="form-control-label">USERNAME</label>
                                <input name="Username" type="text" class="form-control" placeholder="Mantas">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">PASSWORD</label>
                                <input name="Password" type="password" class="form-control" placeholder="123456">
                            </div>

                            <div class="col-lg-12 loginbttm">
                            <?php if (isset($msg['username']) && $msg['username'] !== 'password'){?>
                                <div class="col-lg-6 login-btm login-text">
                                    <?php echo $msg['username']?>
                                </div>
                                <?php } ?>
                                <div class="col-lg-12 login-btm login-button">
                                    <button name="Submit" type="submit" value="Login" class="btn btn-outline-primary">LOGIN</button>
                                </div>
                            </div>
                        </form>
                    </div>

                <div class="col-lg-3 col-md-2"></div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>

    
</body>
</html>