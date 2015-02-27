<?php

// Limit the number of entries shown in the table:
if (isset($_POST['limit'])) {
	$limit = $_POST['limit'];
} else { 
	$limit = 100; 
}

if (isset($_GET['startval'])) {
	$startval = ($_GET['startval']-1);
}
elseif (isset($_POST['startval'])) {
	$startval = ($_POST['startval']-1);
} else { 
	$startval = 0;
}

//Begin Search for keywords//
if($_GET['type'] == 'word') {
	$sign1 = $_SESSION['sign1'];

	/* moved to actions.php
	If all search boxes are empty, we do nothing, otherwise we assign the variables and do the search:
	if(empty($_POST['term1']) && empty($_POST['term2']) && empty($_POST['term3']) && empty($_POST['term4']) && empty($_POST['notterm1']) && empty($_POST['notterm2']) && empty($_POST['notterm3']) && empty($_POST['notterm4']) && empty($_POST['sign1'])) {
		header("Location: index.php?mode=list&error=error");
	}*/

	if(empty($_POST['term1']) && empty($_POST['term2']) && empty($_POST['term3']) && empty($_POST['term4']) && empty($_POST['notterm1']) && empty($_POST['notterm2']) && empty($_POST['notterm3']) && empty($_POST['notterm4']) && ($_POST['sign1'])) {
		$sql = "select * from strains where Signature like '%$sign1%' ORDER BY Strain ASC LIMIT $startval, $limit";
		$sql2 = "select COUNT(Strain) from strains where Signature like '%$sign1%'";
		$message = "Your search for the above keyword in <strong>Signature</strong> resulted in ";
	} else {
		$searchfield = $_POST['check'];
		$term1 = mysql_real_escape_string($_SESSION['term1']);
		$term2 = mysql_real_escape_string($_SESSION['term2']);
		$term3 = mysql_real_escape_string($_SESSION['term3']);
		$term4 = mysql_real_escape_string($_SESSION['term4']);

		$notterm1 = mysql_real_escape_string($_SESSION['notterm1']); 
		$notterm2 = mysql_real_escape_string($_SESSION['notterm2']);
		$notterm3 = mysql_real_escape_string($_SESSION['notterm3']);
		$notterm4 = mysql_real_escape_string($_SESSION['notterm4']);

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
			header("Location: index.php?mode=list&error=error");
		}

		if($term1 != '') {

			if($term2 == '') {
				$incl2 = '';
			} else {
				$incl2 = "and ($field1 like '%$term2%' or $field2 like '%$term2%')";
			}

			if($term3 == '') {
				$incl3 = '';
			} else {
				$incl3 = "and ($field1 like '%$term3%' or $field2 like '%$term3%')";
			}

			if($term4 == '') {
				$incl4 = '';
			} else {
				$incl4 = "and ($field1 like '%$term4%' or $field2 like '%$term4%')";
			}

			if($notterm1 == '') {
				$excl1 = '';
			} else {
				$excl1 = "and ($field1 not like '%$notterm1%' and $field2 not like '%$notterm1%')";
			}

			if($notterm2 == '') {
				$excl2 = '';
			} else {
				$excl2 = "and ($field1 not like '%$notterm2%' and $field2 not like '%$notterm2%')";
			}

			if($notterm3 == ''){
				$excl3 = '';
			} else {
				$excl3 = "and ($field1 not like '%$notterm3%' and $field2 not like '%$notterm3%')";
			}

			if($notterm4 == ''){
				$excl4 = '';
			} else {
				$excl4 = "and ($field1 not like '%$notterm4%' and $field2 not like '%$notterm4%')";
			}

			if($sign1 == ''){
				$sign = '';
			} else {
				$sign = " and Signature like '%$sign1%'";
			}

			$sql = "select * from strains where ($field1 like '%$term1%' or $field2 like '%$term1%') $incl2 $incl3 $incl4 $excl1 $excl2 $excl3 $excl4 $sign ORDER BY Strain ASC LIMIT $startval, $limit";

			$sql2 = "select COUNT(Strain) from strains where ($field1 like '%$term1%' or $field2 like '%$term1%') $incl2 $incl3 $incl4 $excl1 $excl2 $excl3 $excl4 $sign";
		}
	}
}
//End Search for keywords//

