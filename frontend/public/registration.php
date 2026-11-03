<?php
/* registration handler for webapp 
   @TODO format page so that user can enter information to signup, alert when account successfully created
   @TODO add redirect to login page when signup is complete
   @TODO send info to RMQ then recieve confirmation
*/
?>
<html>
<head>
	 <meta charset="UTF-8">
	 <title>Register</title>
	 <link rel="stylesheet" href="css/login.css">
</head>
<body>
	<h1>REGISTRATION</h1>
	<label>Username</label>
	<input id="username" type="text" required></input> <br>

	<label>Email</label>
	<input id="email" type="text" required></input> <br>
	
	<label>Password</label> 
	<input id="password"  type="password" required></input> <br>
	
	<label>Confirm Password</label>
	<input id="confirm" type="password" required></input> <br>
	
	<button id="register" type="submit">REGISTER</input> <br>
	<a id="nav" href="index.html">Log In</a>
<body>
</html>

<?php
/*
* @PARAM username, email, password, confirm from form submission
*/
if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm'])) {
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$confirm = $_POST['confirm'];
	
	// @TODO validation and sanitization of inputted information
	$error = false;
	switch(true) {
		case empty($username):
			$error = true;
			$err_msg = "Please enter a username.";
		break;
		case empty($email):
			$error = true;
			$err_msg = "Please enter a valid email";
		break;
		case empty($password):
			$error = true;
			$err_msg = "Password cannot be empty";
		break;
		case (empty($confirm) || ($confirm !== $password)):
			$error = true;
			$err_msg = "Passwords do not match.";
		break;
		// @TODO password and email checking for format (regex) 
	}
	
	// error checking and giving the user a message
	if (!$error) {
	// @TODO make rabbitMQ request to DB here
		$req = new DBRequests();
		$req->register($username, $email, $password);
	// @TODO send alert that RMQ request was successful
	}
	else {
		echo '<script language="javascript">';
		echo '<alert("' . $err_msg . '")';
		echo '</script>';
	}	
}
?>
