<!DOCTYPE html>
<html>
<head>
	<title>Cancel Appointment</title>
</head>
<body>

	<?php
		$con = new mysqli('localhost','root','mysql','bur');
		if (!$con) {die('Cannot connect' .mysqli_connect_error());}

		$patient_id = $_COOKIE['patientID'];
		$get_info = "select * from appointments where patientid = '" . $patient_id . "'";
		$patient_apt = mysqli_query($con, $get_info);
		$apt = $patient_apt->fetch_array();

		if (isset($apt['PatientID'])) {
			$cancel = "delete from appointments where patientid = '" . $patient_id . "'";
			mysqli_query($con, $cancel);
			if (isset($apt['DoseID'])) {
				$restore_dose = "update doses set status = 'valid' where doseid = " . $apt['DoseID'];
				mysqli_query($con, $restore_dose);
			}
			echo "Appointment canceled";
		}
	?>
	<form method = "POST" action = "signup.php">
		<input type = "submit" value = "signout"><br/>
	</form>

</body>
</html>