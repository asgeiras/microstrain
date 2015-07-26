<?php

$helpmessage = "You need to log in to access this page.";

?>

<form name="login" id='login' method="POST" action="index.php">
	<p>
		Username: <input type='text' name='username'>
		Password: <input type='password' name='password'>
		          <input type='hidden' name='form-type' id='form-type' value="login">
	</p>

	<p><input type="Submit" Name="Submit1" value="Login"></p>
</form>