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

// DO SEARCH
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['form-type'] == 'search'){

	// Search button has been pressed
	if($_POST['button'] == 'search-text' || $_POST['button'] == 'search-list'){
		$_SESSION['sign1'] = $_POST['sign1'];

		// Search is text
		if($_POST['button'] == 'search-text') {
			$_SESSION['term1'] = $_POST['term1'];
			$_SESSION['term2'] = $_POST['term2'];
			$_SESSION['term3'] = $_POST['term3'];
			$_SESSION['term4'] = $_POST['term4'];
			$_SESSION['notterm1'] = $_POST['notterm1'];
			$_SESSION['notterm2'] = $_POST['notterm2'];
			$_SESSION['notterm3'] = $_POST['notterm3'];
			$_SESSION['notterm4'] = $_POST['notterm4'];
			$_SESSION['genotype'] = $_POST['check']['genotype'];
			$_SESSION['comment'] = $_POST['check']['comment'];
			if(!empty($_POST['check']['genotype'])){
				$_SESSION['searchgenotype'] = 1;
			} else {
				$_SESSION['searchgenotype'] = 0;
			}
			if(!empty($_POST['check']['comment'])){
				$_SESSION['searchcomment'] = 1;
			} else {
				$_SESSION['searchcomment'] = 0;
			}

			// If all search boxes are empty, redirect to error
			if(empty($_POST['term1']) && 
				empty($_POST['term2']) && 
				empty($_POST['term3']) && 
				empty($_POST['term4']) && 
				empty($_POST['notterm1']) && 
				empty($_POST['notterm2']) && 
				empty($_POST['notterm3']) && 
				empty($_POST['notterm4']) && 
				empty($_POST['sign1'])
			) {
				header("Location: index.php?mode=search&error=error");
			}
		}

		// Search is number/list
		if($_POST['button'] == 'search-list') { 
			$_SESSION['minNum'] = $_POST['minNum'];
			$_SESSION['maxNum'] = $_POST['maxNum'];
		}
	}

	// The reset button clears all submitted variables from all forms
	if($_POST['reset'] == 'Reset'){
		//if($_GET['reset'] == 'TRUE') {
			//Search:
			$_SESSION['total_records'] = '';
			$_SESSION['word'] = '';
			$_SESSION['num'] = '';
			$_SESSION['check'] = '';
			$_SESSION['genotype'] = '';
			$_SESSION['comment'] = '';
			$_SESSION['term1'] = '';
			$_SESSION['term1'] = '';
			$_SESSION['term2'] = '';
			$_SESSION['term3'] = '';
			$_SESSION['term4'] = '';
			$_SESSION['notterm1'] = '';
			$_SESSION['notterm2'] = '';
			$_SESSION['notterm3'] = '';
			$_SESSION['notterm4'] = '';
			$_SESSION['sign1'] = '';
			$_SESSION['minNum'] = '';
			$_SESSION['maxNum'] = '';

			/*
			// Add strains:
			$_SESSION["Line"] = '';
			$_SESSION['saveFail'] = '';
			for($i = 1; $i <= 50; $i++) {
				$_SESSION["txtGenotype$i"] = '';
				$_SESSION["txtDonor$i"] = '';
				$_SESSION["txtRecipient$i"] = '';
				$_SESSION["txtComment$i"] = '';
			}
			*/
		//}
	}

}

?>