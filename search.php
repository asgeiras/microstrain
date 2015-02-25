


<form name="search" id="search" action="index.php?mode=search&amp;type=<?php echo $_GET['type'];?>" action="search.php" method="post">

<?php

//Are we in the 'Search' tab?
if($_GET['type'] == 'word' || !isset($_GET['type'])) {
	
	// Set Genotype as default or remember the status of the checkboxes in the previous search: 
	$helpmessage = "Select which field(s) to search in and type a keyword in at least one of the input boxes.<br>When you click 'Search', your keywords are remembered until you click 'Reset'.";

	if(($_SESSION['genotype'] == 'genotype') && ($_SESSION['comment'] != 'comment')) {
		$field1 = "Genotype";
		$field2 = "Genotype";
		$check1 = 'checked="checked"';
		$check2 = '';
	}
	elseif(($_SESSION['genotype'] == 'genotype') && ($_SESSION['comment'] == 'comment')) {
		$field1 = "Genotype";
		$field2 = "Comment";
		$check1 = 'checked="checked"';
		$check2 = 'checked="checked"';
	}
	elseif(($_SESSION['genotype'] != 'genotype') && ($_SESSION['comment'] == 'comment')) {
		$field1 = "Comment";
		$field2 = "Comment";
		$check1 = '';
		$check2 = 'checked="checked"';
	}
	else {
		$check1 = 'checked="checked"';
		$check2 = '';
	}

	?>



	<p>Search in:</p>
	<label for="checkboxGenotype"><input type="checkbox" name="check[genotype]" id="checkboxGenotype" title="Select which fields to search in" value="genotype" <?php echo ($check1); ?> />Genotype</label><br>
	<label for="checkboxComment"><input type="checkbox" name="check[comment]" id="checkboxComment" title="Select which fields to search in" value="comment" <?php echo ($check2); ?>/>Comment</label><br>
	<br>


	<table width="20" border="0">
		<tr>
			<td colspan=2>Include:<br><input type="text" title="Tip: use % as wildcard" name="term1" value="<?php echo $_SESSION['term1']; ?>" onKeyPress="if (event.keyCode == 13) pageforward(1);"/></td>
			<td><br><input type="text" title="Tip: use % as wildcard" name="term2" value="<?php echo $_SESSION['term2']; ?>" onKeyPress="if (event.keyCode == 13) pageforward(1);"/></td>
			<td><br><input type="text" title="Tip: use % as wildcard" name="term3" value="<?php echo $_SESSION['term3']; ?>" onKeyPress="if (event.keyCode == 13) pageforward(1);"/></td>
			<td><br><input type="text" title="Tip: use % as wildcard" name="term4" value="<?php echo $_SESSION['term4']; ?>" onKeyPress="if (event.keyCode == 13) pageforward(1);"/></td>
		</tr>

		<tr>
			<td colspan=2>Exclude:<br><input type="text" title="Tip: use % as wildcard" name="notterm1" value="<?php echo $_SESSION['notterm1']; ?>" onKeyPress="if (event.keyCode == 13) pageforward(1);"/></td>
			<td><br><input type="text" title="Tip: use % as wildcard" name="notterm2" value="<?php echo $_SESSION['notterm2']; ?>" onKeyPress="if (event.keyCode == 13) pageforward(1);"/></td>
			<td><br><input type="text" title="Tip: use % as wildcard" name="notterm3" value="<?php echo $_SESSION['notterm3']; ?>" onKeyPress="if (event.keyCode == 13) pageforward(1);"/></td>
			<td><br><input type="text" title="Tip: use % as wildcard" name="notterm4" value="<?php echo $_SESSION['notterm4']; ?>" onKeyPress="if (event.keyCode == 13) pageforward(1);"/></td>
		</tr>


	<?php
}
//Are we in the 'List' tab?
if($_GET['type'] == 'number' or $_GET['type'] == 'advanced') {
	$helpmessage = "To list a range of strain numbers, use both of the input boxes. To find a single strain, use only one of the input boxes.<br>When you click 'Search', the values are remembered until you click 'Reset'."; ?>
	List strain numbers:

    <table width="20" border="0">
    	<tr><td colspan=4>Between:<br><input type="text" name="minNum" maxlength="5" value="<?php echo $_SESSION['minNum']; ?>" onKeyPress="if (event.keyCode == 13) pageforward(1); return number(event);"/></td><td></td></tr>
    	<tr><td colspan=4>And:<br><input type="text" name="maxNum" maxlength="5" value="<?php echo $_SESSION['maxNum']; ?>" onKeyPress="if (event.keyCode == 13) pageforward(1); return number(event);"/></td></tr>
     

<?php
//**
}
?>

<tr><td colspan=2>Limit to strains frozen by:<br><input type="text" name="sign1" value="<?php echo $_SESSION['sign1']; ?>" onKeyPress="if (event.keyCode == 13) pageforward(1);"/></td>
	<td>Show:<br>
		<input type="text" name="limit" size=4 maxlength="5" value="<?php if(isset($_POST['limit'])) {echo $_POST['limit'];}else {echo 100;} ?>" onKeyPress="if (event.keyCode == 13) pageforward(1); return number(event);"/><br></td><td><input type="hidden" name="startval" value="<?php if(isset($_POST['startval'])) {echo $_POST['startval'];}else {echo 1;} ?>" /><input type="hidden" name="page"/><br>

	</td>
</tr>

<tr>
	<td width=20>
	    <input type="hidden" name="isPosted" value="TRUE"/>
	    <input type="button" name="button" value="Search" onClick="pageforward(1);"/></td><td>
		<input type="button" title="Reset all fields and clear the results table" name="reset" value="Reset" onclick="window.location.href='index.php?mode=search&type=<?php echo $_GET['type'];?>&reset=TRUE'">
	</td>
</tr>
</form>
</table>
