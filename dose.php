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
<style>
	div#dose_add {
		position: absolute;
		text-align: center;
		top: 50%;
		left: 50%;
		width: 300px; height: 220px;
		margin-top: -110px;
		border: 1px solid gray;
	}
	div#dose_table {
		position: absolute;
		text-align: center;
		top: 10%;
		left: 25%;
		margin-bottom: 50px;
		border: 1px solid gray;
	}
	div#dose_error {
		position: absolute;
		color: red;
		top: 50%;
		left: 50%;
		margin-top: 130px;
	}
	div#dose_result {
		position: absolute;
		color: blue;
		top: 50%;
		left: 50%;
		margin-top: 130px;
	}
	div#back {
		position: absolute;
		top: 80%;
		left: 80%;
	}
</style>
<body>
	<?php
		$con = new mysqli('localhost','root','mysql','test');
		if (!$con) {die('Cannot connect' .mysqli_connect_error());}

		// Insert a new dose if applicable information provided
		// While inserting, update applicable patient if dose matches with their information

		$dose_id = "";
		$is_error = false;
		if (isset($_POST['dose']) || isset($_Post['DoseID']) || isset($_POST['ExpDate']) && count($_POST) < 3) {
			$dose_id = $_POST['DoseID'];
			$check_doseID = mysqli_query($con, "select DoseID from doses where DoseID = '" . $dose_id . "'");
			$dose_id_result = $check_doseID->fetch_array();
			echo "<div id = 'dose_error'>";
			if (isset($dose_id_result['DoseID'])) {
				echo "Dose ID with '#" . $dose_id . "' already exists in the inventory. <br> Please try with other Dose ID. <br>";
				$is_error = true;
			} 
			echo "</div>";
		} 
		if (count($_POST) > 2 && !$is_error) {

				$get_waitlist = mysqli_query($con, "select appointments.PatientID, age, Priority, Edate from appointments left join patient on "
											. "(appointments.patientid = patient.patientid) where AptResult = 'waitlist' order by priority ASC, age DESC");
				$is_updated = false;
				if ($get_waitlist) {
					while ($waitlist = $get_waitlist->fetch_array()) {
						if ($waitlist['Edate'] <= $_POST['ExpDate'] && $_POST['ExpDate'] >= '2021-03-20') {
							mysqli_query($con, "update appointments set AptResult = 'reserved', DoseID = '" . $dose_id . "', Date = '" . $waitlist['Edate'] 
										. "' where patientID = '" . $waitlist['PatientID'] . "'");
							echo "Updated a wailisted patient with ID: " . $waitlist['PatientID'];
							$is_updated = true;
							break;
						}
					}
				}

				$status = 'valid';
				if ($is_updated) $status = 'used';
				if ($_POST['ExpDate'] < '2021-03-20') $status = 'expired';
				mysqli_query($con, "insert into doses (DoseID, Manufacture, ExpDate, Status) values ('" . $dose_id . "', '" . $_POST['dose'] . "', '" 
								. $_POST['ExpDate'] . "', '" . $status . "')");
				echo "<div id = 'dose_result'>";
				echo "Dose ID '#" . $dose_id . "' is succssfully added to the inventory. <br> The current status of this dose is: '" . $status . "'. <br>";
				echo "</div>";
			
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
			echo "<div id = 'dose_table'>";
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
					echo "Dose Distributed:    " . $dose_used . "<br>";
					echo "Dose Available:      " . $dose_valid . "<br>";
					echo "Dose Expired:        " . $dose_exp . "<br>";
				}
			}
			echo "</div>";
		}
	?>
	<div id = "dose_add">
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
	</div>
	<div id = "back">
		<form method = "GET" action = "signup.php">
			<input type = "submit" value = "Back">
		</form>
	</div>
</body>
</html>