//Begin Search for Strain numbers//
if($_GET['type'] == 'number') {
	$minNum = $_SESSION['minNum'];
	$maxNum = $_SESSION['maxNum'];
	$sign1 = $_SESSION['sign1'];
	$myList = $_GET['myList'];
	$message = "Your search resulted in ";

	//If all search boxes are empty, we do nothing, otherwise we do the search:
	if(!(empty($_POST['minNum']) && empty($_POST['maxNum']) && empty($_POST['sign1']))) {

		if(empty($_POST['minNum']) && empty($_POST['maxNum']) && ($_POST['sign1'])) {
			$sql = "select * from strains where Signature like '%$sign1%' ORDER BY Strain ASC LIMIT $startval, $limit";
			$sql2 = "select COUNT(Strain) from strains where Signature like '%$sign1%'";
			$message = "Your search for the above keyword in <strong>Signature</strong> resulted in ";
		} else {
			if ($_POST['minNum'] != '' && $_POST['maxNum'] !='' && $_POST['minNum'] > $_POST['maxNum']) {
				$sql = "select * from strains where Strain >= '$maxNum' and Strain <= '$minNum' and Signature like '%$sign1%' ORDER BY Strain ASC LIMIT $startval, $limit";
				$sql2 = "select COUNT(Strain) from strains where Strain >= '$maxNum' and Strain <= '$minNum' and Signature like '%$sign1%'";
			}

			if ($_POST['minNum'] != '' && $_POST['maxNum'] !='' && $_POST['minNum'] <= $_POST['maxNum']){
				$sql = "select * from strains where Strain >= '$minNum' and Strain <= '$maxNum' and Signature like '%$sign1%' ORDER BY Strain ASC LIMIT $startval, $limit";
				$sql2 = "select COUNT(Strain) from strains where Strain >= '$minNum' and Strain <= '$maxNum' and Signature like '%$sign1%'";
			}

			if ($_POST['minNum'] != '' && $_POST['maxNum'] == '') {
				$sql = "select * from strains where Strain = '$minNum' and Signature like '%$sign1%' ORDER BY Strain ASC LIMIT $startval, $limit";
				$sql2 = "select COUNT(Strain) from strains where Strain = '$minNum' and Signature like '%$sign1%'";
			}
			
			if ($_POST['minNum'] == '' && $_POST['maxNum'] != '') {
				$sql = "select * from strains where Strain = '$maxNum' and Signature like '%$sign1%' ORDER BY Strain ASC LIMIT $startval, $limit";
				$sql2 = "select COUNT(Strain) from strains where Strain = '$maxNum' and Signature like '%$sign1%'";
			}
		}
	}
}
//End Search for strain numbers//

//Show Donor/ recipient from clickable link in table:
if ($_GET['mode'] == 'myNum') {
	$myNum = $_GET['myNum'];
	$sql = "select * from strains where Strain = '$myNum'";
	$sql2 = "select COUNT(Strain) from strains where Strain = '$myNum'";
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
		$sql = "select * from strains where Strain IN ($list)";
	}
}
//End List selected strains

//Begin List inserted strains
if ($_GET['mode'] == 'add3') {
	$inserted = $_POST['inserted'];
	$list = implode(", ",$inserted);
	$message = "You successfully saved the following "; 
	$sql = "select * from strains where Strain IN ($list)";
}
//End List inserted strains

