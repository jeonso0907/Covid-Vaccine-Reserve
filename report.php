<!DOCTYPE html>
<html>
<head>
	<title>Patient Report</title>
</head>
<style>
	table, th, td {
		border: 1px solid black;
	}
</style>
<body>
	<!-- <h2>Patient Report: <?php //echo date("Y-m-d") ?></h2> -->
	<h2>Patient Report: 2021-4-20</h2>
	<?php
		$con = new mysqli('localhost','root','mysql','bur');
		if (!$con) {die('Cannot connect' .mysqli_connect_error());}

		$get_report = mysqli_query($con, "select Fname, Lname, AptResult, Manufacture, Date from appointments left join patient on "
										. "(appointments.patientid = patient.patientid) left join doses on "
										. "(appointments.doseid = doses.doseid) order by AptResult, Date Asc");

		$vaccinated = array();
		$reserved = array();
		$waitlist = array();
		while ($report = $get_report->fetch_array()) {
			if ($report['AptResult'] == 'reserved') {
				if ($report['Date'] < '2021-04-20') {
					array_push($vaccinated, $report);
				} else {
					array_push($reserved, $report);
				}
			} else {
				array_push($waitlist, $report);
			}
		}
		$patient_report = array($vaccinated, $reserved, $waitlist);

		for ($i = 0; $i < 3; $i++) {
			$section = $patient_report[$i];
			if ($i == 0) {
				echo "<h3>Report: Vaccinated Patient</h3>";
				$col = "<th>Name</th> <th>Brand</th> <th>Date</th>";
			} else if ($i == 1) {
				echo "<h3>Report: Reserved Patient</h3>";
				$col = "<th>Name</th> <th>Brand</th> <th>Date</th>";
			} else {
				echo "<h3>Report: Waitlisted Patient</h3>";
				$col = "<th>Name</th>";
			}
			if (count($section) != 0) {
				echo "<table id = 'report_table'>";
				echo "<tr> " . $col . " </tr>";
				for ($j = 0; $j < count($section); $j++) {
					$r = $section[$j];
					if ($i < 2) {
						echo "<tr><td>" . $r['Fname'] . " " . $r['Lname'] . "</td><td>" . $r['Manufacture'] . "</td><td>" . $r['Date'] . "</td></tr>";
					} else {
						echo "<tr><td>" . $r['Fname'] . " " . $r['Lname'] . "</td><tr>";
					}
				}
				echo "</table> <br>";
			} else {
				echo "No Patient in this report<br>";
			}
		}

	?>
	<br>
	<form method = "get" action = "signup.php">
		<input type = "submit" value = "Back"></br>
	</form>
</body>
</html>