<?php
###############  Comdif Innovation CUCM Billing software  ###############
###########################  @Christian Zeler ###########################
include 'menu.php';
include 'cucminc/varinc.php';
$curdir = getcwd(); $directory = $curdir."/cucminc/";
$filename = "bilvar.php"; $fn = $directory . $filename;
$cisfilename = "ciscovar.php"; $fncis = $directory . $cisfilename;
$varfilename = "varinc.php"; $fnvar = $directory . $varfilename;
$special_chars = array("\\", ";", "\"", "$");

############################### BASE CONFIGURATION ###############################
if(isset($_POST['form3']) && isset($_POST['dbhost']) && isset($_POST['dbport']) && isset($_POST['dbuser']))
	{
	if(count( array_intersect( str_split($_POST['dbhost']),$special_chars)) > 0 )
	{echo"$ or ; or \" and \\ are forbidden characters for db host."; exit(0);}
	if(count( array_intersect( str_split($_POST['dbuser']),$special_chars)) > 0 )
	{echo"$ or ; or \" and \\ are forbidden characters for db User."; exit(0);}
	if(!empty($_POST['dbpwd']))
		{
		if(count( array_intersect( str_split($_POST['dbpwd']),$special_chars)) > 0 )
		{echo"$ or ; or \" and \\ are forbidden characters for db Password."; exit(0);}
		}
	if(!empty($_POST['dbport']) && !is_numeric($_POST['dbport']))
	{ echo"Port must be numeric"; exit(0); }
	$file = fopen($fnvar,"w");
	fwrite($file,"<?php".PHP_EOL);
	fwrite($file,"\$dbhost = \"".$_POST['dbhost']."\";".PHP_EOL);
	fwrite($file,"\$dbport = \"".$_POST['dbport']."\";".PHP_EOL);
	fwrite($file,"\$dbuser = \"".$_POST['dbuser']."\";".PHP_EOL);
	if(!empty($_POST['dbpwd']))
		{
		fwrite($file,"\$dbpwd = \"".$_POST['dbpwd']."\";".PHP_EOL);
		}
	else
		{
		fwrite($file,"\$dbpwd = \"".$dbpwd."\";".PHP_EOL);
		}
	fwrite($file,"\$dbname = \"".$_POST['dbname']."\";".PHP_EOL);
	fwrite($file,"\$bashtble = \"".$_POST['bashtble']."\";".PHP_EOL);
	fwrite($file,"include 'ciscovar.php';".PHP_EOL);
	fwrite($file,"include 'bilvar.php';".PHP_EOL);
	fwrite($file,"?>".PHP_EOL);
	fclose($file);
	echo "<center>Change done successfully</center>";
	echo "<SCRIPT type='text/javascript'>"; echo'window.location.replace("'.$_SERVER['PHP_SELF'].'")'; echo"</SCRIPT>";
	}
############################### CISCO CONFIGURATION ###############################
if(isset($_POST['form2']) && isset($_POST['axlhost']))
	{

	if (!filter_var($_POST['axlhost'], FILTER_VALIDATE_IP)) { echo $_POST['axlhost']." Is not a valid IP address"; exit(0); }
	if(count( array_intersect( str_split($_POST['axlusername']),$special_chars)) > 0 )
	{echo"$ or ; or \" and \\ are forbidden characters in Username."; exit(0);}
	if(isset($_POST['axlpassword']) && !empty($_POST['axlpassword']))
		{
		if(count( array_intersect( str_split($_POST['axlpassword']),$special_chars)) > 0 )
		{echo"$ or ; or \" and \\ are forbidden characters in Password."; exit(0);}
		}

	$file = fopen($fncis,"w");
	fwrite($file,"<?php".PHP_EOL);
	fwrite($file,"\$axlhost = \"".$_POST['axlhost']."\";".PHP_EOL);
	fwrite($file,"\$axlusername = \"".$_POST['axlusername']."\";".PHP_EOL);
	if(!empty($_POST['axlpassword']))
		{
		fwrite($file,"\$axlpassword = \"".$_POST['axlpassword']."\";".PHP_EOL);
		}
	else
		{
		fwrite($file,"\$axlpassword = \"".$axlpassword."\";".PHP_EOL);
		}
	fwrite($file,"?>".PHP_EOL);
	fclose($file);
	echo "<center>Change done successfully</center>";
	echo "<SCRIPT type='text/javascript'>"; echo'window.location.replace("'.$_SERVER['PHP_SELF'].'")'; echo"</SCRIPT>";
	}
