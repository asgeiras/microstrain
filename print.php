<?php

$go_ahead = FALSE;

//Begin List selected strains
if ($_GET['mode'] == 'myList') {
	$selected = $_POST['selected'];
	$list = $selected;
	$message = "You selected the following ";

	if(count($selected) == 0) {
		echo "You did not select any strain<br>";
	} else {
		// Prepare parameters for strains in list
		$listInQuery = array();
		for($i = 1; $i <= count($list); $i++){
			$listInQuery[$i] = ":list_" . $i;
		}
		$listInQuery = implode(", ", $listInQuery);
		$numberOfListParameters = count($list);

		$sql = "SELECT * FROM strains WHERE Strain IN (" . $listInQuery . ")";
	}
}
//End List selected strains

// Do the actual mysql query
if($sql){
	// Prepare PDO statement
	$stmt = $dbh->prepare($sql);

	// Bind parameters
	for($i = 1; $i <= $numberOfListParameters; $i++){
		$stmt->bindParam(":list_" . $i, $list[$i-1]);
	}

	// Execute statement
	$stmt->execute();

	// Check how many rows were found
	$rows_found = $stmt->rowCount();

	if ($rows_found == '0') {
		echo "<span style='color: red'>Sorry, no matches. Try again</span>";
	}
	else {
		// Set some print variables
		if ($rows_found == '1') {
			$count = 'only one';
			$plural = '';
		} else {
			$plural = 's';
		}

		// Fetch results
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// Make a nice table:
		echo "<div class='noPrint'>" . $message . "<strong>" . $rows_found . "</strong> strain" . $plural . ":<br>";
			// Print out a table of the resulting strains
			// TOOD
			echo "<input type='button' value='Print' onclick='window.print()'>";
		echo "</div><br>";

		echo "<div id='landscape'>";
			echo "<table class='sample'><br>";
				echo "<thead>";
					echo "<tr>";
						echo "<th>Strain</th>";
						echo "<th width='300px'>Genotype</th>";
						echo "<th>Recipient</th>";
						echo "<th>Donor</th>";
						echo "<th width='300px'>Comment</th>";
						echo "<th>Signature</th>";
					echo "</tr>";
				echo "</thead>";

				foreach($result as $row){
					$genotype = htmlspecialchars($row['Genotype']);
					$comment = htmlspecialchars($row['Comment']);

					echo "<tr>";
						echo "<td>";    
							echo 'DA' . $row['Strain'];
						echo "</td>";

						echo "<td>"; 
							echo $genotype;
						echo "</td>";

						echo "<td align='right'>";
							if ($row['Recipient'] == "0") {
								echo "";
							} else {
								echo "DA" . $row['Recipient'] . "</a>";
							}
						echo "</td>";

						echo "<td align='right'>";
							if ($row['Donor'] == "0") {
								echo "";
							} else {
								echo "DA" . $row['Donor'] . "</a>";
							}
						echo "</td>";

						echo "<td>"; 
							echo $comment;
						echo "</td>";

						echo "<td>"; 
							echo $row['Signature'];
						echo "</td>";
					echo "</tr>";
				} // END foreach($result as $row)
				        
			echo "</table>";
		echo "</div>";
	}
}

?>