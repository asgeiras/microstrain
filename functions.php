<?php

// CHECK IF USER EXISTS
function user_exists($user = NULL, $field = "id"){
	global $dbh;

	// Prepare PDO statement
	if($field == "id"){
		$stmt = $dbh->prepare("SELECT * FROM users WHERE Id = :user");
	}
	elseif ($field == "username") {
		$stmt = $dbh->prepare("SELECT * FROM users WHERE Username = :user");
	}
	elseif ($field == "signature") {
		$stmt = $dbh->prepare("SELECT * FROM users WHERE Signature = :user");
	}

	// Bind parameter
	$stmt->bindParam(":user", $user);

	// Execute statement
	$stmt->execute();

	// Check how many rows were found
	$rows_found = $stmt->rowCount();

	// Return based on result
	if($rows_found > 0){
		return TRUE;
	} else {
		return FALSE;
	}
}