//Begin List edited strains
if ($_GET['mode'] == 'edit2') {
	$edited = $_POST['selected'];
	$list = implode(", ",$edited);
	$message = "Please check the following ";
	$sql = "select * from strains where Strain IN ($list)";
}
//End List inserted strains

	// Do the actual mysql query
	if($sql){
		$result = mysql_query($sql,$con);
		// Count the results and print a message: If no matches were found, back off.
		$rs_result = mysql_query($sql2,$con);
		$row = mysql_fetch_row($rs_result); 
		$total_records = $row[0];

		$_SESSION['total_records'] = $total_records;
		$showing_records = mysql_num_rows($result);

		if ($total_records == '1') {
			$message_records = 'only one';
			$plural = '';
		} else {
			$message_records = $total_records;
			$plural = 's';
		}

		if ($total_records == '0') {
			echo "<span style='color: red'>Sorry, no matches. Try again</span>";
			mysql_close();
		} else {
			$helpmessage2 = "<br><span style='font-weight: bold; color: red;'>Tip:</span> To see the information about a donor or recipient, click the link in the corresponding cell. Use ctrl+click (cmd+click in Mac OS) to open in a new tab.";
		    echo $message, "<span style='font-weight: bold'>", $total_records, "</span> strain", $plural, ". ";

		//Multi page:
		if ($total_records > $limit) {
			echo "Showing <span style='font-weight: bold'> ", $showing_records, "</span> strains per page starting at no. <span style='font-weight: bold'>", ($startval+1), "</span>:<br>"; 

			$pages = ceil($total_records / $limit);
			$page = $_POST['page'];

			echo "<br>Page <span style='font-weight: bold'>",$page, "</span> of <span style='font-weight: bold'>", $pages, "</span>. ";
			
			for($n = ; $n <= $pages; $n++) {
				if ($n != $page){
					echo ' <a href="javascript:pageforward(', "'", $n, "'", ');">', $n,'</a> ';
				} else {
					echo " <span style='font-weight: bold; color: blue;'>", $n, "</span> ";
				} 
			}
		}

		// Print out a table of the resulting strains
		echo "<table class='sample' border='1'><br>";

			echo "<tr>";
				echo "<th>Strain</th>";
				echo "<th width='300px'>Genotype</th>";
				echo "<th>Recipient</th>";
				echo "<th>Donor</th>";
				echo "<th width='300px'>Comment</th>";
				echo "<th>Signature</th>";
				echo "<th>Created</th>";
				echo "<th>Select<input type='hidden' name='toggle' value='set' />";
					echo "<input type='button' name='CheckAll' value='All' onClick='checkAll(document.selectRow)'>";
					echo "<input type='button' name='UnCheckAll' value='None' onClick='uncheckAll(document.selectRow)'>";
				echo "</th>";
			echo "</tr>";

			echo "<form action='index.php?mode=myList' name='selectRow' method='post'><input type='submit' title='Show a table of only the selected strains' name='show' value='Show selected'/>";

				if($_SESSION['Usertype'] == 'Superuser') {
					echo "<input type='submit' title='Edit the selected strains' name='edit' value='Edit selected'/>";
				}

				echo "<input type='submit' title='Open the selected strains in a printer-friendly table' name='print' value='print selected'/>";

				$csv_output .= "Strain;Genotype;Recipient;Donor;Comment;Signature;Created\n";
				while ($row = mysql_fetch_array($result)){
					$genotype = htmlspecialchars($row['Genotype']);
					$genotype1 = htmlspecialchars($row['Genotype']);
					$comment = htmlspecialchars($row['Comment']);
					$comment1 = htmlspecialchars($row['Comment']);

					// Display every match to the search word with colored text:
					// Genotype:
					if(($_SESSION['genotype'] == 'genotype')){

						if($_SESSION['term1'] != '') {
							$genotype = preg_replace('/'.preg_quote($_SESSION['term1'], '/').'/i', "¢$0¦", $genotype);
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
							echo 'DA' .$row['Strain'];
							$csv_output .= "DA". $row['Strain'] . "; ";
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
								echo "<a href=index.php?mode=myNum&myNum=". $row['Recipient']. " title='View DA". $row['Recipient']. " in a new tab'>DA". $row['Recipient']. "</a>";
								$csv_output .= "DA". $row['Recipient'] . "; ";
							}
						echo "</td>";

						echo "<td align='right'>";
							if ($row['Donor'] == "0") {
								echo "";
								$csv_output .= "; ";
							} else {
								echo "<a href=index.php?mode=myNum&myNum=". $row['Donor']. " title='View DA". $row['Donor']. " in a new tab'>DA". $row['Donor']. "</a>";
								$csv_output .= "DA". $row['Donor'] . "; ";
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
						echo "<td><div align=center><input type=checkbox name='selected[]' value=". $row['Strain']. "></div></td>";
					echo "</tr>";
					//Stop making the table
				}

			echo "</form>";
		echo "</table>";

		// Close the mysql connection
		mysql_close();
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