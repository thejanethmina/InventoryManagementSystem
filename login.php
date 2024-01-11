<?php

// check if the user is already logged in , redirect to the dashboard page
session_start();

 if(isset($_SESSION['email']))
 {
    header('Location: dashboard.php');
 }


?>


<!DOCTYPE html>
<html>
<head>
	<title>Inventory System - Login</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<style>

        *{ box-sizing:border-box; }

		body {
			background-color: #f2f2f2;
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		}
		.container {
			margin: 100px auto;
			max-width: 400px;
			padding: 30px;
			background-color: #fff;
			border-radius: 10px;
			box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
		}
		h1 {
			text-align: center;
			margin-bottom: 20px;
			color: #333;
		}
		form {
			display: flex;
			flex-direction: column;
			align-items: center;
		}
		input[type="email"], input[type="password"] {
			width: 100%;
			padding: 10px;
			margin-bottom: 20px;
			border: none;
			border-radius: 5px;
			background-color: #f2f2f2;
			color: #333;
			font-size: 16px;
			box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
			transition: box-shadow 0.3s ease-in-out;
			outline:none;
		}
		input[type="text"]:focus, input[type="password"]:focus {
			box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
		}
		button[type="submit"] {
			padding: 10px;
			border: none;
			border-radius: 5px;
			background-color: #333;
			color: #fff;
			font-size: 16px;
			font-weight: bold;
			box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
			cursor: pointer;
			transition: background-color 0.3s ease-in-out;
			width:100%;
		}
		button[type="submit"]:hover {
			background-color: #444;
		}

		.error {
			color: #ff0000;
			font-size: 14px;
			margin-bottom: 10px;
			text-align: center;
		}




/* ////////////////////////////////// */

.alert{ padding: 20px; border-radius: 5px; text-align: center; position: relative;
    width: 100%; margin: 0 auto; color: #333; font-size: 16px; margin-bottom: 15px; }

.alert.error{ background-color: #ffe0e0; color: #d8000c; border: 1px solid #d8000c; }

.alert.success{ background-color: #e6ffe6; color: #006600; border: 1px solid #006600;  }

.close-alert{ position: absolute; top: 5px; right: 5px; cursor: pointer; font-size: 24px;
          padding: 1px 4px; transition: color 0.2s ease-in-out; }

.close-alert:hover{ color:#fff; }


    </style>
</head>
<body>

	<div class="container">

<?php

// display errors
if(isset($_SESSION['error'])){
        echo '<div class="alert error"><p>'.$_SESSION['error'].'</p>
              <span class="close-alert">&times;</span></div>';
}

unset($_SESSION['error']);

?>


		<h1><i class="fas fa-box-open"></i> Inventory System</h1>
		<form method="POST" action="login-script.php">
			<input type="email" name="email" placeholder="Email" required>
			<input type="password" name="password" placeholder="Password" required>
			<button type="submit" name="login">Login</button>	
		</form>
    </div>



<script src="js/close-msg.js"></script>



</body>
</html>
