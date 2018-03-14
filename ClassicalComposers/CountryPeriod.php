<?php
ini_set('display_errors', 'On');
include 'password.php';
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", $Username, $Password, $Username);
if(!$mysqli || $mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http:///www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<style>
	table, th, td {
		border: 2px solid black; <!-- table cell borders --> border-collapse: collapse;
			padding: 10px <!-- table padding -->
	}
	</style>
</head>
<body>
	<div>
		<table>
			<tr>
				<td>Select composers by country </td>
			</tr>
			<tr>
				<td><b>Country</b></td>
				<td><b>Composer</b></td>
				<td><b>Period</b></td>
			</tr>
			
			<?php
			if(!($stmt = $mysqli->prepare("SELECT home_country.name, composer.lname, period.name FROM composer INNER JOIN home_country ON composer.c_id = home_country.id INNER JOIN period ON composer.p_id = period.id WHERE home_country.name = ?"))){
					echo "prepare failed: " . $stmt->errno . " " . $stmt->error; 
				}
			if(!($stmt->bind_param("s",$_POST['findComposersByCountry']))){
					echo "bind failed: " . $stmt->errno . " " . $stmt->error;
				}
			if(!$stmt->execute()){
					echo "execute failed: " . $mysqli->errno . " " . $mysqli->error; 
				}
			if(!$stmt->bind_result($country, $lname, $period)){
					echo "bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}
			while ($stmt->fetch()){
					echo "<tr>\n<td>\n" . $country . "\n</td>\n<td>\n" . $lname . "\n</td>\n<td>\n" . $period . "\n</td>\n</tr>";
			}
			$stmt->close();
			?>

		</table>
	</div>
<hr>
<a href="http://web.engr.oregonstate.edu/~reichmat/index.php">Back to the Main Page</a>
</body>
</html>