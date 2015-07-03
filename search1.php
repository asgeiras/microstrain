<?php

//Begin Search for keywords//
if($_GET['type'] == 'word') {
	$sign1 = $_SESSION['sign1'];

	$term1 = $_POST['term1'];
	$term2 = $_POST['term2'];
	$term3 = $_POST['term3'];
	$term4 = $_POST['term4'];

	$notterm1 = $_POST['notterm1'];
	$notterm2 = $_POST['notterm2'];
	$notterm3 = $_POST['notterm3'];
	$notterm4 = $_POST['notterm4'];

	/* moved to actions.php
	If all search boxes are empty, we do nothing, otherwise we assign the variables and do the search:
	if(empty($_POST['term1']) && empty($_POST['term2']) && empty($_POST['term3']) && empty($_POST['term4']) && empty($_POST['notterm1']) && empty($_POST['notterm2']) && empty($_POST['notterm3']) && empty($_POST['notterm4']) && empty($_POST['sign1'])) {
		header("Location: index.php?mode=list&error=error");
	}*/

	// If all but signature fields are empty
	if(empty($_POST['term1']) && empty($_POST['term2']) && empty($_POST['term3']) && empty($_POST['term4']) && empty($_POST['notterm1']) && empty($_POST['notterm2']) && empty($_POST['notterm3']) && empty($_POST['notterm4']) && ($_POST['sign1'])) {
		
		// Build SQL statements, one limited and one that counts unlimited
		$sql = "SELECT * FROM strains WHERE Signature LIKE :sign1 ORDER BY Strain ASC LIMIT :startval, :limitval";
		$sql2 = "SELECT COUNT(Strain) FROM strains WHERE Signature LIKE :sign1";

		//TODO
		//Replaced 3/3 by Per Enström
		//$sql = "select * from strains where Signature like '%$sign1%' ORDER BY Strain ASC LIMIT $startval, $limit";

		$message = "Your search for the above keyword in <strong>Signature</strong> resulted in ";
	} else {
		$searchfield = $_POST['check'];

		//If not all search boxes are filled, reassign the variables (necessary for highlighting matching keywords in the table)
		if($term3 == '') {
			$term3 = $term4;
			$term4 = '';
		}
		if($term2 == '') {
			$term2 = $term3;
			$term3 = $term4;
			$term4 = '';
		}
		if($term1 == '') {
			$term1 = $term2;
			$term2 = $term3;
			$term3 = $term4;
			$term4 = '';
		}

		if(($searchfield['genotype'] == 'genotype') && ($searchfield['comment'] != 'comment')) {
			$field1 = "Genotype";
			$field2 = "Genotype";
			$check1 = 'checked="checked"';
			$check2 = '';
			$message = "Your search for the above keywords in <strong>Genotype</strong> resulted in ";
		}
		elseif(($searchfield['genotype'] == 'genotype') && ($searchfield['comment'] == 'comment')) {
			$field1 = "Genotype";
			$field2 = "Comment";
			$check1 = 'checked="checked"';
			$check2 = 'checked="checked"';
			$message = "Your search for the above keywords in <strong>Genotype</strong> or <strong>Comment</strong> resulted in ";
		}
		elseif(($searchfield['genotype'] != 'genotype') && ($searchfield['comment'] == 'comment')) {
			$field1 = "Comment";
			$field2 = "Comment";
			$check1 = '';
			$check2 = 'checked="checked"';
			$message = "Your search for the above keywords in <strong>Comment</strong> resulted in ";
		} else {
			$check1 = 'checked="checked"';
			$check2 = '';
		}

		// Add the additional search parameters to the mysql query if more than one field is used:
		if($term1 == '') {
			//TODO
			print("No keyword entered woop");
			die();
			//header("Location: index.php?mode=list&error=error");
		} else {

			if($term2 == '') {
				$incl2 = '';
			} else {
				$incl2 = "AND ($field1 LIKE :term2 OR $field2 LIKE :commentterm2)";
			}

			if($term3 == '') {
				$incl3 = '';
			} else {
				$incl3 = "AND ($field1 LIKE :term3 OR $field2 LIKE :commentterm3)";
			}

			if($term4 == '') {
				$incl4 = '';
			} else {
				$incl4 = "AND ($field1 LIKE :term4 OR $field2 LIKE :commentterm4)";
			}

			if($notterm1 == '') {
				$excl1 = '';
			} else {
				$excl1 = "AND ($field1 NOT LIKE :notterm1 AND $field2 NOT LIKE :commentnotterm1)";
			}

			if($notterm2 == '') {
				$excl2 = '';
			} else {
				$excl2 = "AND ($field1 NOT LIKE :notterm2 AND $field2 NOT LIKE :commentnotterm2)";
			}

			if($notterm3 == ''){
				$excl3 = '';
			} else {
				$excl3 = "AND ($field1 NOT LIKE :notterm3 AND $field2 NOT LIKE :commentnotterm3)";
			}

			if($notterm4 == ''){
				$excl4 = '';
			} else {
				$excl4 = "AND ($field1 NOT LIKE :notterm4 AND $field2 NOT LIKE :commentnotterm4)";
			}

			if($sign1 == ''){
				$sign = '';
			} else {
				$sign = " AND Signature LIKE :sign1";
			}

			$sql = "SELECT * FROM strains WHERE ($field1 LIKE :term1 OR $field2 LIKE :commentterm1) $incl2 $incl3 $incl4 $excl1 $excl2 $excl3 $excl4 $sign ORDER BY Strain ASC LIMIT :startval, :limitval";
			$sql2 = "SELECT COUNT(Strain) FROM strains WHERE ($field1 LIKE :term1 OR $field2 LIKE :commentterm1) $incl2 $incl3 $incl4 $excl1 $excl2 $excl3 $excl4 $sign";
		}
	}
}
//End Search for keywords//