############################### BILLING CONFIGURATION ###############################
if(isset($_POST['form1']) && isset($_POST['trunk1']))
	{
	if(!empty($_POST['ccode']) && !is_numeric($_POST['ccode']))
	{ echo"Country code must be numeric"; exit(0); }
	if(!empty($_POST['lclpref']) && !is_numeric($_POST['lclpref']))
	{ echo"First digit must be numeric"; exit(0); }
	if(!empty($_POST['prefix1']) && !is_numeric($_POST['prefix1']))
	{ echo"Trunk prefix must be numeric"; exit(0); }
	if(!empty($_POST['prefix2']) && !is_numeric($_POST['prefix2']))
	{ echo"Trunk prefix must be numeric"; exit(0); }
	if(!empty($_POST['prefix3']) && !is_numeric($_POST['prefix3']))
	{ echo"Trunk prefix must be numeric"; exit(0); }
	if(!empty($_POST['prefix4']) && !is_numeric($_POST['prefix4']))
	{ echo"Trunk prefix must be numeric"; exit(0); }
	if(!empty($_POST['prefix5']) && !is_numeric($_POST['prefix5']))
	{ echo"Trunk prefix must be numeric"; exit(0); }
	if(count( array_intersect( str_split($_POST['trunk1']),$special_chars)) > 0 )
	{echo"$ or ; or \" and \\ are forbidden characters in Trunk name."; exit(0);}
	if(count( array_intersect( str_split($_POST['trunk2']),$special_chars)) > 0 )
	{echo"$ or ; or \" and \\ are forbidden characters in Trunk name."; exit(0);}
	if(count( array_intersect( str_split($_POST['trunk3']),$special_chars)) > 0 )
	{echo"$ or ; or \" and \\ are forbidden characters in Trunk name."; exit(0);}
	if(count( array_intersect( str_split($_POST['trunk4']),$special_chars)) > 0 )
	{echo"$ or ; or \" and \\ are forbidden characters in Trunk name."; exit(0);}
	if(count( array_intersect( str_split($_POST['trunk5']),$special_chars)) > 0 )
	{echo"$ or ; or \" and \\ are forbidden characters in Trunk name."; exit(0);}

	$file = fopen($fn,"w");
	fwrite($file,"<?php".PHP_EOL);
    	fwrite($file,"\$ctrycode = \"".$_POST['ccode']."\";".PHP_EOL);
    	fwrite($file,"\$lclprefix = \"".$_POST['lclpref']."\";".PHP_EOL);
    	fwrite($file,"\$trunk1 = \"".$_POST['trunk1']."\";".PHP_EOL);
	fwrite($file,"\$trunk2 = \"".$_POST['trunk2']."\";".PHP_EOL);
	fwrite($file,"\$trunk3 = \"".$_POST['trunk3']."\";".PHP_EOL);
	fwrite($file,"\$trunk4 = \"".$_POST['trunk4']."\";".PHP_EOL);
	fwrite($file,"\$trunk5 = \"".$_POST['trunk5']."\";".PHP_EOL);
	fwrite($file,"\$cucmprefix1 = \"".$_POST['prefix1']."\";".PHP_EOL);
	fwrite($file,"\$cucmprefix2 = \"".$_POST['prefix2']."\";".PHP_EOL);
	fwrite($file,"\$cucmprefix3 = \"".$_POST['prefix3']."\";".PHP_EOL);
	fwrite($file,"\$cucmprefix4 = \"".$_POST['prefix4']."\";".PHP_EOL);
	fwrite($file,"\$cucmprefix5 = \"".$_POST['prefix5']."\";".PHP_EOL);
	fwrite($file,"?>".PHP_EOL);
	fclose($file);
	echo "<center>Change done successfully</center>";
	echo "<SCRIPT type='text/javascript'>"; echo'window.location.replace("'.$_SERVER['PHP_SELF'].'")'; echo"</SCRIPT>";
	}
