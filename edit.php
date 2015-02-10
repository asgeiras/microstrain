   
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


<?php
include ("/var/local/datalogin.php");
if($_SESSION['Usertype'] == 'Superuser')
{
$con = mysql_connect("$dbhost","$dbusername","$dbuserpass");
if (!$con)
 {
  die('Could not connect: ' . mysql_error());
 }
$helpmessage = "Before you press the save button, please verify that you changed the strain information correctly. To update the timestamp in the \"Created\" column, click the checkbox."; 
mysql_select_db("$dbname", $con) or die("Unable to select database");


//Begin List selected strains
if ($_GET['mode'] == 'myList')
{
$selected = $_POST['selected'];
$list = implode(", ",$selected);
$message = "You selected the following ";
$sql = "select * from strains where Strain IN ($list)";
}
//End List selected strains





// Do the actual mysql query
$result = mysql_query($sql);
// Count the results and print a message:
$count = mysql_num_rows($result);
// Make a nice table:
echo $message. "<strong>". $count. "</strong> strains for <span style='color: red'><strong>editing</strong></span>:";
    echo "<table class='sample' border='1'><br>"; ?>

<?php
// Print out a table of the resulting strains:

    echo "<tr> <th>Strain</th> <th width='300px'>Genotype</th> <th><span style='color: white'>__</span>Parents<span style='color: white'>__</span></th> <th width='300px'>Comment</th> <th>Signature</th> <th>Created</th></tr><form name='strainstoupdate' method='post' action='index.php?mode=edit' ><input type='submit' title='Save the changes' name='save' value='save'/>";
      while ($row = mysql_fetch_array($result)){
      $genotype = htmlspecialchars($row['Genotype']);
      $comment = htmlspecialchars($row['Comment']);


       echo "<tr><td>";    
          echo 'DA' .$row['Strain'];
          echo "<input type='hidden' name='Strain[$i]' value='{$row['Strain']}' />";
	
       echo "</td><td>"; 
          echo $genotype. "<br><br>";
          echo "<textarea type='text' rows=3 cols=40 name='Genotype[$i]'>{$row['Genotype']} </textarea>\n";

       echo "</td><td>";
          echo "Donor:<br>";
          if ($row['Donor'] == "0")
          {
          echo "<span style='color: grey'>(Not entered)</span><br>";
          echo "DA<input type='text' size=4 name='Donor[$i]' value='' onKeyPress='return number(event)' />\n<br><br>";
          }
          else {
          echo "<a href=index.php?mode=myNum&myNum=". $row['Donor']. " title='View DA". $row['Donor']. " in a new tab'>DA". $row['Donor']. "</a><br>";
          echo "DA<input type='text' size=4 name='Donor[$i]' value='{$row['Donor']}' onKeyPress='return number(event)' /><br><br>\n";
          }

          echo "Recipient:<br>";
          if ($row['Recipient'] == "0")
          {
          echo "";
          echo "DA<input type='text' size=4 name='Recipient[$i]' value='' onKeyPress='return number(event)' />\n";
          }
          else
          {
          echo "<a href=index.php?mode=myNum&myNum=". $row['Recipient']. " title='View DA". $row['Recipient']. " in a new tab'>DA". $row['Recipient']. "</a><br>";
          echo "DA<input type='text' size=4 name='Recipient[$i]' value='{$row['Recipient']}' onKeyPress='return number(event)' />\n";
          }

       echo "</td><td>"; 
          echo $comment. "<br><br>";
          echo "<textarea type='text' rows=3 cols=40 name='Comment[$i]'>{$row['Comment']}</textarea>\n";

       echo "</td><td>";
          if($row['Signature'] == '')
          {
          echo "<span style='color: grey'>(Not entered)</span><br>";
          echo "<input type='text' size='3' name='Signature[$i]' value='{$user}' />";
          }
          else
          {
          echo $row['Signature'];
          echo "<input type='text' size='3' name='Signature[$i]' value='{$row['Signature']}' />";
          }
       echo "</td><td>"; 
          if ($row['Created'] == "0000-00-00 00:00:00")
          {
          echo "<span style='color: grey'>(Not entered)</span><br>";
          echo "Update<input type='checkbox' size='3' name='Created[$i]' value='{$today}' />";
          }
          else {echo $row['Created']. "<br>";
          echo "Update<input type='checkbox' size='3' name='Created[$i]' value='{$today}' />";
          }

       echo "</td></tr>";
//Stop making the table
}
        
echo "</table></form>";
// Close the mysql connection
mysql_close();
}
else
{echo "<span style='color: red; font-weight: bold;'>You are not trusted to edit strains!</span>";}
?>
