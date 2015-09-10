<?php

// TODO: Use GET automatically

// Start the session
session_start();

// Logout
if($_GET['logout'] == '1') {
	$user = '';
	session_destroy();
	header('Location: index.php');
}

// Connect to the database or die
include("db.php");

// Include all functions
include("functions.php");

// Include all actions for forms
include("actions.php");

// Troubleshooting
//print("session:<pre class='noPrint'>".print_r($_SESSION, true)."</pre>");

?>

<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="variant.css" media="screen,projection" title="Variant Portal" />
		<link rel="stylesheet" type="text/css" href="print.css" media="print">
		<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
		<script type="text/javascript" src="js/script.js" ></script>

		<title>Dr Strainlove</title>
	</head>
	<body>
		<div id="container" >
			<div id="toplinks" class='noPrint'>
				<p>
					Welcome to the machine. Today is <?php echo date('M d, Y'); ?>.<br>

					<?php
					if (!(isset($_SESSION['login']) && $_SESSION['login'] != '')){
						echo "You are not logged in.";
					} else {
						echo "You are currently logged in as <strong>" . $_SESSION['user'] . "</strong>.";
						echo "<br>";
					    echo "If you are not " . $_SESSION['user'] . ", please <a href='index.php?logout=1'> Log out</a>.";
					}
					?>
				</p>
			</div>

			<div id="logo" class='noPrint'>
				<h1><a href="index.php">Dr Strainlove</a></h1>
				<p>Powered by <i>Salmonella</i> genetics!</p>
			</div>

			<div class='noPrint'>
				<h2 class="hide">Site menu:</h2>
				<ul id="navitab">
					<li><a class="<?php echo ($_GET['mode'] == 'search' && $_GET['type'] == 'word' ? 'current' : NULL); ?>" href="index.php?mode=search&amp;type=word">Search strains</a></li>
					<li><a class="<?php echo ($_GET['mode'] == 'search' && $_GET['type'] == 'number' ? 'current' : NULL); ?>" href="index.php?mode=search&amp;type=number">List strains</a></li>

					<?php if($_SESSION['Usertype'] == 'Superuser'){ ?>
						<li><a class="<?php echo ($_GET['mode'] == 'add' ? 'current' : NULL); ?>" href="index.php?mode=add&amp;Line=<?php echo $_SESSION['Line'];?>">Add strain(s)</a></li>
						<li><a class="<?php echo ($_GET['mode'] == 'addUser' ? 'current' : NULL); ?>" href="index.php?mode=addUser">Add User(s)</a></li>
					<?php } ?>
				</ul>
			</div>
			<div id="desc" class='noPrint'>
				<div class="splitleft">
					<h2>Welcome to the DA strain database!</h2>
					<p>This is where the good strains are. And some <i>E. coli</i> too...</br></p>
				</div>
				<hr>
			</div>

			<div id="main">
				<p id="js-disabled" class="validation-fail">No JS detected! Some functionality disabled!</p>

				<?php
				$value = $_GET['mode'];

				if ($_SESSION['login'] != '1') {
					include("login.php");
				} else {
					if (($value == 'search') && $_GET['error'] == 'error') {
						echo "<span style='color: red'><strong>You need to provide at least one keyword!</strong></span><br>";
						include("search.php");
					}
					elseif (($value == 'search') && ($_POST['isPosted'] == 'TRUE' || $_GET['search'] == "1")) {
						include("search.php");
						include("search1.php");
					}
					elseif (($value == 'search') && ($_POST['isPosted'] != 'TRUE')) {
						include("search.php");
					}
					elseif ($value == 'myNum') {
						include("search1.php");
					}
					elseif (($value == 'myList') && ($_POST['show'])) {
						include("search1.php");
					}
					elseif (($value == 'myList') && ($_POST['edit'])) {
						include("edit.php");
					}
					elseif (($value == 'myList') && ($_POST['print'])) {
						include("print.php");
					}
					elseif ($value == 'add') {
						include("insert.php");
					}
					elseif ($value == 'add3') {
						include("search1.php");
					}
					elseif ($value == 'edit2') {
						include("search1.php");
					}
					elseif (($value == 'addUser') && ($_GET['save'] != 'success')) {
						include("signup.php");
					}
					else {
						include("search.php");
					}
				}
				?>

				<div class='noPrint'>
					<br><br>
					<p class="block"><strong>Please note:</strong> <?php echo $helpmessage . " " . $helpmessage2; ?></p>
				</div>
			</div> <!-- id="main" -->

			<div class='noPrint'>
				<div id="footer">
					<p>2011 &middot; Joakim Näsvall and Erik Gullberg &middot; This page was last updated 2013-01-22 by Joakim Näsvall (finally fixed some anoying bugs)</p>
				</div>
			</div>
		</div> <!-- id="container" -->
	</body>
</html>
