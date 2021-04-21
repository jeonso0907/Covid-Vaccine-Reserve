<!DOCTYPE html>
<html>
<head>
	<title>Patient Signup</title>
</head>
<body>
	<h1>Insert your information</h1>
	<?php
		$con = new mysqli('localhost','root','mysql','mydb');
		if (!$con) {die('Cannot connect' .mysqli_connect_error());}
	?>
	<form method = "POST" action = "appointment.php">
		First Name:	<input type = "text" name = "fname"/><br/>
		Last Name :	<input type = "text" name = "lname"/><br/>
		Age:	<input type = "number" name = "age"/><br/>
		Phone:	<input type = "number" name = "number"/><br/>
		Earlist Date:	<input type = "date" name = "edate"/><br/>
		<input type = "submit" value = "Submit" name = "submit"/><br/>
	</form>
	<form method ="GET" action = "signup.php">
		<input type = "submit" value = "Back" name = "signin"/>
	</form>
</body>
</html>