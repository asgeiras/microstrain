<?php
//<body onLoad='javascript:document.showEdited.submit();'>

include ("/var/local/datalogin.php");  
$con = mysql_connect("$dbhost","$dbusername","$dbuserpass");
if (!$con) {
	die('Could not connect: ' . mysql_error());
}

mysql_select_db("$dbname", $con) or die("Unable to select database");

// find out how many records there are to update
$size = count($_POST['Genotype']);

// start a loop in order to update each record
$i = 0;

echo "<form name='showEdited' action='index.php?mode=edit2' method='post'>";
while ($i < $size) {
	// define each variable
	$Strain= $_POST['Strain'][$i];
	//$Genotype = $_POST['Genotype'][$i];

	$Genotype = mysql_real_escape_string(str_replace(array("\r", "\r\n", "\n") , " " , $_POST['Genotype'][$i] ));
	$Comment = mysql_real_escape_string(str_replace(array("\r", "\r\n", "\n") , " " , $_POST['Comment'][$i] ));

	$Recipient = $_POST['Recipient'][$i];
	$Donor = $_POST['Donor'][$i];
	//$Comment = $_POST['Comment'][$i];
	$Signature = $_POST['Signature'][$i];
	$Created = $_POST['Created'][$i];
	$selected[$i] = $Strain;

	if(empty($Created)) {
		$query = "UPDATE strains SET `Genotype` = '$Genotype', `Donor` = '$Donor', `Recipient` = '$Recipient', `Comment` = '$Comment', `Signature` = '$Signature' WHERE `Strain` = '$Strain' LIMIT 1";
		mysql_query($query) or die ("Error in query: $query");
	} else{
		$query = "UPDATE strains SET `Genotype` = '$Genotype', `Donor` = '$Donor', `Recipient` = '$Recipient', `Comment` = '$Comment', `Signature` = '$Signature', `Created` = '$Created' WHERE `Strain` = '$Strain' LIMIT 1";
		mysql_query($query) or die ("Error in query: $query");
	}

	echo "<input type='hidden' name='selected[]' value=" . $selected[$i] . "><br>";

	++$i;
}

echo "</form>";
mysql_close();
?>