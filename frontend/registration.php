#!/usr/bin/php

<?php
/* registration page for creating new users, confirms successful
   registration once info is parsed into database */

/* Take the information entered by the user, validate that information w/ regex before sending the rabbitmq request to send it to the database*/

?>
<html>
	<body>
		<h1 style"=text-align: center;">REGISTRATION</h1>
		<label>Username</label> <input type="text">EX: jsmith21</input> <br>
		<label>Email</label> <input type="text">EX: jsmith@mail.com</input> <br>
		<label>Password</label> <input type="password">EX: pass1234</input> <br>
		<label>Confirm Password</label> <input type="password"></input>
	<body>
</html>
