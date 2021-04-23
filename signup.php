<!DOCTYPE html>
<html>
<head>
	<title>Patient Signup or Signin</title>
</head>
<body>
	<form method = "POST" action = "result.php">
		<h1>Signin</h1>
		Patient ID : <input type = "text" name = "patientid"></br>
		First Name : <input type = "text" name = "fname"></br>
		<input type = "submit" value = "Signin"></br>
	</form>

	<form method = "GET" action = "patient.php">
		<input type = "submit" value = "Signup"></br>
	</form>

	<form method = "get" action = "dose.php">
		<input type = "submit" value = "Dose Edit"></br>
	</form>
</body>
</html>