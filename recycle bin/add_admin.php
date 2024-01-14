<?php include 'db.php' //include connect.php here  ?>
<!DOCTYPE html>
<html>

<head>
	<title>ADMIN SYSTEM</title>
	<link rel="stylesheet" href="default.css">
</head>

<body>
<?php
    require('db.php');
    // When form submitted, insert values into the database.
    if (isset($_REQUEST['username'])) {
        // removes backslashes
        $username = stripslashes($_REQUEST['username']);
        //escapes special characters in a string
        $username = mysqli_real_escape_string($mysqli, $username);
        $tel    = stripslashes($_REQUEST['tel']);
        $tel   = mysqli_real_escape_string($mysqli, $tel);
        $email    = stripslashes($_REQUEST['email']);
        $email    = mysqli_real_escape_string($mysqli, $email);
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($mysqli, $password);
        $create_datetime = date("Y-m-d H:i:s");
        
        
        $query    = "INSERT into `admins` (username, tel, password, email, create_datetime)
                     VALUES ('$username','$tel', '" . md5($password) . "', '$email', '$create_datetime')";
        $result   = mysqli_query($mysqli, $query);
           
            // Redirect to user dashboard page
        header("Location: admin.php");
        
        
    if ($result) {
        echo "<div class='form'>
            <h3>You are registered successfully.</h3><br/>
            <p class='link'>Click here to <a href='login.php'>Login</a></p>
            </div>";
    } else {
        echo "<div class='form'>
            <h3>Required fields are missing.</h3><br/>
            <p class='link'>Click here to <a href='registration.php'>registration</a> again.</p>
            </div>";
        }
    } else  {
	?>

	<div id="wrapper">
		<?php include 'header.php'; ?>
		<div id="div_main">
			<div id="div_left">
				

			</div>
			<div id="div_content" class="form">
				<!--%%%%% Main block %%%%-->
				<!--Form -->
				

				<form action="" method="post">
					
					<div>
					</div>

					

					<h2> Account Profile</h2>
					<label>Username</label>
					<input type="text" name="username">

					<label>Tel</label>
					<input type="text" name="tel">

					
					<label>Email</label>
					<input type="text" name="email">

					<label>Password</label>
					<input type="password" name="password">


					

					
					<div class="center">
					<input type="submit" name="submit" value="Submit" class="login-button">
					</div>
				</form>
			</div> <!-- end div_content -->

		</div> <!-- end div_main -->

		<div id="div_footer">

		</div>

	</div>
	<?php
    }
?>
</body>

</html>
