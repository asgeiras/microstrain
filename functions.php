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

// CHECK IF STRAIN EXISTS
function strain_exists($strain = NULL){
	global $dbh;

	if($strain !== NULL && is_numeric($strain)){
		// Prepare PDO statement
		$stmt = $dbh->prepare("SELECT * FROM strains WHERE Strain = :strain");

		// Bind parameter
		$stmt->bindParam(":strain", $strain);

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
	} else {
		return FALSE;
	}
}