//Begin Search for Strain numbers//
if($_GET['type'] == 'number') {

	//$myList = $_GET['myList'];
	$message = "Your search resulted in ";

	//If all search variables are empty, we do nothing, otherwise we do the search:
	if(!(empty($minNum) && empty($maxNum) && empty($sign1))) {

		// If only signature box is filled
		if(empty($minNum) && empty($maxNum) && !empty($sign1)) {
			$sql = "SELECT * FROM strains WHERE Signature LIKE :sign1 ORDER BY Strain ASC LIMIT :startval, :limitval";
			$sql2 = "SELECT COUNT(Strain) FROM strains WHERE Signature LIKE :sign1";
			$message = "Your search for the above keyword in <strong>Signature</strong> resulted in ";
		} else {
			// If both min and max are filled, and min is larger than max: invert the search
			if (!empty($minNum) && !empty($maxNum) != '' && $minNum > $maxNum) {
				$sql = "SELECT * FROM strains WHERE Strain >= :maxNum AND Strain <= :minNum AND Signature LIKE :sign1 ORDER BY Strain ASC LIMIT :startval, :limitval";
				$sql2 = "SELECT COUNT(Strain) FROM strains WHERE Strain >= :maxNum AND Strain <= :minNum AND Signature LIKE :sign1";
			}

			// If both min and max are filled, and max is -- as intended -- larger than min: search normally
			if (!empty($minNum) && !empty($maxNum) && $minNum <= $maxNum){
				$sql = "SELECT * FROM strains WHERE Strain >= :minNum AND Strain <= :maxNum AND Signature LIKE :sign1 ORDER BY Strain ASC LIMIT :startval, :limitval";
				$sql2 = "SELECT COUNT(Strain) FROM strains WHERE Strain >= :minNum AND Strain <= :maxNum AND Signature LIKE :sign1";
			}

			// If max is empty while min is filled: only find the entered strain
			if (!empty($minNum) && empty($maxNum)) {
				$sql = "SELECT * FROM strains WHERE Strain = :minNum AND Signature LIKE :sign1 ORDER BY Strain ASC LIMIT :startval, :limitval";
				$sql2 = "SELECT COUNT(Strain) FROM strains WHERE Strain = :minNum AND Signature LIKE :sign1";
			}
			
			// If min is empty while max is filled: only find the entered strain
			if (empty($minNum) && !empty($maxNum)) {
				$sql = "SELECT * FROM strains WHERE Strain = :maxNum AND Signature LIKE :sign1 ORDER BY Strain ASC LIMIT :startval, :limitval";
				$sql2 = "SELECT COUNT(Strain) FROM strains WHERE Strain = :maxNum AND Signature LIKE :sign1";
			}
		}
	}
}
//End Search for strain numbers//

