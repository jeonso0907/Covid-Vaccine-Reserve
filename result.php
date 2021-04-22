<!DOCTYPE html>
<html>
<head>
	<title>Appointment Result</title>
</head>
<body>
	<?php
		$con = new mysqli('localhost','root','mysql','bur');
		if (!$con) {die('Cannot connect' .mysqli_connect_error());}

		$patient_iD = null;

		if (!isset($_POST['patientid'])) {
			$patient_iD = $_COOKIE['patientID'];
		} else {
			$patient_iD = $_POST['patientid'];
			$patient_fname = $_POST['fname'];
			$valid_patient = mysqli_query($con, "select patientid from patient where patientid = '". $patient_iD . "' and fname = '" . $patient_fname . "'");
			if ($valid_patient->num_rows == 0) {
				echo "Wrong information, please try again";
			}
		}

		if (!isset($_POST['patientid']) || $valid_patient->num_rows != 0) {
		
			$get_patientinfo = "select * from patient where patientid = '" . $patient_iD . "'";

			$patient_info = mysqli_query($con, $get_patientinfo);
			$patient = $patient_info->fetch_array();

			if (isset($patient['Fname'])) {
				echo "<h3> Patient Information </h3> <br>";
				echo "Name				:	" . $patient['Fname'] . " " . $patient['Lname'] . "<br>";
				echo "Patient ID		:	" . $patient_iD . "<br>";
				echo "Age				:	" . $patient['Age'] . "<br>";
				echo "Earlist Date		:	" . $patient['Edate'] . "<br>";
				echo "Priority			:	" . $patient['Priority'] . "<br><br>";
			}

			$get_doseinfo = "select * from doses right join appointments on (doses.doseid = appointments.doseid) where appointments.patientid = '" . $patient_iD . "'";
			$dose_info = mysqli_query($con, $get_doseinfo);
			$dose = $dose_info->fetch_array();

			if (isset($dose['DoseID'])) {
				echo "<h3> Dose Information </h3> <br>";
				echo "Dose ID			:	" . $dose['DoseID'] . "<br>";
				echo "Batch ID			:	" . $dose['BatchID'] . "<br>";
				echo "Brand				:	" . $dose['Manufacture'] . "<br>";
				echo "Expiration Date	:	" . $dose['ExpDate'] . "<br>";
			} else {
				echo "<h3> Waitlisted </h3> <br>";
			}
		}
		setcookie("patientID", $patient_iD, time() + 60 * 1);
	?>

	<form method = "post" action = "signup.php">
		<input type = "submit" value = "signout"><br/>
	</form>

	<form method = "post" action = "cancel.php">
		<input type = "submit" value = "cancel"><br/>
	</form>


</body>
</html>