<?php
ini_set('display_errors', 'On');
include 'password.php';
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", $Username, $Password, $Username);
if(!$mysqli || $mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
if(!($stmt = $mysqli->prepare("INSERT INTO piece(name, type) VALUES (?,?)"))){
	echo "prepare failed: " . $stmt->errno . " " . $stmt->error; 
}
if(!($stmt->bind_param("ss",$_POST['PieceName'],$_POST['PieceType']))){
	echo "bind failed: " . $stmt->errno . " " . $stmt->error;
	}
if(!($stmt->execute())){
	echo "execute failed: " . $stmt->errno . " " . $stmt->error; 
	} else {
		echo "Added " . $stmt->affected_rows . " rows into piece.";
	}

?>