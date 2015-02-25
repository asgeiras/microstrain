<?php

// *****************************
// ** Written by: Per Enström **
// ******* February 2015 *******

/*
$dbuser = 'root';
$dbpassword = '1Lovestrains';
$dbname = 'strains';
$hostname = 'localhost';
$port = 8889;

$today = date ('Y-m-d H:i:s');
*/


$dbuser = 'root';
$dbpassword = 'root';
$dbname = 'strains';
$hostname = 'localhost';
$port = 8889;

$today = date ('Y-m-d H:i:s');

// Open database connection
try {
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpassword);
	echo "connected to database";
}
catch(PDOException $e) {
	echo $e->getMessage();
	die();
}

?>