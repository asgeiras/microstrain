<?php

$helpmessage = "You need to log in to access this page.";

?>

<form name="login" id='login' method="POST" action="index.php">
	<p>
		Username: <input type='text' name='username' value="" maxlength="20">
		Password: <input type='password' name='password' value="" maxlength="16">
		          <input type='hidden' name='js_var' id='js_var' >
		          <input type='hidden' name='form-type' id='form-type' value="login">
	</p>

	<p><input type="Submit" Name="Submit1" value="Login"></p>
</form>


<?php
/*
if($_POST['js_var'] != 'TRUE') {
	$_SESSION['noJs'] = "<br><p><span style='color: red; font-weight: bold'>WARNING: Your browser does not seem to have javascript enabled.<br>If you don't enable it, most things won't work at all.</span></p><br>";
	echo $errorMessage, $errorMessage2;
} else {
	echo "<script language=javascript>alert('Please enter a valid username and password.')</script>"; 
}
*/
?>