############################### FORM FOR BILLING CONFIGURATION ###############################
	echo'<center>BASE BILLING SETTINGS</center>';
	echo"<form method='POST' action='".$_SERVER['PHP_SELF']."'>";
	echo'<center><table <table class="blueTable">';
	echo'<tr><td>Country Code</td><td><input type="text" name="ccode" value="'.$ctrycode.'"></td><td>Change the prefix for your local country code eg. 255 for Tanzania, 33 for France,..</td></tr>';
	echo'<tr><td>First Digit</td><td><input type="text" name="lclpref" value="'.$lclprefix.'"></td><td>Enter the expected first digit (usually 0) for a local call</td></tr>';
	echo'<tr><td>Trunk Prefix 1</td><td><input type="text" name="prefix1" value="'.$cucmprefix1.'"></td><td>Enter any trunk prefix that you use in the CUCM to make outbound call here eg.. 99 or leave it empty.</td></tr>';
	echo'<tr><td>Trunk Prefix 2</td><td><input type="text" name="prefix2" value="'.$cucmprefix2.'"></td><td>Other prefix if any.</td></tr>';
	echo'<tr><td>Trunk Prefix 3</td><td><input type="text" name="prefix3" value="'.$cucmprefix3.'"></td><td>Other prefix if any.</td></tr>';
	echo'<tr><td>Trunk Prefix 4</td><td><input type="text" name="prefix4" value="'.$cucmprefix4.'"></td><td>Other prefix if any.</td></tr>';
	echo'<tr><td>Trunk Prefix 5</td><td><input type="text" name="prefix5" value="'.$cucmprefix5.'"></td><td>Other prefix if any.</td></tr>';
	echo'<tr><td>Trunk 1</td><td><input type="text" name="trunk1" value="'.$trunk1.'"></td><td>Enter the EXACT outbound trunk name from the CUCM, Check it on CUCM - Device > Trunk > Name</td></tr>';
	echo'<tr><td>Trunk 2</td><td><input type="text" name="trunk2" value="'.$trunk2.'"></td><td>Other trunk if any.</td></tr>';
	echo'<tr><td>Trunk 3</td><td><input type="text" name="trunk3" value="'.$trunk3.'"></td><td>Other trunk if any.</td></tr>';
	echo'<tr><td>Trunk 4</td><td><input type="text" name="trunk4" value="'.$trunk4.'"></td><td>Other trunk if any.</td></tr>';
	echo'<tr><td>Trunk 5</td><td><input type="text" name="trunk5" value="'.$trunk5.'"></td><td>Other trunk if any.</td></tr>';
	echo'<input type="hidden" name="form1" value="changeform1">';
	echo'<tr><td colspan="3"><input type="Submit"></form></td></tr>';
	echo'</table></center>';
############################### FORM FOR CUCM CONFIGURATION ###############################	
	echo'<center>BASE CUCM CONFIGURATION (Needed for the Import Phones-Users function)</center>';
	echo"<form method='POST' action='".$_SERVER['PHP_SELF']."'>";
	echo'<center><table <table class="blueTable">';
	echo'<tr><td>CUCM IP Address</td><td><input type="text" name="axlhost" value="'.$axlhost.'"></td><td>ENTER Your CUCM IP Address</td></tr>';
	echo'<tr><td>AXL Username</td><td><input type="text" name="axlusername" value="'.$axlusername.'"></td><td>Enter the AXL Username defined in the CUCM  --> User Management > Application User</td></tr>';
	echo'<tr><td>AXL Password</td><td><input type="password" name="axlpassword" value="" title="LEAVE EMPTY FOR NO CHANGE !!"><font color="red"> LEAVE EMPTY FOR NO CHANGE !!</font></td><td>Enter the AXL Password for the username defined in the CUCM.</td></tr>';
	echo'<input type="hidden" name="form2" value="changeform2">';
	echo'<tr><td colspan="3"><input type="Submit"></form></td></tr>';
	echo'</table></center>';
############################### FORM FOR BASE CONFIGURATION ###############################
	echo'<center>BASE SYSTEM CONFIGURATION (Setup fresh installation of this interface) <font color="red">DO NOT CHANGE ANYTHING HERE IF YOUR APPLICATION IS WORKING FINE</font></center>';
	echo"<form method='POST' action='".$_SERVER['PHP_SELF']."'>";
	echo'<center><table <table class="blueTable">';
	echo'<tr><td>Database IP or Hostname</td><td><input type="text" name="dbhost" value="'.$dbhost.'"></td><td>ENTER Your local database IP Address or HOSTNAME (Usualy 127.0.0.1 or localhost)</td></tr>';
	echo'<tr><td>Database port</td><td><input type="text" name="dbport" value="'.$dbport.'"></td><td>ENTER Your local database port (Usualy 3306)</td></tr>';
	echo'<tr><td>Database user</td><td><input type="text" name="dbuser" value="'.$dbuser.'"></td><td>ENTER Your local database user ()</td></tr>';
	echo'<tr><td>Database Password</td><td><input type="password" name="dbpwd" value=""><font color="red"> LEAVE EMPTY FOR NO CHANGE !!</font></td><td>ENTER Your local database Password ()</td></tr>';
	echo'<input type="hidden" name="bashtble" value="calldetails">';
	echo'<input type="hidden" name="dbname" value="calldata">';	
	echo'<input type="hidden" name="form3" value="changeform3">';
	echo'<tr><td colspan="3"><input type="Submit"></form></td></tr>';
	echo'</table></center>';
?>
