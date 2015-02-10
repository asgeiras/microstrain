<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="description" content="your description goes here" />
<meta name="keywords" content="your,keywords,goes,here" />
<meta name="author" content="Your Name / Original design: Andreas Viklund - http://andreasviklund.com/" />
<link rel="stylesheet" type="text/css" href="variant.css" media="screen,projection" title="Variant Portal" />
<link rel="stylesheet" type="text/css" href="print.css" media="print">

<title>Dr Strainlove</title>
<script>
function hideDiv(){
document.getElementById('hideDiv').style.display = "none";
}
hideDiv()
</script>
</head>
<?php
session_start();
if (!(isset($_SESSION['login']) && $_SESSION['login'] != '')) {
        header("index.php?mode=login");
}
?>
<body>

<div id="container" >
<div id="toplinks" class='noPrint'><p>Welcome to the machine. Today is <?php echo date('M d, Y'); ?>.<br>
<?php	if (!(isset($_SESSION['login']) && $_SESSION['login'] != ''))
	{
	echo "You are not logged in.";
	}
	else
	{
	echo "You are currently logged in as <span style='color: blue; font-weight: bold;'> ";
	echo $_SESSION['user'], "</span>.<br>";
        echo "If you are not ", $_SESSION['user'], ", please <A HREF = index.php?logout=1> Log out</A>.</p>";
	}
?>



</div>
<div id="logo" class='noPrint'>
<h1><a href="index.php">Dr Strainlove</a></h1>

<p>Powered by <i>Salmonella</i> genetics!</p>

</div>
<div class='noPrint'>
<h2 class="hide">Site menu:</h2>
<ul id="navitab">
<li><a class="<?php echo ($_GET['mode'] == 'search' && $_GET['type'] == 'word' ? 'current' : NULL); ?>" href="index.php?mode=search&type=word">Search strains</a></li>
<li><a class="<?php echo ($_GET['mode'] == 'search' && $_GET['type'] == 'number' ? 'current' : NULL); ?>" href="index.php?mode=search&type=number">List strains</a></li>
<?php
if($_SESSION['Usertype'] == 'Superuser')
{ ?>
<li><a class="<?php echo ($_GET['mode'] == 'add' ? 'current' : NULL); ?>" href="index.php?mode=add&Line=<?php echo $_SESSION['Line'];?>">Add strain(s)</a></li>
<li><a class="<?php echo ($_GET['mode'] == 'addUser' ? 'current' : NULL); ?>" href="index.php?mode=addUser">Add User(s)</a></li>
<?php } ?>
</ul>
</div>
<div id="desc" class='noPrint'>

<div class="splitleft">
<h2>Welcome to the DA strain database!</h2>
<p>This is where the good strains are. And some <i>E. coli</i> too...</br></p>

</div>


<hr />
</div>

<div id="main">


<?php	  //********************************************//
	 //      Jocke's magic code starts here:       //
	//********************************************//
echo $_SESSION['noJs'];

if($_POST['isPosted'] == 'TRUE') {
	session_start();
	$_SESSION['sign1'] = $_POST['sign1'];
	if($_GET['type'] == 'word') {
	$_SESSION['term1'] = $_POST['term1'];
	$_SESSION['term2'] = $_POST['term2'];
	$_SESSION['term3'] = $_POST['term3'];
	$_SESSION['term4'] = $_POST['term4'];
	$_SESSION['notterm1'] = $_POST['notterm1'];
	$_SESSION['notterm2'] = $_POST['notterm2'];
	$_SESSION['notterm3'] = $_POST['notterm3'];
	$_SESSION['notterm4'] = $_POST['notterm4'];
	$_SESSION['genotype'] = $_POST['check']['genotype'];
	$_SESSION['comment'] = $_POST['check']['comment'];
	}
	if($_GET['type'] == 'number') { 
	$_SESSION['minNum'] = $_POST['minNum'];
	$_SESSION['maxNum'] = $_POST['maxNum'];
	}
}

if($_GET['logout'] == '1')
{
$user = '';
session_start();
session_destroy();
}

// The reset button clears all submitted variables from all forms.
if($_GET['reset'] == 'TRUE')
{
//Search:
$_SESSION['total_records'] = '';
$_SESSION['word'] = '';
$_SESSION['num'] = '';
$_SESSION['check'] = '';
$_SESSION['genotype'] = '';
$_SESSION['comment'] = '';
$_SESSION['term1'] = '';
$_SESSION['term1'] = '';
$_SESSION['term2'] = '';
$_SESSION['term3'] = '';
$_SESSION['term4'] = '';
$_SESSION['notterm1'] = '';
$_SESSION['notterm2'] = '';
$_SESSION['notterm3'] = '';
$_SESSION['notterm4'] = '';
$_SESSION['sign1'] = '';
$_SESSION['minNum'] = '';
$_SESSION['maxNum'] = '';
    // Add strains:
    $_SESSION["Line"] = '';
    $_SESSION['saveFail'] = '';
    for($i=1;$i<=50;$i++)  
    {
    $_SESSION["txtGenotype$i"] = '';
    $_SESSION["txtDonor$i"] = '';
    $_SESSION["txtRecipient$i"] = '';
    $_SESSION["txtComment$i"] = '';
    }
}


if ($_GET['error'] == 'error')
{
echo "<span style='color: red'><strong>You need to provide at least one keyword!</strong></span><br>";
}
else
{

$value = $_GET['mode']; 
if ($_SESSION['login'] != '1')
 {
 include("login.php");
 }
else {
if (($value == 'search') && ($_POST['isPosted'] == 'TRUE'))
 {
 include("search.php");
 include("search1.php");
 }
elseif (($value == 'search') && ($_POST['isPosted'] != 'TRUE'))
 {
 include("search.php");
 }
elseif ($value == 'myNum')
 {
 include("search1.php");
 }
elseif (($value == 'myList') && ($_POST['show']))
 {
 include("search1.php");
 }
elseif (($value == 'myList') && ($_POST['edit']))
 {
 include("edit.php");
 }
elseif (($value == 'myList') && ($_POST['print']))
 {
 include("print.php");
 }
elseif ($value == 'add')
 {
 include("insert.php");
 }
elseif ($value == 'add2')
 {
 include("insert1.php");
 }
elseif ($value == 'add3')
 {
 include("search1.php");
 }
elseif ($value == 'edit')
 {
 include("edit1.php");
 }
elseif ($value == 'edit2')
 {
 include("search1.php");
 }
elseif (($value == 'addUser') && ($_GET['save'] != 'success'))
 {
 include("signup.php");
 }
elseif (($value == 'addUser') && ($_GET['save'] == 'success'))
 {
 echo "<span style='font-weight: bold'>Success!</span><br> The new user was successfully added.";
 $helpmessage = "To log in as the new user,<A HREF = index.php?logout=1> log out ". $_SESSION['user']. "</A>.";
 //include("signup.php");
 }
else
 {
 header("Location: index.php?mode=search&type=word");
 }
}
}


?>
<div class='noPrint'>

<br><br>
<p class="block"><strong>Please note:</strong> <?php echo $helpmessage, " ", $helpmessage2;?></p>
</div>    
</div>

<div class='noPrint'>
<div id="footer">
<p>2011 &middot; Joakim Näsvall and Erik Gullberg and Ásgeir &middot; This page was last updated 2013-01-22 by Joakim Näsvall (finally fixed some anoying bugs)</p>
</div>
</div>
</div>
</body>
</html>
