<!DOCTYPE html>
<html>
<head>
	<title>Patient Signup or Signin</title>
</head>
<style>
	div#signin_or_up {
		position: absolute;
		text-align: center;
		top: 20%;
		left: 25%;
		width: 300px; height: 240px;
		border: 1px solid gray;
	}
</style>
<style>
	div#edit_and_report {
		position: absolute;
		text-align: center;
		top: 20%;
		left: 25%;
		margin-left: 310px;
		width: 300px; height: 240px;
		border: 1px solid gray;
	}
</style>
<body>
		<div style = "text-align: center;">
			<h1>BUR Covid Vaccine</h1>
		</div>

		<div id = "signin_or_up">
			<form method = "POST" action = "result.php">
				<h3>SignIn</h3>
				Patient ID : <input type = "text" name = "patientid"></br>
				First Name : <input type = "text" name = "fname"></br>
				<input type = "submit" value = "Signin"></br>
			</form>

			<form method = "GET" action = "patient.php">
				<h3>SignUp</h3>
				<input type = "submit" value = "Signup"></br>
			</form>
		</div>

		<div id = "edit_and_report">
			<h3>Edit Dose</h3>
			<form method = "get" action = "dose.php">
				<input type = "submit" value = "Dose Edit"></br>
			</form>

			<h3>Reports</h3>
			<form method = "get" action = "dose_report.php">
				<input type = "submit" value = "Dose Report"></br>
			</form>

			<form method = "get" action = "report.php">
				<input type = "submit" value = "Patient Report"></br>
			</form>
		</div>


</body>
</html>