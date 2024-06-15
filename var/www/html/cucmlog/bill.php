<?php
###############  Comdif Innovation CUCM Billing software  ###############
###########################  @Christian Zeler ###########################
/////////////////////INCLUDE/////////////////////
include 'cucminc/varinc.php';
include 'cucminc/connectdb.php';

//Store current timestamp in a VAR
$now = exec("date +\"%s\"");

//////////////////CHECK GET VARIABLES (you can replace get by post in a production system for security)/////////////
if (isset($_GET['dati']) && !isset($_GET['stop']))
	{
	if ($_GET['dati'] == "1") { $lw = ($now - 86400); }
	if ($_GET['dati'] == "2") { $lw = ($now - 604800); }
	if ($_GET['dati'] == "3") { $lw = ($now - 2674800); }
	$SQL1="dateTimeConnect >= $lw AND originalCalledPartyNumber > 0 AND callingPartyNumber > 0 AND duration > 0";
	}
else
	{
	//Default displayed period when you open this page (change it if you need)
	$lw = ($now - 86400);
	$SQL1="dateTimeConnect >= $lw AND originalCalledPartyNumber > 0 AND callingPartyNumber > 0 AND duration > 0 ";
	}
if (isset($_GET['stop']))
	{
	$start = strtotime($_GET['start']); $stop = strtotime($_GET['stop']);
	$SQL1="dateTimeConnect >= $start AND dateTimeConnect <= $stop AND originalCalledPartyNumber > 0 AND callingPartyNumber > 0 AND duration > 0 ";
	}
//Filter on just one phone
if (isset($_GET['telnum']))
	{
	$tphone = $_GET['telnum'];
	$SQL2="AND (originalCalledPartyNumber = $tphone OR callingPartyNumber = $tphone)";
	}
//////////////////MYSQL QUERIES//////////////////
if (isset($SQL2))
	{
	$sql = "SELECT dateTimeConnect, callingPartyNumber, dateTimeDisconnect, originalCalledPartyNumberPartition, 
	origDeviceName, destDeviceName, originalCalledPartyNumber, duration, pkid, dest, cost FROM billing WHERE ($SQL1 $SQL2) ORDER BY dateTimeConnect DESC";
	}
else
	{
	$sql = "SELECT dateTimeConnect, callingPartyNumber, dateTimeDisconnect, originalCalledPartyNumberPartition, 
	origDeviceName, destDeviceName, originalCalledPartyNumber, duration, pkid, dest, cost FROM billing WHERE ($SQL1) ORDER BY dateTimeConnect DESC";
	}
	$result = $conn->query($sql);
				////////////////////////////////////////////////////#### WEB PAGE ####////////////////////////////////////////////////////
include 'menu.php';
echo'<center><table class="blueTable">';
////////////////////////////////////################################ Functions Menu Bar
?>
<thead>
	<tr>
		<th colspan="2">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
				<select name="dati"onchange='this.form.submit()'>
					<option value="">Quick period selection</option>
					<option value="1">Since Last 24 Hours</option>
					<option value="2">Since Last week</option>
					<option value="3">Since Last month</option>
				</select>
			</form>
		</th>
		<th colspan="2">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
			FROM <input name="start" type="date" value="<?php if(isset($_GET['start'])){echo $_GET['start'];} ?>"></input> 
			TO <input name="stop" type="date" value="<?php if(isset($_GET['stop'])){echo $_GET['stop'];} ?>" onchange='this.form.submit()'></input>
			</form>
		</th>
		<th colspan="2"> 
<?php
		if(isset($_GET['stop'])) { echo "From ".date('d-M-Y H:i', $start)." to ".date('d-M-Y H:i', $stop).""; }
		else
		{ echo "From ".date('d-M-Y H:i', $lw)." to ".date('d-M-Y H:i', $now).""; }
?>
		</th>
		<th colspan="2">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
				<select name="telnum" onchange='this.form.submit()'>
					<option value=""> Select Phone </option>
