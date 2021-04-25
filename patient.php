<!DOCTYPE html>
<html>
<head>
	<title>Patient Signup</title>
</head>
<style>
	.container {
 		width: 100px;
 		clear: both;
	}

	.container input {
  		width: 200%;
  		clear: both;
		margin-bottom: 15px;
	}

	div#type {
		position: absolute;
		top: 20%;
		left: 41%;
	}
	div#back {
		position: absolute;
		left: 20%;
	}
</style>
<body>
	<div style = "text-align: center;">
		<h1>Insert your information</h1>
	</div>
	<div id = "type">
		<div class = "container">
			<form method = "POST" action = "appointment.php">
				First Name:	<input type = "text" name = "fname"/><br>
				Last Name:	<input type = "text" name = "lname"/><br/>
				Age:	<input type = "number" name = "age"/><br/>
				Phone:	<input type = "number" name = "number"/><br/>
				Earliest Date:	<input type = "date" name = "edate"/><br/>
				Priority:	<input type = "number" name = "priority"/><br/>
				<input type = "submit" value = "Submit" name = "submit"/><br/>
			</form>
		</div>
	</div>
	<div id = "back">
		<form method ="GET" action = "signup.php">
			<input type = "submit" value = "Back" name = "signin"/>
		</form>
	</div>
</body>
</html>