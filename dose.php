<!DOCTYPE html>
<html>
<head>
	<title>Dose Control</title>
</head>
<style>
	table, th, td {
		border: 1px solid black;
	}
</style>
<body>
	<?php
		$con = new mysqli('localhost','root','mysql','bur');
		if (!$con) {die('Cannot connect' .mysqli_connect_error());}

		// Insert a new dose if applicable information provided
		// While inserting, update applicable patient if dose matches with their information
		if (isset($_POST['dose'])) {

			$curr_dose = mysqli_query($con, "select count(Manufacture) from doses where Manufacture = '" . $_POST['dose'] . "'");
			$dose_count = $curr_dose->fetch_array();
			$dose_id = $_POST['DoseID'];

			$get_waitlist = mysqli_query($con, "select appointments.PatientID, age, Priority, Edate from appointments left join patient on "
											. "(appointments.patientid = patient.patientid) where AptResult = 'waitlist' order by priority ASC, age DESC");
			$is_updated = false;
			if ($get_waitlist) {
				while ($waitlist = $get_waitlist->fetch_array()) {
					if ($waitlist['Edate'] <= $_POST['ExpDate']) {
						mysqli_query($con, "update appointments set AptResult = 'reserved', BatchID = 1, DoseID = '" . $dose_id . "', Date = '" . $waitlist['Edate'] 
									. "' where patientID = '" . $waitlist['PatientID'] . "'");
						echo "Updated a wailisted patient with ID: " . $waitlist['PatientID'];
						$is_updated = true;
						break;
					}
				}
			}

			$status = 'valid';
			if ($is_updated) $status = 'used';
			mysqli_query($con, "insert into doses (BatchID, DoseID, Manufacture, ExpDate, Status) values (1,  '" . $dose_id . "', '" . $_POST['dose'] . "', '" 
							. $_POST['ExpDate'] . "', '" . $status . "')");
			
		}

		$doses = mysqli_query($con, "select * from doses");
		$pfizer = array();
		$moderna = array();
		$johnson = array();
		$is_empty = true;
		while ($dose = $doses->fetch_array()) {
			if ($dose['Manufacture'] == 'pfizer') {
				array_push($pfizer, $dose);
				$is_empty = false;
			} else if ($dose['Manufacture'] == 'moderna') {
				array_push($moderna, $dose);
				$is_empty = false;
			} else {
				array_push($johnson, $dose);
				$is_empty = false;
			}
		}
		if (!$is_empty) {
			$dose_total = array($pfizer, $moderna, $johnson);
			for ($i = 0; $i < 3; $i++) {
				$dose = $dose_total[$i];
				$total_dose = 0;
				$dose_used = 0;
				$dose_valid = 0;
				$dose_exp = 0;
				if (isset($dose[0]['Manufacture'])) {
					echo "<h3>" . $dose[0]['Manufacture'] . "</h3>";
					echo "<table id = 'dose_table'>";
					echo "<tr> <th>Dose ID</th> <th>Expiration Date</th> <th>Status</th> </tr>";
					for ($j = 0; $j < count($dose); $j++) {
						echo "<tr> <td>" . $dose[$j]['DoseID'] . "</td> <td>" . $dose[$j]['ExpDate'] . "</td><td>" . $dose[$j]['Status'] . "</td> </tr>";
						$total_dose++;
						if ($dose[$j]['Status'] == 'used') {
							$dose_used++;
						} else if ($dose[$j]['Status'] == 'valid') {
							$dose_valid++;
						} else {
							$dose_exp++;
						}
					}
					echo "</table>";
					echo "Total Dose Recevied: " . $total_dose . "<br>";
					echo "Dose Distributed: " . $dose_used . "<br>";
					echo "Dose Available: " . $dose_valid . "<br>";
					echo "Dose Expired: " . $dose_exp . "<br>";
				}
			}
		}
	?>
	<form method = "POST" action = "dose.php">
		<h4>Add Dose</h4>
		<input type = "radio" id = "pfizer" name = "dose" value = "pfizer">
		<label for = "pfizer">Pfizer</label>
		<input type = "radio" id = "moderna" name = "dose" value = "moderna">
		<label for = "moderna">Moderna</label>
		<input type = "radio" id = "johnson" name = "dose" value = "johnson">
		<label for = "johnson">Johnson</label><br>
		Dose ID: <input type = "text" name = "DoseID"> <br>
		Expiration Date: <input type = "date" name = "ExpDate"><br>
		<input type = "submit"	value = "Add"><br>
	</form>
	<form method = "GET" action = "signup.php">
		<input type = "submit" value = "Back">
	</form>
</body>
</html>