<?php
					$checph = mysqli_query($conn,"SELECT userid FROM enduser");
					while($ph=mysqli_fetch_row($checph))
						{
						echo'<option value="'.$ph[0].'">'.$ph[0].'</option>';
						}
				echo "</select>";
			if (isset($_GET['stop']))
				{
				echo "<input type=\"hidden\" name=\"start\" value=\"".$_GET['start']."\" />";
				echo "<input type=\"hidden\" name=\"stop\" value=\"".$_GET['stop']."\" />"; 
				}
			if (isset($_GET['dati']))
				{
				echo "<input type=\"hidden\" name=\"dati\" value=\"".$_GET['dati']."\" />";
				}
echo'</form></th></tr></thead>';
////////////////////////////////////################################ TITLE Table header
echo'<thead>
	<tr>
		<th>Start</th>
		<th>CallerID</th>
		<th>End</th>
		<th>Trunk</th>
		<th>Destination Number</th>
		<th>Destination</th>
		<th>Cost (cents)</th>
		<th>Duration Sec.</th>
	</tr>
</thead><tbody>';
?>

<tr><td colspan="8" align="center"><a href="cron.php" target="blank">Billing CDRS are Updated nightly, to update them now click here
	</a></td><tr>
<?php
if ($result->num_rows > 0)
	{
	  while($row = $result->fetch_row())
		{
		///////////// Check if phone exist in user list Be sure to alway have a updated user list: use the Update Phone List link /////////////
		$checkcalleridexist = mysqli_query($conn,"SELECT * FROM enduser WHERE userid = '$row[1]'");
		$cidexist = mysqli_fetch_row($checkcalleridexist);
		if(!empty($cidexist[0]))
			{
			echo "<tr><td>".date('m/d/Y H:i:s', $row[0])."</td>"; // Start
			///////////////////////////////////// Click on the number to filter result ///////////////////////////////////////////
			if (isset($_GET['dati']) && !isset($_GET['stop']))
				{
				echo"<td title='Click to filter on this phone number'><a href='".$_SERVER['PHP_SELF']."?telnum=".$row[1]."&dati=".$_GET['dati']."'>";
				}
			elseif (!isset($_GET['dati']) && !isset($_GET['stop']))
				{
				echo"<td title='Click to filter on this phone number'><a href='".$_SERVER['PHP_SELF']."?telnum=".$row[1]."'>";
				}
			else
				{
				echo"<td title='Click to filter on this phone number'><a href='".$_SERVER['PHP_SELF']."?telnum=".$row[1]."&start=".$_GET['start']."&stop=".$_GET['stop']."'>";
				}

			///////////////////////////////////// Show phone number and name ////////////////////////////////////////
			echo $row[1]."</a>&nbsp;&nbsp; - &nbsp;&nbsp;<font color='blue'>".$cidexist[1]."</font></td>";

		echo"<td>".date('m/d/Y H:i:s', $row[2])."</td>"; // End
		echo"<td>".$row[5]."</td>"; // Destination Trunk

		/////////////////////////////////////////////////////////////////////
		echo"<td>".$row[6]."</td>"; // Destination Number
		echo"<td>".$row[9]."</td>";
		$tprice = round(($row[10]/100),2);
		echo"<td>".$tprice."</td>";
		echo"<td>".$row[7]."</td></tr>";
		// Just to avoid a php error in log
		if(!isset($ftime)){$ftime = "0"; $fprice = "0";}
			$fprice = ( $fprice + $tprice);
			$ftime = ( $row[7] + $ftime); 
			}
		}
	echo'<tr><td colspan="6" align="right">TOTAL </td><td>'.$fprice.'</td><td>'.$ftime.'</td></tr>';
	echo "</tbody></table></center>";
	}
else
	{
	echo "</tbody></table>";
	echo "<center>No Cdrs</center>";
	}
$conn->close();
?>
