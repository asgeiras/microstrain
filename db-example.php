<?php

$dbuser = 'user';
$dbpassword = 'password';
$dbname = 'database';
$hostname = 'localhost';
$port = 8889;

// Open database connection
try {
	$dbh = new PDO("mysql:host=$hostname;port=$port;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpassword);
	$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
}
catch(PDOException $e) {
	echo $e->getMessage();
	die();
}

?>