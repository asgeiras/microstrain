<?php

// DO LOGIN
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['form-type'] == 'login'){

	$uname = $_POST['username'];
	$pword = $_POST['password'];

	// Prepare PDO statement
	$stmt = $dbh->prepare("SELECT * FROM users WHERE Username = :uname AND Password = :pword");
	$stmtCount = $dbh->prepare("SELECT COUNT(*) FROM users WHERE Username = :uname AND Password = :pword");

	// Bind parameters
	$stmt->bindParam(":uname", $uname);
	$stmt->bindParam(":pword", md5($pword));
	$stmtCount->bindParam(":uname", $uname);
	$stmtCount->bindParam(":pword", md5($pword));

	// Execute the statements
	$stmt->execute();
	$stmtCount->execute();

	// Cast the fetched count-column as an integaer
	$count = (int) $stmtCount->fetchColumn();

	if($count > 0){
		// Store the fetched row in a user variable
		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		// Store session as logged in
		$_SESSION['login'] = "1";

		// Store user details in session
		$_SESSION['user'] = $user['Username'];
		$_SESSION['Usertype'] = $user['Usertype'];
		$_SESSION['Signature'] = $user['Signature'];

		// Close database cursors
		$stmt->closeCursor();
		$stmtCount->closeCursor();

	} else {
		$_SESSION['login'] = "";

		$errorMessage = "Error logging on";

		// Close database cursors
		$stmt->closeCursor();
		$stmtCount->closeCursor();
	}
}

?>