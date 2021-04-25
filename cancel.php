<!DOCTYPE html>
<html>
<head>
	<title>Cancel Appointment</title>
</head>
<style>
	div#cancel {
		position: absolute;
		text-align: center;
		top: 50%;
		left: 50%;
		margin-left: -120px;
		margin-top: -150px;
		width: 300px; height: 240px;
	}
</style>
<body>

	<?php
		$con = new mysqli('localhost','root','mysql','test');
		if (!$con) {die('Cannot connect' .mysqli_connect_error());}

		$patient_id = $_COOKIE['patientID'];
		$get_info = "select * from appointments where patientid = '" . $patient_id . "'";
		$patient_apt = mysqli_query($con, $get_info);
		$apt = $patient_apt->fetch_array();
		$valid_cancel = $apt['Date'] >= '2021-03-20';

		if (isset($apt['PatientID']) && $valid_cancel) {
			$cancel = "delete from appointments where patientid = '" . $patient_id . "'";
			mysqli_query($con, $cancel);
			if (isset($apt['DoseID'])) {
				$status = 'valid';
			}
			echo "<div id = 'cancel'>";
			echo "Appointment canceled<br>";
			echo "</div>";
		}

		$get_waitlist = mysqli_query($con, "select appointments.PatientID, age, Priority, Edate from appointments left join patient on "
											. "(appointments.patientid = patient.patientid) where AptResult = 'waitlist' order by priority ASC, age DESC");
		$get_currdose = mysqli_query($con, "select * from doses where doseid = '" . $apt['DoseID'] . "'");
		$curr_dose = $get_currdose->fetch_array();

		if ($get_waitlist && $valid_cancel) {
			while ($waitlist = $get_waitlist->fetch_array()) {
				if ($waitlist['Edate'] <= $curr_dose['ExpDate']) {
					echo "Dose id: " . $apt['DoseID'] . "<br>";
					echo "Dose reserved to:" . $waitlist['PatientID'] . "<br>";
					$result = mysqli_query($con, "update appointments set AptResult = 'reserved', DoseID = '" . $apt['DoseID'] . "', Date = '" . $waitlist['Edate'] 
									. "' where patientID = '" . $waitlist['PatientID'] . "'");

					$status = 'used';
					if ($result) echo "Updated a wailisted patient <br>";
					else echo "Failed to update a waitlisted patient <br>";
					break;
				}
			}
		}
		if ($valid_cancel) {
			$restore_dose = "update doses set status = '" . $status . "' where doseid = '" . $apt['DoseID'] . "'";
			mysqli_query($con, $restore_dose);
		} else {
			echo "This patient already got vaccinated: Can not Cancel";
		}
	?>
	<div id = "cancel" style = "margin-top: -200px;">
	<form method = "POST" action = "signup.php">
		<input type = "submit" value = "signout"><br/>
	</form>
	</div>

</body>
</html>