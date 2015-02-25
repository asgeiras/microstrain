<?php 

include ("/var/local/datalogin.php");

$db_handle = mysql_connect($dbhost, $dbusername, $dbuserpass) or die(mysql_error());
$db_found = mysql_select_db($dbname, $db_handle) or die(mysql_error());

if ($db_found) {
	$SQL = "SELECT * FROM users WHERE Username ='". $_SESSION['user']. "'";
	$result = mysql_query($SQL) or die(mysql_error());
	$num_rows = mysql_num_rows($result) or die(mysql_error());
	if ($result) {
		while($row = mysql_fetch_array($result)) {
			session_start();
			$_SESSION['Usertype'] = $row['Usertype'];
			$_SESSION['signature'] = $row['Signature'];
		}
	}
}

if($_SESSION['Usertype'] == 'Superuser') {

	echo '<form action="index.php?mode=add2" name="frmAdd" method="post">'; 
	echo "<p>Number of strains to add:</p>";
	echo '<select class="droplist" name="menu1" onChange="MM_jumpMenu(\'parent\',this,0) ">';


	$helpmessage = "Only lines with text in the genotype field will be saved to the database. Enter the strain information carefully!"; 
	for($i = 1; $i <= 50; $i++) {
		if($_GET["Line"] == $i){  
			$sel = "selected";
		} else {
			$sel = "";
		}

		echo '<option value="' . $_SERVER['PHP_SELF'] . '?mode=add&amp;Line=' . $i . '" ' . $sel . '>' . $i . '</option>';
	} 

	// Did we fail validation (missing genotype?)
	if($_SESSION['saveFail'] != '') {
		$n = $_SESSION['saveFail'];
		
		echo '<script language=javascript>alert("Missing, a genotype is." + "\n" + "That it is correct, verify you must.");</script>';
		echo '<script language=javascript>document.frmAdd.txtGenotype' . $n . '.focus();</script>';

		$_SESSION['saveFail'] = '';
	}
	 
	echo "</select>";
	echo "<table border=1 rules=none frame=border>";
	echo "<tr>";
		echo "<th> <div align="center">Genotype </div></th>";
		echo "<th> <div align="center">Parents </div></th>";
		echo "<th> <div align="center">Comment </div></th>";
		echo "<th> <div align="center">Signature </div></th>";
	echo "</tr>";

	$line = $_GET["Line"];  
	if($line == 0){
		$line=1;
	}  

	for($i = 1; $i <= $line; $i++){
		echo "<tr>";
			echo "<td>";
				echo '<div align="center">';
					echo '<textarea rows="4" cols="45" type="text" style="font-size: 14px;" wrap=soft name="txtGenotype' . $i . '">';
						echo $_SESSION["txtGenotype$i"];
					echo "</textarea>";
				echo "</div>";
			echo "</td>";
		  
			echo "<td>";
				echo "<table>";
					echo "<tr>";
						echo "<td>";
							echo '<div align="left">Donor:</div>';
							echo '<input type="text" name="txtDonor' . $i . '" size="3" maxlength="5" onKeyPress="return number(event)" value="' . $_SESSION["txtDonor$i"] . ' />';
						echo "</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td>";
							echo "<div align='left'>Recipient:</div>";
							echo '<input type="text" name="txtRecipient' . $i . '" size="3" maxlength="5" onKeyPress="return number(event)" value="' . $_SESSION["txtRecipient$i"] . '" />';
						echo "</td>";
					echo "</tr>";
				echo "</table>";
			echo '</td>';
			  
			echo "<td>";
				echo '<div align="center">';
					echo '<textarea rows="4" cols="45" type="text" style="font-size: 14px;" wrap=soft name="txtComment<?=$i;?>">';
						echo $_SESSION["txtComment$i"];
					echo "</textarea>";
				echo "</div>";
			echo "</td>";

			echo '<td align="right">';
				echo '<input type="text" name="txtSignature' . $i . '" value="' . $_SESSION['signature'] . '" size="8" disabled>';
			echo '</td>';
		echo "</tr>";
	}
	echo "</table>";
	echo "<br />";
	echo '<input type="submit" class="savebutton" name="submit" value="Save">';
	echo '<input type="button" title="Reset all fields and clear memory" name="reset" value="Reset" onclick="window.location.href=\'index.php?mode=add&reset=TRUE\'">';
	echo '<input type="hidden" name="hdnLine" value="' . $i . '">';
	echo "</form>";
}
else {
	echo "<span style='color: red; font-weight: bold;'>You are not trusted to add new strains!</span>";
}
?>
