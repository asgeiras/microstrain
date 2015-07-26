
<?php

// Check user rights
if($_SESSION['Usertype'] == 'Superuser') {

	$helpmessage = "Before you press the save button, please verify that you changed the strain information correctly. To update the timestamp in the \"Created\" column, click the checkbox."; 

	//Begin List selected strains
	if ($_GET['mode'] == 'myList') {
		$selected = $_POST['selected'];
		$list = $selected;

		// Prepare parameters for strains in list
		$listInQuery = array();
		for($i = 1; $i <= count($list); $i++){
			$listInQuery[$i] = ":list_" . $i;
		}
		$listInQuery = implode(", ", $listInQuery);
		$numberOfListParameters = count($list);

		$message = "You selected the following ";
		$sql = "SELECT * FROM strains WHERE Strain IN (" . $listInQuery . ")";
		$sql2 = "SELECT COUNT(Strain) FROM strains WHERE Strain IN (" . $listInQuery . ")";
	}
	//End List selected strains

	if($sql){
		// Prepare PDO statements
		$stmt = $dbh->prepare($sql);
		$stmtTotal = $dbh->prepare($sql2);

		// Bind parameters
		for($i = 1; $i <= $numberOfListParameters; $i++){
			$stmt->bindParam(":list_" . $i, $list[$i-1]);
			$stmtTotal->bindParam(":list_" . $i, $list[$i-1]);
		}
		
		// Execute statement
		$stmt->execute();
		$stmtTotal->execute();

		// Fetch total number of rows
		$total_records = $stmtTotal->fetchColumn();

		// Fetch results
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$showing_records = count($result);

		// Make a nice table:
		echo $message . "<strong>" . $total_records . "</strong> strains for <span style='color: red'><strong>editing</strong></span>:";
		echo "<table class='sample' border='1'><br>";

			// Print out a table of the resulting strains:
			echo "<tr>";
				echo "<th>Strain</th>";
				echo "<th width='300px'>Genotype</th>";
				echo "<th>Parents</th>";
				echo "<th width='300px'>Comment</th>";
				echo "<th>Signature</th>";
				echo "<th>Created</th>";
			echo "</tr>";

			echo "<form name='strainstoupdate' method='post' action='index.php?mode=edit2'>";
			    echo "<input type='hidden' name='form-type' id='form-type' value='edit'>";
				echo "<input type='submit' title='Save the changes' name='save' value='save'>";
				
				$strainIndex = 0;

				// Loop through all strains
				foreach ($result as $row) {
					$genotype = htmlspecialchars($row['Genotype']);
					$comment = htmlspecialchars($row['Comment']);

					echo "<tr>";
						echo "<td>";    
							echo 'DA' . $row['Strain'];
							echo "<input type='hidden' name='Strain[" . $strainIndex . "]' value='" . $row['Strain'] . "'>";
						echo "</td>";

						echo "<td>"; 
							echo $genotype . "<br><br>";
							echo "<textarea type='text' rows=3 cols=40 name='Genotype[" . $strainIndex . "]'>" . $row['Genotype'] . "</textarea>\n";
						echo "</td>";

						echo "<td>";
							echo "Donor:<br>";
							if ($row['Donor'] == "0") {
								echo "<span style='color: grey'>(Not entered)</span><br>";
								echo "DA<input type='text' size=5 name='Donor[" . $strainIndex . "]' value=''>\n<br><br>";
							} else {
								echo "<a href=index.php?mode=myNum&myNum=" . $row['Donor'] . " title='View DA" . $row['Donor'] . " in a new tab'>DA" . $row['Donor'] . "</a><br>";
								echo "DA<input type='text' size=5 name='Donor[" . $strainIndex . "]' value='" . $row['Donor'] . "'><br><br>\n";
							}

							echo "Recipient:<br>";
							if ($row['Recipient'] == "0") {
								echo "";
								echo "DA<input type='text' size=5 name='Recipient[" . $strainIndex . "]' value=''>\n";
							} else {
								echo "<a href=index.php?mode=myNum&myNum=" . $row['Recipient'] . " title='View DA" . $row['Recipient'] . " in a new tab'>DA" . $row['Recipient'] . "</a><br>";
								echo "DA<input type='text' size=5 name='Recipient[" . $strainIndex . "]' value='" . $row['Recipient'] . "'>\n";
							}
						echo "</td>";

						echo "<td>"; 
							echo $comment . "<br><br>";
							echo "<textarea type='text' rows=3 cols=40 name='Comment[" . $strainIndex . "]'>" . $row['Comment'] . "</textarea>\n";
						echo "</td>";

						echo "<td>";
							if($row['Signature'] == '') {
								echo "<span style='color: grey'>(Not entered)</span><br>";
								echo "<input type='text' size='3' name='Signature[" . $strainIndex . "]' value='" . $user . "'>";
							} else {
								echo $row['Signature'];
								echo "<input type='text' size='3' name='Signature[" . $strainIndex . "]' value='" . $row['Signature'] . "'>";
							}
						echo "</td>";

						echo "<td>"; 
							if ($row['Created'] == "0000-00-00 00:00:00") {
								echo "<span style='color: grey'>(Not entered)</span><br>";
							} else {
								echo $row['Created'] . "<br>";
							}
							echo "Update<input type='checkbox' size='3' name='Created[" . $strainIndex . "]' value='" . $today . "'>";
							echo "<input type='hidden' name='CreatedDate[" . $strainIndex . "]' value='" . $row['Created'] . "'>";
						echo "</td>";
					echo "</tr>";
					//Stop making the table

					$strainIndex++;
				} // End foreach ($result as $row)

			echo "</form>";
		echo "</table>";
		
		// Close the mysql connection
		$stmt->closeCursor();
		$stmtTotal->closeCursor();

	} // End if($sql)
} // End if($_SESSION['Usertype'] == 'Superuser')
else {
	echo "<span style='color: red; font-weight: bold;'>You are not trusted to edit strains!</span>";
}
?>
