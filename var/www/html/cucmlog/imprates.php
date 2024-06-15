<?php
//import cvs to mysql
###############  Comdif Innovation CUCM Billing software  ###############
###########################  @Christian Zeler ###########################
ini_set('memory_limit', '-1');
include 'cucminc/varinc.php';
include 'cucminc/connectdb.php';
include 'menu.php';
?>
<form method="post" enctype="multipart/form-data" action="imprates.php"> 
<center><table class="blueTable"> 
	<tr> 
		<td width="500"><font size="3"><b>File.csv  format: <font color="red"> pattern;name;trunk;connectcost;includedsec;purchase;sale</b></font></font><br />
		<font size="2">the field trunk is optional and not actualy used by the system<br />
		You can import without trunk name if you whant format: <font color="red">pattern;name;;connectcost;includedsec;purchase;sale</font><br />
		Prices are in 100em cents does mean 1 cents is 100, never use commas in the csv file</font></td> 
		<td width="244" align="center"><input type="file" name="userfile" value="userfile"></td> 
		<td width="137" align="center"> 
		<input type="submit" value="Send" name="Send"> 
		</td> 
	</tr> 
</table> 
</form>
<center><table class="blueTable">
	<tr>
		<th>Action</th>
		<th>Prefix</th>
		<th>Name</th>
		<th>Trunk</th>
		<th>Connect cost</th>
		<th>Included seconds</th>
		<th>Purchase price</th>
		<th>Sale price</th>
	</tr>
<?php
$fichier=$_FILES["userfile"]["name"];
if ($fichier !='')
	{
	$fp = fopen ($_FILES["userfile"]["tmp_name"], "r");
	$empty = "TRUNCATE TABLE `routes`";
	$done = mysqli_query($conn,$empty);
	}
else
	{
	exit();
	}
$cpt=0;
echo "<p align=\"center\">Successful</p>";
while (!feof($fp))
	{
	$ligne = fgets($fp,120000);
	$liste = explode(";",$ligne);
	$liste[0] = ( isset($liste[0]) ) ? $liste[0] : Null;
	$liste[1] = ( isset($liste[1]) ) ? $liste[1] : Null;
	$liste[2] = ( isset($liste[2]) ) ? $liste[2] : Null;
	$liste[3] = ( isset($liste[3]) ) ? $liste[3] : Null;
	$liste[4] = ( isset($liste[4]) ) ? $liste[4] : Null;
	$liste[5] = ( isset($liste[5]) ) ? $liste[5] : Null;
	$liste[6] = ( isset($liste[6]) ) ? $liste[6] : Null;
	$champs0=$liste[0]; // Prefix
	$champs1=$liste[1]; // Name
	$champs2=$liste[2]; // Trunks
	$champs3=$liste[3]; // Concost
	$champs4=$liste[4]; // Incsec
	$champs5=$liste[5]; // Purchase
	$champs6=$liste[6]; // Sale
	if ($champs1!='')
		{
		$cpt++;
		$sql= "INSERT INTO calldata.routes (pattern,comment,trunks,connectcost,includedseconds,purchase,sale)
		VALUES('$champs0','$champs1','$champs2','$champs3','$champs4','$champs5','$champs6') ";
		//$requete = mysqli_query($conn,$sql);
	if (!mysqli_query($conn,$sql))
		{
  		echo("Error description: " . mysqli_error($conn));
		}
		?>
		<tr>
		<td>Imported elements :</td>
		<td><?php echo $champs0;?></td>
		<td><?php echo $champs1;?></td>
		<td><?php echo $champs2;?></td>
		<td><?php echo $champs3;?></td>
		<td><?php echo $champs4;?></td>
		<td><?php echo $champs5;?></td>
		<td><?php echo $champs6;?></td>
		</tr>
		<?php
		}
	}
fclose($fp);
unset($fichier);
echo "</table>";
mysqli_close($conn);
?>
