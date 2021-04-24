<!DOCTYPE html>
<html>
<head>
	<title>Appointment Result</title>
</head>
<body>

	<?php
		$con = new mysqli('localhost','root','mysql','bur');
		if (!$con) {die('Cannot connect' .mysqli_connect_error());}

		// Generate the patient's id by the count of the current same last names
		$lname = $_POST['lname'];
		$curr_patient = mysqli_query($con, "select count(lname) from patient where lname = '" . $lname . "'");
		$lname_count = $curr_patient->fetch_array();

		$patient_id = $lname . ".1";
		if ($lname_count[0] > 0) {
			$patient_id = $lname . "." . $lname_count[0] + 1;
		}

		$insert = "INSERT INTO patient (PatientID, Fname, Lname, Phone, Age, Priority, Edate)"
			. " VALUES ('" . $patient_id . "', '" .$_POST['fname']. "','" .$_POST['lname'] ."'," .$_POST['number']. "," .$_POST['age']. ", " 
			. $_POST['priority'] . ", '" .$_POST['edate']. "')";
		$result = mysqli_query($con, $insert);
		if ($result) {
			echo "Sign up successed <br>";
		} else {
			echo "Sign up failed <br>";
		}

		// Check the available dose and display the result (waitlist or appointment)
		$dose = mysqli_query($con, "select BatchID, DoseID, count(DoseID), manufacture, expdate, status from doses where status = 'valid' and ExpDate >= '" . $_POST['edate'] 
								. "' order by expdate");
		$available_dose = $dose->fetch_array();

		$result = "";
		if ($available_dose[2] > 0) {
			echo "Appointment reserved <br>";
			$result = "insert into appointments (PatientID, AptResult, BatchID, DoseID, Date) values ('" . $patient_id . "', 'reserved', " . $available_dose[0]
						. ", '" . $available_dose[1] . "', '" . $_POST['edate'] . "')";
			$update = "update doses set status = 'used' where DoseID = '" . $available_dose[1] . "'";
			mysqli_query($con, $update);
		} else {
			echo "Watilisted <br>";
			$result = "insert into appointments (PatientID, AptResult) values ('" . $patient_id . "', 'waitlist')";
		}
		$ap_result = mysqli_query($con, $result);

		if ($ap_result) {
			echo "Data stored successed <br>";
		}
		setcookie("patientID", $patient_id, time() + 60 * 1);
	?>
	<form method = "POST" action = "result.php">
		<input type = "submit" value = "Next">
	</form>
</body>
</html>