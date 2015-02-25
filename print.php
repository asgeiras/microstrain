<?php

// Here be dragons:
include ("/var/local/datalogin.php");

$con = mysql_connect("$dbhost","$dbusername","$dbuserpass");

if (!$con) {
	die('Could not connect: ' . mysql_error());
}

mysql_select_db("$dbname", $con) or die("Unable to select database");

//Begin List selected strains
if ($_GET['mode'] == 'myList') {
	$selected = $_POST['selected'];
	$list = implode(", ",$selected);
	$message = "You selected the following ";
	$sql = "select * from strains where Strain IN ($list)";
}
//End List selected strains

// Do the actual mysql query
$result = mysql_query($sql);

// Count the results and print a message: If no matches were found, back off.
$count = mysql_num_rows($result);
if ($count == '1') {
	$count = 'only one';
	$plural = '';
} else {
	$plural = 's';
}

if ($count == '0') {
	echo "<span style='color: red'>Sorry, no matches. Try again</span>";
	mysql_close();
} else {
	// Make a nice table:
    echo "<div class='noPrint'>", $message, "<strong>", $count, "</strong> strain", $plural, ":<br>";
		// Print out a table of the resulting strains
		echo "<input type='button' value='Print' onclick='window.print()'>";
	echo "</div><br>";

	echo "<div id='landscape'>";
		echo "<table class='sample' border='1'><br>";
			echo "<thead>";
				echo "<tr>";
					echo "<th >Strain</th>";
					echo "<th width='300px'>Genotype</th>";
					echo "<th>Recipient</th>";
					echo "<th>Donor</th>";
					echo "<th width='300px'>Comment</th>";
					echo "<th>Signature</th>";
				echo "</tr>";
			echo "</thead>";

			while ($row = mysql_fetch_array($result)){
				$genotype = htmlspecialchars($row['Genotype']);
				$comment = htmlspecialchars($row['Comment']);

				echo "<tr>";
					echo "<td>";    
						echo 'DA' .$row['Strain'];
					echo "</td>";

					echo "<td>"; 
						echo $genotype;
					echo "</td>";

					echo "<td align='right'>";
						if ($row['Recipient'] == "0") {
							echo "";
						} else {
							echo "DA",$row['Recipient'], "</a>";
						}
					echo "</td>";

					echo "<td align='right'>";
						if ($row['Donor'] == "0") {
							echo "";
						} else {
							echo "DA",$row['Donor'], "</a>";
						}
					echo "</td>";

					echo "<td>"; 
						echo $comment;
					echo "</td>";

					echo "<td>"; 
						echo $row['Signature'];
					echo "</td>";
				echo "</tr>";
				//Stop making the table
			}
			        
		echo "</table>";
	echo "</div>";

	// Close the mysql connection
	mysql_close();
}
?>
