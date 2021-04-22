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

		$doses = mysqli_query($con, "select * from doses");
		$pfizer = array();
		$moderna = array();
		$johnson = array();

		while ($dose = $doses->fetch_array()) {
			if ($dose['Manufacture'] == 'pfizer') {
				array_push($pfizer, $dose);
			} else if ($dose['Manufacture'] == 'moderna') {
				array_push($moderna, $dose);
			} else {
				array_push($johnson, $dose);
			}
		}
		$dose_total = array($pfizer, $moderna, $johnson);
		for ($i = 0; $i < 3; $i++) {
			$dose = $dose_total[$i];
			$total_dose = 0;
			$dose_used = 0;
			$dose_valid = 0;
			$dose_exp = 0;
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



	?>
</body>
</html>