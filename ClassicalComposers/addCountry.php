<?php
ini_set('display_errors', 'On');
include 'password.php';
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", $Username, $Password, $Username);
if(!$mysqli || $mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!($stmt = $mysqli->prepare("INSERT INTO home_country(name) VALUES (?)"))){
	echo "prepare failed: " . $stmt->errno . " " . $stmt->error; 
}
if(!($stmt->bind_param("s",$_POST['CountryName']))){
	echo "bind failed: " . $stmt->errno . " " . $stmt->error;
	}
if(!($stmt->execute())){
	echo "execute failed: " . $stmt->errno . " " . $stmt->error; 
	} else {
		echo "Added " . $stmt->affected_rows . " rows into home country.";
	}

?>