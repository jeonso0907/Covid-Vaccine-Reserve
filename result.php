<!DOCTYPE html>
<html>
<head>
	<title>Appointment Result</title>
</head>
<style>
	div#info {
		position: absolute;
		top: 10%;
		left: 30%;
	}
	div#dinfo {
		position: absolute;
		top: 10%;
		left: 60%;
	}
	div#butn {
		position: absolute;
		top: 50%;
		left: 40%;
	}
	div#error {
		position: absolute;
		top: 40%;
		left: 38%;
	}
	div#cancel {
		position: absolute;
		top: 50%;
		left: 55%;
	}
</style>
<body>
	<?php
		$con = new mysqli('localhost','root','mysql','test');
		if (!$con) {die('Cannot connect' .mysqli_connect_error());}

		$patient_iD = null;
		$valid_signin = true;
		$sign_again = false;

		if (!isset($_POST['patientid']) && isset($_COOKIE['patientID'])) {
			$patient_iD = $_COOKIE['patientID'];
		} else if (isset($_POST['patientid'])) {
			$patient_iD = $_POST['patientid'];
			$patient_fname = $_POST['fname'];
			$valid_patient = mysqli_query($con, "select patientid from patient where patientid = '". $patient_iD . "' and fname = '" . $patient_fname . "'");
			if ($valid_patient->num_rows == 0) {
				echo "<div id = 'error'>";
				echo "<h3>Wrong information, please try again</h3>";
				echo "</div>";
				$valid_signin = false;
			}
		}

		if ($valid_signin && (!isset($_POST['patientid']) || $valid_patient->num_rows != 0)) {
		
			$get_patientinfo = "select * from patient where patientid = '" . $patient_iD . "'";

			$patient_info = mysqli_query($con, $get_patientinfo);
			$patient = $patient_info->fetch_array();

			if (isset($patient['Fname'])) {
				echo "<div id = 'info'>";
				echo "<h3> Patient Information </h3> <br>";
				echo "Name				:	" . $patient['Fname'] . " " . $patient['Lname'] . "<br>";
				echo "Patient ID		:	" . $patient_iD . "<br>";
				echo "Age				:	" . $patient['Age'] . "<br>";
				echo "Earlist Date		:	" . $patient['Edate'] . "<br>";
				echo "Priority			:	" . $patient['Priority'] . "<br><br>";
				echo "</div>";
			}

			$get_doseinfo = "select * from doses right join appointments on (doses.doseid = appointments.doseid) where appointments.patientid = '" . $patient_iD . "'";
			$dose_info = mysqli_query($con, $get_doseinfo);
			$dose = $dose_info->fetch_array();

			echo "<div id = 'dinfo'>";
			if (isset($dose['DoseID'])) {
				
				echo "<h3> Dose Information </h3> <br>";
				echo "Dose ID			:	" . $dose['DoseID'] . "<br>";
				echo "Brand				:	" . $dose['Manufacture'] . "<br>";
				echo "Expiration Date	:	" . $dose['ExpDate'] . "<br>";
			} else if (isset($dose['AptResult'])) {
				echo "<h3> Waitlisted </h3> <br>";
			} else {
				echo "<h3> No Appointment </h3> <br>";
				$valid_signin = false;
				$sign_again = true;
			}
			echo "</div>";
		}
		setcookie("patientID", $patient_iD, time() + 60 * 10);
	?>

	<div id = "butn">
		<form method = "post" action = "signup.php">
			<input type = "submit" value = "signout"><br/>
		</form>
	</div>
	<?php 
		if ($valid_signin) {
			echo "<div id = 'cancel'>";
			echo "<form method = 'post' action = 'cancel.php'>";
			echo "<input type = 'submit' value = 'cancel'><br/>";
			echo "</form>";
			echo "</div>";
		}
		if ($sign_again) {
			echo "<div id = 'cancel'>";
			echo "<form method = 'post' action = 'cancel.php'>";
			echo "<input type = 'submit' value = 'New Appointment'><br/>";
			echo "</form>";
			echo "</div>";
		}
	?>



</body>
</html>