//Show Donor/ recipient from clickable link in table:
if ($_GET['mode'] == 'myNum') {
	$myNum = $_GET['myNum'];
	$sql = "SELECT * FROM strains WHERE Strain = ':myNum'";
}
//End Show Donor/ recipient from clickable link in table

//Begin List selected strains
if ($_GET['mode'] == 'myList') {
	$selected = $_POST['selected'];
	$list = implode(", ",$selected);
	$message = "You selected the following ";

	if($list == '') {
		echo "You did not select any strain<br>";
	} else {
		$sql = "SELECT * FROM strains WHERE Strain IN (:list)";
	}
}
//End List selected strains

//Begin List inserted strains
if ($_GET['mode'] == 'add3') {
	$inserted = $_POST['inserted'];
	$list = implode(", ",$inserted);
	$message = "You successfully saved the following "; 
	$sql = "SELECT * FROM strains WHERE Strain IN (:list)";
}
//End List inserted strains

//Begin List edited strains
if ($_GET['mode'] == 'edit2') {
	$edited = $_POST['selected'];
	$list = implode(", ",$edited);
	$message = "Please check the following ";
	$sql = "SELECT * FROM strains WHERE Strain IN (:list)";
}
//End List inserted strains

// Do the actual mysql query
if($sql){
	// Prepare PDO statements
	$stmt = $dbh->prepare($sql);
	$stmtTotal = $dbh->prepare($sql2);

	// Prepare some variables for binding
	$sign1param = '%' . $sign1 . '%';

	$term1 = '%' . $term1 . '%';
	$term2 = '%' . $term2 . '%';
	$term3 = '%' . $term3 . '%';
	$term4 = '%' . $term4 . '%';
	$commentTerm1 = $term1;
	$commentTerm2 = $term2;
	$commentTerm3 = $term3;
	$commentTerm4 = $term4;

	$notterm1 = '%' . $notterm1 . '%';
	$notterm2 = '%' . $notterm2 . '%';
	$notterm3 = '%' . $notterm3 . '%';
	$notterm4 = '%' . $notterm4 . '%';
	$commentNotterm1 = $notterm1;
	$commentNotterm2 = $notterm2;
	$commentNotterm3 = $notterm3;
	$commentNotterm4 = $notterm4;

	// Bind parameters for total number of rows
	$stmtTotal->bindParam(":sign1", $sign1param);

	$stmtTotal->bindParam(":term1", $term1);
	$stmtTotal->bindParam(":term2", $term2);
	$stmtTotal->bindParam(":term3", $term3);
	$stmtTotal->bindParam(":term4", $term4);
	$stmtTotal->bindParam(":commentterm1", $commentTerm1, PDO::PARAM_STR);
	$stmtTotal->bindParam(":commentterm2", $commentTerm2, PDO::PARAM_STR);
	$stmtTotal->bindParam(":commentterm3", $commentTerm3, PDO::PARAM_STR);
	$stmtTotal->bindParam(":commentterm4", $commentTerm4, PDO::PARAM_STR);

	$stmtTotal->bindParam(":notterm1", $notterm1);
	$stmtTotal->bindParam(":notterm2", $notterm2);
	$stmtTotal->bindParam(":notterm3", $notterm3);
	$stmtTotal->bindParam(":notterm4", $notterm4);
	$stmtTotal->bindParam(":commentnotterm1", $commentNotterm1, PDO::PARAM_STR);
	$stmtTotal->bindParam(":commentnotterm2", $commentNotterm2, PDO::PARAM_STR);
	$stmtTotal->bindParam(":commentnotterm3", $commentNotterm3, PDO::PARAM_STR);
	$stmtTotal->bindParam(":commentnotterm4", $commentNotterm4, PDO::PARAM_STR);

	$stmtTotal->bindParam(":minNum", $minNum);
	$stmtTotal->bindParam(":maxNum", $maxNum);

	$stmtTotal->bindParam(":myNum", $myNum);

	$stmtTotal->bindParam(":list", $list);

	//Exectue total statement
	$stmtTotal->execute();

	// Fetch total number of rows
	$total_records = $stmtTotal->fetchColumn();

	// Convert pages to starting row number
	if ($total_records > $limit) {
		$pages = ceil($total_records / $limit);

		// Set page number to highest possible if too big
		if($page > $pages){
			$page = $pages;
		}

		$startval = ($page - 1) * $limit;
	} else {
		$pages = 1;
		$startval = 0;
	}

	echo "<p>Pages: $pages</p>";
	echo "<p>Page: $page</p>";
	echo "<p>Startval: $startval</p>";
	echo "<p>Limit: $limit</p>";

	// Bind parameters for limited rows
	$stmt->bindParam(':sign1', $sign1param);

	$stmt->bindParam(':startval', $startval, PDO::PARAM_INT);
	$stmt->bindParam(':limitval', $limit, PDO::PARAM_INT);

	$stmt->bindParam(":term1", $term1, PDO::PARAM_STR);
	$stmt->bindParam(":term2", $term2, PDO::PARAM_STR);
	$stmt->bindParam(":term3", $term3, PDO::PARAM_STR);
	$stmt->bindParam(":term4", $term4, PDO::PARAM_STR);
	$stmt->bindParam(":commentterm1", $commentTerm1, PDO::PARAM_STR);
	$stmt->bindParam(":commentterm2", $commentTerm2, PDO::PARAM_STR);
	$stmt->bindParam(":commentterm3", $commentTerm3, PDO::PARAM_STR);
	$stmt->bindParam(":commentterm4", $commentTerm4, PDO::PARAM_STR);

	$stmt->bindParam(":notterm1", $notterm1, PDO::PARAM_STR);
	$stmt->bindParam(":notterm2", $notterm2, PDO::PARAM_STR);
	$stmt->bindParam(":notterm3", $notterm3, PDO::PARAM_STR);
	$stmt->bindParam(":notterm4", $notterm4, PDO::PARAM_STR);
	$stmt->bindParam(":commentnotterm1", $commentNotterm1, PDO::PARAM_STR);
	$stmt->bindParam(":commentnotterm2", $commentNotterm2, PDO::PARAM_STR);
	$stmt->bindParam(":commentnotterm3", $commentNotterm3, PDO::PARAM_STR);
	$stmt->bindParam(":commentnotterm4", $commentNotterm4, PDO::PARAM_STR);

	$stmt->bindParam(":minNum", $minNum);
	$stmt->bindParam(":maxNum", $maxNum);

	$stmt->bindParam(":myNum", $myNum);

	$stmt->bindParam(":list", $list);

	// Execute statement
	$stmt->execute();

	// Fetch results
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$showing_records = count($result);

	// Troubleshooting
	echo $sql;
	echo "<br>";
	echo $sql2;
	echo "<br>";
	echo "sign1: $sign1";
	echo "<br>";
	echo "startval: $startval";
	echo "<br>";
	echo "limitval: $limit";
	echo "<br>";
	echo "term1: $term1";
	echo "<br>";
	echo "notterm1: $notterm1";
	echo "<br>";
	echo "comterm1: $commentTerm1";
	echo "<br>";
	echo "comnotterm1: $commentNotterm1";
	echo "<br>";
	echo "field1: $field1";
	echo "<br>";
	echo "minNum: $minNum";
	echo "<br>";
	echo "maxNum: $maxNum";
	echo "<br>";
	echo "field2: $field2";
	echo "<br>";
	echo "total_records: $total_records<br>";
	echo "total_records session: " . $_SESSION['total_records'] . "<br>";
	//print("result:<pre>".print_r($result, true)."</pre>");

	$_SESSION['total_records'] = $total_records;

	// Set result text and plural ending
	if ($total_records == '1') {
		$message_records = 'only one';
		$plural = '';
	} else {
		$message_records = $total_records;
		$plural = 's';
	}

	// Print result text
	if ($total_records == '0') {
		echo "<span style='color: red'>Sorry, no matches. Try again</span>";
	} else {
		$helpmessage2 = "<br><span style='font-weight: bold; color: red;'>Tip:</span> To see the information about a donor or recipient, click the link in the corresponding cell. Use ctrl+click (cmd+click in Mac OS) to open in a new tab.";
	    echo $message . "<span style='font-weight: bold'>" . $total_records . "</span> strain" . $plural . ". ";

		//Multi page:
		if ($total_records > $limit) {
			echo "Showing <span style='font-weight: bold'> " . $showing_records . "</span> strains per page starting at no. <span style='font-weight: bold'>" . ($startval+1) . "</span>:<br>"; 

			echo "<br>Page <span style='font-weight: bold'>" . $page . "</span> of <span style='font-weight: bold'>" . $pages . "</span>. ";
			
			// Print pagination links
			for($n = 1; $n <= $pages; $n++) {

				if($_GET['type'] == 'word'){
					if ($n != $page){
						echo ' <a href="javascript:pageforward(\'' . $n . '\');">' . $n . '</a> ';
					} else {
						echo " <span style='font-weight: bold; color: blue;'>" . $n . "</span> ";
					} 
				}
				elseif($_GET['type'] == 'number'){
					if ($n != $page){
						echo ' <a href="?mode=search&amp;type=number&amp;search=1&amp;minNum=' . $minNum . '&amp;maxNum=' . $maxNum . '&amp;sign1=' . $sign1 . '&amp;limit=' . $limit . '&amp;page=' . $n . '">' . $n . '</a> ';
					} else {
						echo " <span style='font-weight: bold; color: blue;'>" . $n . "</span> ";
					} 
				}
			}
		}

		// Print out a table of the resulting strains
		echo "<table class='sample'>";

			echo "<tr>";
				echo "<th>Strain</th>";
				echo "<th style='width: 300px'>Genotype</th>";
				echo "<th>Recipient</th>";
				echo "<th>Donor</th>";
				echo "<th style='width: 300px'>Comment</th>";
				echo "<th>Signature</th>";
				echo "<th>Created</th>";
				echo "<th>";
					echo "Select";
					echo "<input type='hidden' name='toggle' value='set' />";
					echo "<input type='button' name='CheckAll' value='All' onClick='checkAll(document.selectRow)'>";
					echo "<input type='button' name='UnCheckAll' value='None' onClick='uncheckAll(document.selectRow)'>";
				echo "</th>";
			echo "</tr>";
			// WORK DONE TO HERE


			echo "<form action='index.php?mode=myList' name='selectRow' method='post'>";
				echo "<input type='submit' title='Show a table of only the selected strains' name='show' value='Show selected'/>";

				if($_SESSION['Usertype'] == 'Superuser') {
					echo "<input type='submit' title='Edit the selected strains' name='edit' value='Edit selected'/>";
				}

				echo "<input type='submit' title='Open the selected strains in a printer-friendly table' name='print' value='print selected'/>";

				// Prepare CSV header
				$csv_hdr .= "Strain;Genotype;Recipient;Donor;Comment;Signature;Created\n";

				// Loop through all strains
				foreach ($result as $row) {
					
					// Convert html characters to printable
					$genotype = htmlspecialchars($row['Genotype']);
					$genotype1 = htmlspecialchars($row['Genotype']);
					$comment = htmlspecialchars($row['Comment']);
					$comment1 = htmlspecialchars($row['Comment']);

					// Display every match to the search word with colored text:
					// Genotype:
					if(($_SESSION['genotype'] == 'genotype')){

						if($_SESSION['term1'] != '') {
							$genotype = preg_replace('/' . preg_quote($_SESSION['term1'], '/') . '/i', "¢$0¦", $genotype);
						}

						if($_SESSION['term2'] != '') {
							$genotype = preg_replace('/'.preg_quote($_SESSION['term2'], '/').'/i', "¢$0¦", $genotype);
						}

						if($_SESSION['term3'] != '') {
							$genotype = preg_replace('/'.preg_quote($_SESSION['term3'], '/').'/i', "¢$0¦", $genotype);
						}

						if($_SESSION['term4'] != '') {
							$genotype = preg_replace('/'.preg_quote($_SESSION['term4'], '/').'/i', "¢$0¦", $genotype);
						}

						$genotype = preg_replace('/'.preg_quote('¢', '/').'/i', "<span style='color: blue; font-weight: bold;'>", $genotype);
						$genotype = preg_replace('/'.preg_quote('¦', '/').'/i', "</span>", $genotype);
					}

					// Comment:
					if(($_SESSION['comment'] == 'comment')){

						if($_SESSION['term1'] != '') {
							$comment = preg_replace('/'.preg_quote($_SESSION['term1'], '/').'/i', "¢$0¦", $comment);
						}

						if($_SESSION['term2'] != '') {
							$comment = preg_replace('/'.preg_quote($_SESSION['term2'], '/').'/i', "¢$0¦", $comment);
						}

						if($_SESSION['term3'] != '') {
							$comment = preg_replace('/'.preg_quote($_SESSION['term3'], '/').'/i', "¢$0¦", $comment);
						}

						if($_SESSION['term4'] != '') {
							$comment = preg_replace('/'.preg_quote($_SESSION['term4'], '/').'/i', "¢$0¦", $comment);
						}

						$comment = preg_replace('/'.preg_quote('¢', '/').'/i', "<span style='color: blue; font-weight: bold;'>", $comment);
						$comment = preg_replace('/'.preg_quote('¦', '/').'/i', "</span>", $comment);
					}

					echo "<tr>";
						echo "<td>";    
							echo 'DA' . $row['Strain'];
							$csv_output .= "DA" . $row['Strain'] . "; ";
						echo "</td>";

						echo "<td>"; 
							echo $genotype;
							$csv_output .= $genotype1 . "; ";
						echo "</td>";

						echo "<td align='right'>";
							if ($row['Recipient'] == "0") {
								echo "";
								$csv_output .= "; ";
							} else {
								echo "<a href=index.php?mode=myNum&myNum=" . $row['Recipient'] . " title='View DA" . $row['Recipient']. " in a new tab'>DA" . $row['Recipient'] . "</a>";
								$csv_output .= "DA" . $row['Recipient'] . "; ";
							}
						echo "</td>";

						echo "<td align='right'>";
							if ($row['Donor'] == "0") {
								echo "";
								$csv_output .= "; ";
							} else {
								echo "<a href=index.php?mode=myNum&myNum=" . $row['Donor'] . " title='View DA" . $row['Donor'] . " in a new tab'>DA" . $row['Donor'] . "</a>";
								$csv_output .= "DA" . $row['Donor'] . "; ";
							}
						echo "</td>";

						echo "<td>"; 
							echo $comment;
							$csv_output .= $comment1 . "; ";
						echo "</td>";

						echo "<td>"; 
							echo $row['Signature'];
							$csv_output .= $row['Signature'] . "; ";
						echo "</td>";

						echo "<td>"; 
							if ($row['Created'] == "0000-00-00 00:00:00"){
								echo '';
								$csv_output .= ";\n";
							} else {
								echo $row['Created'];
								$csv_output .= $row['Created'] . "\n";
							}
						echo "</td>";
						echo "<td>";
							echo "<div align=center><input type=checkbox name='selected[]' value=" . $row['Strain'] . "></div>";
						echo "</td>";
					echo "</tr>";
					//Stop making the table
				}

			echo "</form>";
		echo "</table>";

		// Close the mysql connection
		$stmt->closeCursor();
		$stmtTotal->closeCursor();

		if ($total_records > $limit) {

			echo "<br>Page <span style='font-weight: bold'>" . $page . "</span> of <span style='font-weight: bold'>" . $pages . "</span>.";

			for($n = 1; $n <= $pages; $n++) {
				if ($n != $page){
					echo ' <a href="javascript:pageforward(', "'", $n, "'", ');">', $n,'</a> ';
				} else {
					echo " <span style='font-weight: bold; color: blue;'>", $n, "</span> ";
				} 
			}
		}

		echo '<form name="export" action="export.php" method="post">';
			echo '<input type="submit" value="Export table to CSV">';
			echo '<input type="hidden" value="' . $csv_hdr . '" name="csv_hdr">';
			echo '<input type="hidden" value="' . $csv_output . '" name="csv_output">';
		echo "</form>";
	}

} else {
	echo "<span style='color: red'><strong>You need to provide at least one keyword!</strong></span><br>";
}
?>