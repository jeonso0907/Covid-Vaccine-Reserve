<!DOCTYPE html>
<html>
<head>
	<title>Patient Signup or Signin</title>
</head>
<body>
	<form method = "POST" action = "result.php">
		<h1>SignIn</h1>
		Patient ID : <input type = "text" name = "patientid"></br>
		First Name : <input type = "text" name = "fname"></br>
		<input type = "submit" value = "Signin"></br>
	</form>

	<form method = "GET" action = "patient.php">
		<h1>SignUp</h1>
		<input type = "submit" value = "Signup"></br>
	</form>

	<h2>Report or Add Dose</h2>
	<form method = "get" action = "dose.php">
		<input type = "submit" value = "Dose Edit"></br>
	</form>

	<form method = "get" action = "report.php">
		<input type = "submit" value = "Patient Report"></br>
	</form>
</body>
</html>