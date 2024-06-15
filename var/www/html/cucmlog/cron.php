<?php
###############  Comdif Innovation CUCM Billing software  ###############
###########################  @Christian Zeler ###########################
include 'cucminc/varinc.php';
include 'cucminc/connectdb.php';
$now = exec("date +\"%s\""); $lw = ($now - 604800);

$rmtime = strtotime("-1 year", time());
$delold = mysqli_query($conn,"DELETE FROM calldetails WHERE dateTimeOrigination <= $rmtime");
$deloldy = mysqli_query($conn,"DELETE FROM billing WHERE dateTimeConnect <= $rmtime");

//Check in conf file if the CUCM use multi outbound trunk this must be defined in the bilvar.php file
$f = @fopen('cucminc/bilvar.php', 'r'); $lines = array();
while (!feof($f)){ $line = fgets($f, 1024); if (strpos($line, 'trunk') === FALSE) continue; $lines[] = $line; } fclose($f); $dt = count($lines);
for($i=0;$i < $dt;$i++) { $tr = explode('"', $lines[$i]); if(!empty($tr[1])) { $trk["$i"] = "$tr[1]"; } }
$cot = count($trk);
$Start = "WHERE (destDeviceName LIKE '$trk[0]'";
for($i=1;$i < $cot;$i++)
	{
	$And .=" OR destDeviceName LIKE '$trk[$i]'";
	}
if(isset($And))
	{			
	$SQL3 = ''.$Start.''.$And.')';
	}
else
	{
	$SQL3 = ''.$Start.')';
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////Check in conf file if the CUCM use any trunk prefix this must be defined in the bilvar.php file
$p = @fopen('cucminc/bilvar.php', 'r'); $alines = array();
while (!feof($p)){ $aline = fgets($p, 1024); if (strpos($aline, 'cucmprefix') === FALSE) continue; $alines[] = $aline; } fclose($p); $adt = count($alines);
if($adt != 0)
	{
	for($i=0;$i < $dt;$i++) { $atr = explode('"', $alines[$i]); if(!empty($atr[1])) { $pref["$i"] = "$atr[1]"; } }
	if ( isset($pref)){ $acot = count($pref); }
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////

$sql = "SELECT dateTimeConnect, callingPartyNumber, dateTimeDisconnect, originalCalledPartyNumberPartition, 
origDeviceName, destDeviceName, originalCalledPartyNumber, duration, pkid FROM calldetails $SQL3 AND dateTimeConnect >= $lw";
$result = $conn->query($sql);
if ($result->num_rows > 0)
	{
	  while($row = $result->fetch_row())
		{
		// Remove trunk prefix if any
		if( isset($acot) && $acot != 0 )
			{
			for($i=0;$i < $acot;$i++)
				{
				$lo = strlen($pref[$i]);
				if (preg_match('/^'.$pref[$i].'.+/', $row[6])){ $row[6] = substr($row[6], $lo); }
				//echo $pref[$i].'<br/>';
				}
			}
		// Check if the number is local and start with a 0 if yes remove it and add the local country code
		if (preg_match('#^0[1-9]#', $row[6]) === 1)
			{
			$row[6] = substr($row[6] , 1); $row[6] = $ctrycode.$row[6];
			}
		$checkrate = mysqli_query($conn,"SELECT * FROM routes WHERE $row[6] RLIKE concat('^', pattern) ORDER BY LENGTH(pattern) DESC");
		$cdata = mysqli_fetch_row($checkrate);
		$tprice = round(((($cdata[6] / 60)*$row[7])),0);

		$mreq="INSERT INTO billing VALUES ('$row[0]', '$row[1]', '$row[2]', '$row[3]', 
		'$row[4]', '$row[5]', '$row[6]', '$row[7]', '$row[8]', '$cdata[1]', '$tprice')";
		mysqli_query($conn,$mreq);
		// IF DEBUG NEEDED
		/*		
		if (!mysqli_query($conn,$mreq))
			{
			echo("Error description: " . mysqli_error($conn));
			}
		*/
		}
	$conn->close();
	}	
