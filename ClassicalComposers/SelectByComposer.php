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
				<td>Select pieces by composer</td>
			</tr>
			<tr>
				<td><b>Composer</b></td>
				<td><b>Piece</b></td>
				<td><b>Type</b></td>
			</tr>
			
			<?php
			if(!($stmt = $mysqli->prepare("SELECT composer.lname, piece.name, piece.type FROM piece INNER JOIN composer ON piece.composer_id = composer.id WHERE composer.lname = ?"))){
					echo "prepare failed: " . $stmt->errno . " " . $stmt->error; 
				}
			if(!($stmt->bind_param("s",$_POST['findPiecesByComposer']))){
					echo "bind failed: " . $stmt->errno . " " . $stmt->error;
				}
			if(!$stmt->execute()){
					echo "execute failed: " . $mysqli->errno . " " . $mysqli->error; 
				}
			if(!$stmt->bind_result($lname, $piece, $type)){
					echo "bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}
			while ($stmt->fetch()){
					echo "<tr>\n<td>\n" . $lname . "\n</td>\n<td>\n" . $piece . "\n</td>\n<td>\n" . $type . "\n</td>\n</tr>";
			}
			$stmt->close();
			?>

		</table>
	</div>
<hr>
<a href="http://web.engr.oregonstate.edu/~reichmat/index.php">Back to the Main Page</a>
</body>
</html>