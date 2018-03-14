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
				<td>Select pieces by instrument</td>
			</tr>
			<tr>
				<td><b>Instrument</b></td>
				<td><b>Piece</b></td>
			</tr>
			
			<?php
			if(!($stmt = $mysqli->prepare("SELECT instrument.name, piece.name FROM piece INNER JOIN contains ON piece.id = contains.pid INNER JOIN instrument ON contains.iid = instrument.id WHERE instrument.name = ?"))){
					echo "prepare failed: " . $stmt->errno . " " . $stmt->error; 
				}
			if(!($stmt->bind_param("s",$_POST['findPiecesByInstrument']))){
					echo "bind failed: " . $stmt->errno . " " . $stmt->error;
				}
			if(!$stmt->execute()){
					echo "execute failed: " . $mysqli->errno . " " . $mysqli->error; 
				}
			if(!$stmt->bind_result($instrument, $piece)){
					echo "bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}
			while ($stmt->fetch()){
					echo "<tr>\n<td>\n" . $instrument . "\n</td>\n<td>\n" . $piece . "\n</td>\n</tr>";
			}
			$stmt->close();
			?>

		</table>
	</div>
<hr>
<a href="http://web.engr.oregonstate.edu/~reichmat/index.php">Back to the Main Page</a>
</body>
</html>