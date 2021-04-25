<!DOCTYPE html>
<html>
<head>
	<title>Dose Report</title>

</head>
<style>
	.center {
	  	margin-left: auto;
	  	margin-right: auto;
	}
	#dose_table, th, td {
		border: 1px solid black;
		text-align: center;
		vertical-align: middle;
	}
	#report{
		position: absolute;
		top: 10%;
		left: 15%;
		vertical-align: middle;
		float: left;
		text-align: center;
		border-collapse: separate;
		border-spacing: 30px;
	}
	div#used {
		color: red;
	}
	div#valid {
		color: blue;
	}
	div#back {
		position: absolute;
		top: 80%;
		left: 80%;
		margin-left: 80px;
	}
	div#no {
		position: absolute;
		top: 40%;
		left: 47%;
	}
</style>
<body>
	<?php
		$con = new mysqli('localhost','root','mysql','test');
		if (!$con) {die('Cannot connect' .mysqli_connect_error());}

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
			echo "<table id = 'report'> <tr>";
			$dose_total = array($pfizer, $moderna, $johnson);
			for ($i = 0; $i < 3; $i++) {
				$dose = $dose_total[$i];
				$total_dose = 0;
				$dose_used = 0;
				$dose_valid = 0;
				$dose_exp = 0;
				echo "<td>";
				if (isset($dose[0]['Manufacture'])) {
					echo "<h3>" . $dose[0]['Manufacture'] . "</h3>";
					echo "<table id = 'dose_table' class = 'center'>";
					echo "<tr> <th>Dose ID</th> <th>Expiration Date</th> <th>Status</th> </tr>";
					for ($j = 0; $j < count($dose); $j++) {
						echo "<tr> <td>" . $dose[$j]['DoseID'] . "</td> <td>" . $dose[$j]['ExpDate'] . "</td>";
						if ($dose[$j]['Status'] == 'used') {
							echo "<td><div id = 'used'>";
							echo  $dose[$j]['Status'];
							echo "</td></div>";
						} else if ($dose[$j]['Status'] == 'valid') {
							echo "<td><div id = 'valid'>";
							echo  $dose[$j]['Status'];
							echo "</td></div>";
						} else {
							echo "<td>";
							echo  $dose[$j]['Status'];
							echo "</td>";
						}
						echo "</tr>";
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
				echo "</td>";
			}
			echo "</tr>";
			echo "</table>";
		} else {
			echo "<div id = 'no'>";
			echo "<h3>No Dose</h3>";
			echo "</div>";
		}
	?>
	<br>
	<div id = "back">
	<form method = "GET" action = "signup.php">
		<input type = "submit" value = "Back">
	</form>
	</div>
</body>
</html>