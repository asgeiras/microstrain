<?php
error_reporting(~0);
ini_set('display_errors', 1);

// Create MySQL connection
include ("/var/local/datalogin.php");
$db_handle = new mysqli($dbhost, $dbusername, $dbuserpass);

// Check connection
if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$db_found = mysqli_select_db($dbhandle, $dbname);


   if ($db_found)
   {
	$SQL = "SELECT * FROM users WHERE Username = '". mysql_real_escape_string($_SESSION['user']['username']) . "'";
	$result = mysqli_query($SQL) or die(mysqli_error());
	$num_rows = mysqli_num_rows($result) or die(mysqli_error($result));
		if ($result)
		{
		while($row = mysqli_fetch_array($result))
			{
			session_start();
			$_SESSION['Usertype'] = $row['Usertype'];
			$_SESSION['signature'] = $row['Signature'];
			}
		}
    }

if($_SESSION['Usertype'] == 'Superuser')
{
?>
<html>
<head>
<SCRIPT TYPE="text/javascript">
function number(e)
{
var key;
var keychar;

if (window.event)
   key = window.event.keyCode;
else if (e)
   key = e.which;
else
   return true;
keychar = String.fromCharCode(key);
keychar = keychar.toLowerCase();


if ((key==null) || (key==0) || (key==8) || 
    (key==9) || (key==13) || (key==27) )
   return true;

else if ((("0123456789").indexOf(keychar) > -1))
   return true;
else
   return false;
}

</SCRIPT>

<script language="JavaScript" type="text/JavaScript">  
<!--  
function MM_jumpMenu(targ,selObj,restore){ //v3.0  
eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");  
if (restore) selObj.selectedIndex=0;  
}  
//-->  
</script>  

</head>  
<body>  
<form action="index.php?mode=add2" name="frmAdd" method="post">  
Number of strains to add:  
<select class="droplist" name="menu1" onChange="MM_jumpMenu('parent',this,0) ">

<?php



$helpmessage = "Only lines with text in the genotype field will be saved to the database. Enter the strain information carefully!"; 
for($i=1;$i<=50;$i++)  
{  
if($_GET["Line"] == $i)  
{  
$sel = "selected";  
}  
else  
{  
$sel = "";  
}  
?>  
<option value="<?php=$_SERVER['PHP_SELF'];?>?mode=add&Line=<?php=$i;?>" <?php=$sel;?> > <?php=$i;?></option>  
<?php
}  
?> 
<?php
// Did we fail validation (missing genotype?)
if($_SESSION['saveFail'] != '') {
$n = $_SESSION['saveFail'];
?>
<script language=javascript>alert("Missing, a genotype is." + "\n" + "That it is correct, verify you must.");</script>
<?php
echo "<script language=javascript>document.frmAdd.txtGenotype",$n, ".focus();</script>";

$_SESSION['saveFail'] = '';
}

?>

 
</select>  
<table BORDER=1 RULES=NONE FRAME=border>  
<tr>  
<th> <div align="center">Genotype </div></th>  
<th> <div align="center">Parents </div></th>  
<th> <div align="center">Comment </div></th>  
<th> <div align="center">Signature </div></th>  
</tr>  
<?php
$line = $_GET["Line"];  
if($line == 0){$line=1;}  
for($i=1;$i<=$line;$i++)  
{  
?>  

<tr><td><div align="center"><textarea rows="4" cols="45" type="text" style="font-size: 14px;" wrap=soft name="txtGenotype<?=$i;?>"><?php= $_SESSION["txtGenotype$i"];?></textarea></div></td>
  
<td><table><tr><td><div align="left">Donor:</div><input type="text" name="txtDonor<?php=$i;?>" size="3" maxlength="5" onKeyPress="return number(event)" value='<?php= $_SESSION["txtDonor$i"];?>' /></td></tr>
<tr><td><div align="left">Recipient:</div><input type="text" name="txtRecipient<?php=$i;?>" size="3" maxlength="5" onKeyPress="return number(event)" value='<?php= $_SESSION["txtRecipient$i"];?>' /></td></tr></table>
  
<td><div align="center"><textarea rows="4" cols="45" type="text" style="font-size: 14px;" wrap=soft name="txtComment<?php=$i;?>"><?php=$_SESSION["txtComment$i"];?></textarea></div></td>  
<td align="right"><input type="text" name="txtSignature<?php=$i;?>" value="<?php echo $_SESSION['signature'];?>" size="8" disabled></td>  
</tr>  
<?php
}
?> 
</table> 
<br /> 
<input type="submit" class="savebutton" name="submit" value="Save">
<INPUT TYPE="BUTTON" title="Reset all fields and clear memory" name="reset" VALUE="Reset" ONCLICK="window.location.href='index.php?mode=add&reset=TRUE'">
<input type="hidden" name="hdnLine" value="<?php=$i;?>">  
</form>
</body>  
</html>
<?php
}
else
{echo "<span style='color: red; font-weight: bold;'>You are not trusted to add new strains!</span>";}
?>
