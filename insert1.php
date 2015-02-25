<?php
// <body onLoad='javascript:document.showSaved.submit();'>

include ("/var/local/datalogin.php");  
$con = mysql_connect("$dbhost","$dbusername","$dbuserpass");
if (!$con) {
	die('Could not connect: ' . mysql_error());
}

mysql_select_db("$dbname", $con) or die("Unable to select database");

$test = array();
$num_lines = $_POST["hdnLine"]-1;
session_start();

$testresult = 0;

if($num_lines == 1) {
	$plural = '';
} else {
	$plural = 's';
}

for($i = 1; $i <= $num_lines; $i++) {
	$_SESSION["Line"] = $num_lines;
	$_SESSION["txtGenotype$i"] = $_POST["txtGenotype$i"];
	$_SESSION["txtDonor$i"] = $_POST["txtDonor$i"];
	$_SESSION["txtRecipient$i"] = $_POST["txtRecipient$i"];
	$_SESSION["txtComment$i"] = $_POST["txtComment$i"];

	if($_POST["txtGenotype$i"] != "") {
		$test[$i] = 'PASS';
	} else {
		$test[$i] = 'FAIL';
		$_SESSION['saveFail'] = $i; 
	}
}

$testresult = array_search('FAIL', $test);
if ($testresult == 0) {
	$goAhead = 'TRUE';
} else {
	$goAhead = 'FALSE';
}

if($goAhead == 'TRUE') {
	echo "<form action='index.php?mode=add3' name='showSaved' action='search.php' action='search1.php'  method='post'>";
		for($i = 1; $i <= $num_lines; $i++) {

			$genotype = str_replace(array("\r", "\r\n", "\n") , " " , $_SESSION["txtGenotype$i"] );
			$comment = str_replace(array("\r", "\r\n", "\n") , " " , $_SESSION["txtComment$i"] );

			$sql = "INSERT INTO strains ";
			$sql .="(Genotype,Donor,Recipient,Comment,Signature) ";
			$sql .="VALUES ";
			$sql .="('".$genotype."','".$_SESSION["txtDonor$i"]."', ";
			$sql .="'".$_SESSION["txtRecipient$i"]."' ";
			$sql .=",'".$comment."','".$_SESSION['signature']."') ";

			$query = mysql_query($sql);
			$inserted[$i] = mysql_insert_id();

			echo "<input type=hidden name='inserted[]' value=". $inserted[$i]. ">";

			$_SESSION["Line"] = '';
			$_SESSION["txtGenotype$i"] = '';
			$_SESSION["txtDonor$i"] = '';
			$_SESSION["txtRecipient$i"] = '';
			$_SESSION["txtComment$i"] = '';

			mysql_close($objConnect);  
		}
	echo "</form>";
}
elseif($goAhead == 'FALSE') {
	mysql_close($objConnect);

	$lineInRedirect = $_SESSION["Line"];

	//echo "<script language=javascript>alert('Missing, a genotype is.')</script>";
	echo "<script language=javascript>window.location = 'index.php?mode=add&Line=$lineInRedirect'</script>";
}
?>