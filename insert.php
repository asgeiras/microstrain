<?php 

// Only allow insert if the user is a Superuser
if($_SESSION['Usertype'] == 'Superuser') {

	$helpmessage = "Genotype field can not be empty."; 

	echo '<form action="index.php?mode=add3" name="frmAdd" method="post">'; 
		echo "<p>";
			echo "Number of strains to add: ";
			echo '<select class="droplist" name="menu1">';

				for($i = 1; $i <= 50; $i++) {
					if($_GET["Line"] == $i){  
						$sel = "selected";
					} else {
						$sel = "";
					}

					echo '<option value="' . $i . '" ' . $sel . '>' . $i . '</option>';
				}
			 
			echo "</select>";
			echo '<input type="submit" name="update_lines" value="Update">';
		echo "</p>";
		echo "<input type='hidden' name='form-type' id='form-type' value='add'>";

		// Did we fail validation
		if(isset($_SESSION['saveFail'])) {
			echo '<p class="validation-fail">There are fields with errors.</p>';
			$failedRows = $_SESSION['saveFail'];
		}

		// Print table header
		echo "<table class='insert'>";
			echo "<tr>";
				echo "<th>Genotype</th>";
				echo "<th>Parents</th>";
				echo "<th>Comment</th>";
			echo "</tr>";

			// Fetch number of lines and set it to 1 if not suitable
			$line = $_GET["Line"];
			if($line <= 0){
				$line = 1;
			} 
			$_SESSION["Line"] = $line;

			// Loop through all rows
			for($i = 1; $i <= $line; $i++){
				unset($failClass);

				// Specify classes for failed fields
				if(isset($failedRows) && array_key_exists($i, $failedRows)){
					if(!$failedRows[$i]['Genotype']){
						$failClass['genotype'] = "validation-fail";
					}

					if(!$failedRows[$i]['Donor']){
						$failClass['donor'] = "validation-fail";
					}

					if(!$failedRows[$i]['Recipient']){
						$failClass['recipient'] = "validation-fail";
					}
				}

				// Print input fields
				echo "<tr id='line-" . $i . "'>";
					echo "<td>";
						echo '<textarea class="' . $failClass['genotype'] . '" rows="4" cols="45" type="text" style="font-size: 14px;" wrap=soft name="txtGenotype[' . $i . ']">';
							echo $_SESSION["txtGenotype"][$i];
						echo "</textarea>";
					echo "</td>";
				  
					echo "<td>";
						echo "<table>";
							echo "<tr>";
								echo "<td>";
									echo '<div>Donor:</div>';
									echo '<input  class="' . $failClass['donor'] . '" type="text" name="txtDonor[' . $i . ']" size="5" value="' . $_SESSION["txtDonor"][$i] . '" />';
								echo "</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>";
									echo "<div>Recipient:</div>";
									echo '<input  class="' . $failClass['recipient'] . '" type="text" name="txtRecipient[' . $i . ']" size="5" value="' . $_SESSION["txtRecipient"][$i] . '" />';
								echo "</td>";
							echo "</tr>";
						echo "</table>";
					echo '</td>';
					  
					echo "<td>";
						echo '<textarea rows="4" cols="45" type="text" style="font-size: 14px;" wrap=soft name="txtComment[' . $i . ']">';
							echo $_SESSION["txtComment"][$i];
						echo "</textarea>";
					echo "</td>";
				echo "</tr>";
			}
		echo "</table>";

		echo "<p>These will be added under the signature <strong>" . $_SESSION['Signature'] . "</strong>.</p>";
		echo '<input type="hidden" name="txtSignature" value="' . $_SESSION['Signature'] . '">';
		echo "<br />";
		
		echo '<input type="submit" class="savebutton" name="submit" value="Save">';
		echo '<input type="submit" title="Reset all fields and clear memory" name="reset" value="Clear fields">';
	echo "</form>";
}
else {
	echo "<span style='color: red; font-weight: bold;'>You are not trusted to add new strains!</span>";
}
?>
