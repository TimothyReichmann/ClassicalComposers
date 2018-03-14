<?php
ini_set('display_errors', 'On');
include 'password.php';
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", $Username, $Password, $Username);
if($mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Classical Music Database</title>
  <style>
  table, th, td {
    border: 2px solid black; <!-- table cell borders -->
    border-collapse: collapse;
	padding: 10px; <!-- table padding -->
}
  </style>
</head>
<body>
<div>
<h1>Current Pieces of Classical Music in the Database</h1> 
	<table>
		<tr>
			<td><b>Piece Name </b></td>
			<td><b>Type </b></td>
			<td><b>Composer </b></td>
		</tr>
<?php

//Connects to the database
if(!($stmt = $mysqli->prepare("SELECT piece.name, piece.type, composer.lname FROM piece INNER JOIN composer ON piece.composer_id = composer.id ORDER BY piece.name ASC"))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}
//error message thrown if execute doesn't run
if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
//binding variables to prepared statement
if(!$stmt->bind_result($name, $type, $composer)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
//while loop runs until there isn't any values to fetch
while($stmt->fetch()){
 echo "<tr>\n<td>\n" . $name . "\n</td>\n<td>\n" . $type . "\n</td>\n<td>\n" . $composer . "\n</td>\n</tr>";
}
$stmt->close();
?>
	</table>
</div>
<!-- add forms-->
<form method="post" action="addCountry.php">
    <fieldset>
      	<legend>Add a Country:</legend>
      	<label>Country Name: </label><br>
      	<input type="text" name="CountryName"><br>
      	<input type="submit" value="Add Country">
    </fieldset>
</form>
<form method="post" action="addPeriod.php">
    <fieldset>
      	<legend>Add a Period:</legend>
      	<label>Period Name: </label><br>
      	<input type="text" name="PeriodName"><br>
      	<input type="submit" value="Add Period">
    </fieldset>
</form>
<form method="post" action="addInstrument.php">
    <fieldset>
      	<legend>Add an Instrument:</legend>
      	<label>Instrument Name: </label><br>
      	<input type="text" name="InstrumentName"><br>
      	<input type="submit" value="Add Instrument">
    </fieldset>
</form>
<form method="post" action="addPiece.php">
    <fieldset>
      	<legend>Add a Piece:</legend>
      	<label>Piece Name: </label><br>
      	<input type="text" name="PieceName"><br>
      	<label>Piece Type: </label><br>
      	<input type="text" name="PieceType"><br>
      	<input type="submit" value="Add Piece">
    </fieldset>
</form>
<form method="post" action="addComposer.php">
    <fieldset>
      	<legend>Add a Composer:</legend>
      	<label>First Name: </label><br>
      	<input type="text" name="FName"><br>
      	<label>Last Name: </label><br>
      	<input type="text" name="LName"><br>
      	<input type="submit" value="Add Composer">
    </fieldset>
</form>
<!-- select forms-->
<div>
<h2>Select All Pieces Written By a Certain Composer</h2>
	<form method="post" action="SelectByComposer.php">
		<fieldset>
			<legend>Select a composer whose songs you want to view:</legend>
				<select name="findPiecesByComposer">
					<?php
					if(!($stmt = $mysqli->prepare("SELECT lname FROM composer"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					if(!$stmt->execute()){
						echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					if(!$stmt->bind_result($lname)){
						echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					while($stmt->fetch()){
					 echo '<option>' . $lname . '</option>\n';
					}
					$stmt->close();
					?>
				</select>
		<input type="submit" value="View Pieces By That Composer" />
		</fieldset>
	</form>
</div>

<div>
<h2>Select All Pieces Written For a Certain Instrument</h2>
	<form method="post" action="SelectByInstrument.php">
		<fieldset>
			<legend>Select an instrument to find pieces written for that instrument:</legend>
				<select name="findPiecesByInstrument">
					<?php
					if(!($stmt = $mysqli->prepare("SELECT name FROM instrument"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					if(!$stmt->execute()){
						echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					if(!$stmt->bind_result($name)){
						echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					while($stmt->fetch()){
					 echo '<option>' . $name . '</option>\n';
					}
					$stmt->close();
					?>
				</select>
		<input type="submit" value="View Pieces Written For That Instrument" />
		</fieldset>
	</form>
</div>

<div>
<h2>Select All Composers From a Certain Country and See Which Period They Are From</h2>
	<form method="post" action="CountryPeriod.php">
		<fieldset>
			<legend>Select a country to see composers from that country:</legend>
				<select name="findComposersByCountry">
					<?php
					if(!($stmt = $mysqli->prepare("SELECT name FROM home_country"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					if(!$stmt->execute()){
						echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					if(!$stmt->bind_result($country)){
						echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					while($stmt->fetch()){
					 echo '<option>' . $country . '</option>\n';
					}
					$stmt->close();
					?>
				</select>
		<input type="submit" value="View Composers From This Country" />
		</fieldset>
	</form>
</div>
<!-- delete form-->
<div>
	<h2>Delete a composer cuz they just aren't famous enough:</h2>
    <form method="post" action="delete.php">
      <fieldset>
        <legend>Please select a composer to delete: </legend>
        <select name="deleteComposer">
<?php
if(!($stmt = $mysqli->prepare("SELECT composer.fname, composer.lname, home_country.name, period.name FROM composer INNER JOIN home_country ON composer.c_id = home_country.id INNER JOIN period ON composer.p_id = period.id"))){
	echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
}
if(!$stmt->execute()){
	echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!$stmt->bind_result($fname, $lname, $cname, $pname)){
	echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
while($stmt->fetch()){
	echo '<option value=" '. $fname . ' "> ' . $lname  . " " . $cname . " " . $pname . '</option>\n';
}
$stmt->close();
?>
        </select><br>
        <input type="submit" value="Delete Composer">
      </fieldset>
    </form>
  </div>

</body>
</html>
