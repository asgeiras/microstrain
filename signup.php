<?php
//session_start();
//if (!(isset($_SESSION['login']) && $_SESSION['login'] != '')) {
	//header ("Location: login.php");
//}

//set the session variable to 1, if the user signs up. That way, they can use the site straight away
//do you want to send the user a confirmation email?
//does the user need to validate an email address, before they can use the site?
//do you want to display a message for the user that a particular username is already taken?
//test to see if the u and p are long enough
//you might also want to test if the users is already logged in. That way, they can't sign up repeatedly without closing down the browser
//other login methods - set a cookie, and read that back for every page
//collect other information: date and time of login, ip address, etc
//don't store passwords without encrypting them

$uname = "";
$pword = "";
$type = "";
$errorMessage = "";
$num_rows = 0;
$helpmessage = "Username must be between 5 and 20 characters, password between 8 and 16 characters and signature between 2 and 20 characters.";
function quote_smart($value, $handle) {

   if (get_magic_quotes_gpc()) {
       $value = stripslashes($value);
   }

   if (!is_numeric($value)) {
       $value = "'" . mysql_real_escape_string($value, $handle) . "'";
   }
   return $value;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

	//====================================================================
	//	GET THE CHOSEN U AND P, AND CHECK IT FOR DANGEROUS CHARCTERS
	//====================================================================
	$uname = $_POST['username'];
        $type = $_POST['type'];
	$pword = $_POST['password'];
	$sign = $_POST['signature'];

	$uname = htmlspecialchars($uname);
        $type = htmlspecialchars($type);
	$pword = htmlspecialchars($pword);
	$sign = htmlspecialchars($sign);
	//====================================================================
	//	CHECK TO SEE IF U AND P ARE OF THE CORRECT LENGTH
	//	A MALICIOUS USER MIGHT TRY TO PASS A STRING THAT IS TOO LONG
	//	if no errors occur, then $errorMessage will be blank
	//====================================================================

	$uLength = strlen($uname);
	$pLength = strlen($pword);
	$sLength = strlen($sign);

	if ($uLength >= 5 && $uLength <= 20) {
		$errorMessage = "";
	}
	else {
		$errorMessage = $errorMessage . "Username must be between 5 and 20 characters" . "<BR>";
	}

if ($sLength >= 2 && $sLength <= 20) {
		$errorMessage = "";
	}
	else {
		$errorMessage = $errorMessage . "Signature must be between 2 and 20 characters" . "<BR>";
	}

	if ($pLength >= 8 && $pLength <= 16) {
		$errorMessage = "";
	}
	else {
		$errorMessage = $errorMessage . "Password must be between 8 and 16 characters" . "<BR>";
	}

	// Check that type is one of 'User' or 'Superuser'

	if ($type == 'Superuser' or $type == 'User') {
		$errorMessage = "";
	}
	else {
		$errorMessage = $errorMessage . "User Type can only be User or Superuser" . "<BR>";
	}




//test to see if $errorMessage is blank
//if it is, then we can go ahead with the rest of the code
//if it's not, we can display the error

	//====================================================================
	//	Write to the database
	//====================================================================
	if ($errorMessage == "") {
        include('../local/datalogin.php');
        
	$db_handle = mysql_connect($dbhost, $dbusername, $dbuserpass);
	$db_found = mysql_select_db($dbname, $db_handle);

	if ($db_found) {

		$uname = quote_smart($uname, $db_handle);
		$pword = quote_smart($pword, $db_handle);
		$type = quote_smart($type, $db_handle);
		$sign = quote_smart($sign, $db_handle);
	//====================================================================
	//	CHECK THAT THE USERNAME IS NOT TAKEN
	//====================================================================

		$SQL = "SELECT * FROM users WHERE Username = $uname OR Signature = $sign";
		$result = mysql_query($SQL);
		$num_rows = mysql_num_rows($result);

		if ($num_rows > 0) {
			$errorMessage = "Username or Signature already taken";
		}
		
		else {

			$SQL = "INSERT INTO users (Username, Usertype, Password, Signature) VALUES ($uname, $type, md5($pword), $sign)";

			$result = mysql_query($SQL) or die ("ERROR". mysql_error()."<br>". "$SQL");

			mysql_close($db_handle);

		//=================================================================================
		//	START THE SESSION AND PUT SOMETHING INTO THE SESSION VARIABLE CALLED login
		//	SEND USER TO A DIFFERENT PAGE AFTER SIGN UP
		//=================================================================================

			//session_start();
			//$_SESSION['login'] = "1";
			//$_SESSION['user'] = $uname;
			header ("Location: index.php?mode=addUser&save=success");

		}

	}
	else {
		$errorMessage = "Database Not Found";
	}




	}

}


?>

	<html>
	<head>
	<title>Basic Login Script</title>


	</head>
	<body>
<?php

if ($_GET['save'] != "success")
{
?>
<FORM NAME ="form1" METHOD ="POST" ACTION ="index.php?mode=addUser">
<table><tr><td>
Username:</td><td colspan=2> <INPUT TYPE = 'TEXT' Name ='username'  value="<?php print $_POST['username'];?>" maxlength="20"></td><td> (5-20 characters)</td></tr>

<tr><td>Password:</td><td colspan=2><INPUT TYPE = 'PASSWORD' Name ='password'  value="<?php print $_POST['password'];?>" maxlength="16"></td><td> (8-16 characters)</td></tr>
<td>Signature:</td><td colspan=2> <INPUT TYPE = 'TEXT' Name ='signature'  value="<?php print $_POST['signature'];?>" maxlength="20"></td><td> (2-20 characters)</td></tr>
<tr><td>User Type:</td><td align=left><select name="type"><option value="Superuser">Superuser</option><option value="User">User</option></select></td><td><div align=right><INPUT TYPE = "Submit" Name = "Submit1"  VALUE = "Register"></div></td><td></td></tr>

</table>


</FORM>
<P>

<?php
}
echo $errorMessage;
?>

	</body>
	</html>
