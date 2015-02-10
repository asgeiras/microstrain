<html>
   <head><SCRIPT TYPE="text/javascript">
////////////////////////////////////////////////////////////////
function js_detect() {

document.login.js_var.value = 'TRUE';

}
///////////////////////////////////////////////////////////////
</SCRIPT>



<?php
session_start();
$_SESSION['noJs'] = '';
$helpmessage = "You need to log in to access this page.";
$uname = "";
$pword = "";
$errorMessage = "";
//==========================================
//	ESCAPE DANGEROUS SQL CHARACTERS
//==========================================
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
	$uname = $_POST['username'];
	$pword = $_POST['password'];

	$uname = htmlspecialchars($uname);
	$pword = htmlspecialchars($pword);

	//==========================================
	//	CONNECT TO THE LOCAL DATABASE
	//==========================================
	include('/var/local/datalogin.php');

	$db_handle = mysql_connect($dbhost, $dbusername, $dbuserpass);
	$db_found = mysql_select_db($dbname, $db_handle);

	if ($db_found) {

		$uname = quote_smart($uname, $db_handle);
		$pword = quote_smart($pword, $db_handle);

		$SQL = "SELECT * FROM users WHERE Username = $uname AND Password = md5($pword)";
		$result = mysql_query($SQL);
		$num_rows = mysql_num_rows($result);

	//====================================================
	//	CHECK TO SEE IF THE $result VARIABLE IS TRUE
	//====================================================

		if ($result) {
			if ($num_rows > 0) {
				session_start();
				$_SESSION['login'] = "1";
					while($row = mysql_fetch_array($result))
					{
					$_SESSION['user'] = $row['Username'];
					$_SESSION['Usertype'] = $row['Usertype'];
					$_SESSION['Signature'] = $row['Signature'];
					}
                                $_SESSION['user'] = str_replace("'", "", $uname);
				//header ("Location: page1.php");
				header ("Location: index.php");
			}
			else {
				//session_start();
				//$_SESSION['login'] = "";
                                $_SESSION['user'] = "";
			$errorMessage = "Error logging on";
				//header ("Location: signup.php");
			}	
		}
		else {
			$errorMessage = "Error logging on";
			echo '<SCRIPT LANGUAGE="javascript">alert("Wrong username or password!");</script>';
		}

	mysql_close($db_handle);

	}

	else {
		$errorMessage = "Error logging on";
	}

}


?>


<html>
<head>
<title>Basic Login Script</title>
</head>
<body onLoad='javascript:js_detect();'>

<FORM NAME ="login" Id='login' METHOD ="POST" ACTION ="index.php">

Username: <INPUT TYPE = 'TEXT' Name ='username'  value="<?php print htmlspecialchars($_POST['username']);?>" maxlength="20">
Password: <INPUT TYPE = 'PASSWORD' Name ='password'  value="" maxlength="16">
          <INPUT TYPE = 'HIDDEN' Name ='js_var' id='js_var' >

<P align = center>
<INPUT TYPE = "Submit" Name = "Submit1"  VALUE = "Login">
</P>

</FORM>

<P>
<?php
if($_POST['js_var'] != 'TRUE') {
session_start();
$_SESSION['noJs'] = "<br><br><span style='color: red; font-weight: bold'>WARNING: Your browser does not seem to have javascript enabled.<br>If you don't enable it, most things won't work at all.</span><br><br>";
echo $errorMessage, $errorMessage2;
}
else { echo "<script language=javascript>alert('Please enter a valid username and password.')</script>"; }
;?>




</body>
</html>
