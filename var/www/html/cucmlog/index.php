<?php
/////////////////////INCLUDE/////////////////////
include 'cucminc/varinc.php';
include 'cucminc/connectdb.php';

//////////////////////DEFINE VAR/////////////////
$now = exec("date +\"%s\"");

//////////////////////PURGE DATA/////////////////
$conn->query("DELETE from calldetails WHERE originalCalledPartyNumber IS NULL OR callingPartyNumber IS NULL OR originalCalledPartyNumber ='' OR callingPartyNumber = ''");
//$conn->query("DELETE from calldetails WHERE callingPartyNumber IS NULL");

//////////////////CHECK GET VARIABLES/////////////
if (isset($_GET['dati']) && !isset($_GET['stop']))
	{
	if ($_GET['dati'] == "1") { $lw = ($now - 86400); }
	if ($_GET['dati'] == "2") { $lw = ($now - 604800); }
	if ($_GET['dati'] == "3") { $lw = ($now - 2674800); }
	$SQL1="dateTimeOrigination >= $lw AND originalCalledPartyNumber > 0 AND callingPartyNumber > 0";
	}
else
	{
	$lw = ($now - 604800);
	$SQL1="dateTimeOrigination >= $lw AND originalCalledPartyNumber > 0 AND callingPartyNumber > 0";
	}
if (isset($_GET['stop']))
	{
	$start = strtotime($_GET['start']); $stop = strtotime($_GET['stop']);
	$SQL1="dateTimeOrigination >= $start AND dateTimeOrigination <= $stop AND originalCalledPartyNumber > 0 AND callingPartyNumber > 0";
	}
if (isset($_GET['telnum']))
	{
	$tphone = $_GET['telnum'];
	$SQL2="AND (originalCalledPartyNumber = $tphone OR callingPartyNumber = $tphone)";
	}
//////////////////MYSQL QUERIES//////////////////
if (isset($SQL2))
	{
	$sql = "SELECT dateTimeOrigination, callingPartyNumber, dateTimeDisconnect, originalCalledPartyNumberPartition, 
	origDeviceName, destDeviceName, originalCalledPartyNumber, duration FROM calldetails WHERE ($SQL1 $SQL2) ORDER BY dateTimeOrigination DESC";
	}
else
	{
	$sql = "SELECT dateTimeOrigination, callingPartyNumber, dateTimeDisconnect, originalCalledPartyNumberPartition, 
	origDeviceName, destDeviceName, originalCalledPartyNumber, duration FROM calldetails WHERE ($SQL1) ORDER BY dateTimeOrigination DESC";
	}
	$result = $conn->query($sql);
//////////////////#### WEB PAGE ####//////////////////
include 'menu.php';
?><center><table class="blueTable">
<thead>
	<tr>
		<th colspan="2">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
			<select name="dati"onchange='this.form.submit()'>
			<option value="">- Speed Select -</option>
			<option value="1">Since Last 24 Hours</option>
			<option value="2">Since Last week</option>
			<option value="3">Since Last month</option>
			</select>
			</form>
		</th>
		<th colspan="2">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
			or START <input name="start" type="date" value="<?php if(isset($_GET['start'])){echo $_GET['start'];} ?>"></input> 
			STOP <input name="stop" type="date" value="<?php if(isset($_GET['stop'])){echo $_GET['stop'];} ?>" onchange='this.form.submit()'></input>
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
		if (isset($_GET['dati'])) { echo "<input type=\"hidden\" name=\"dati\" value=\"".$_GET['dati']."\" />"; }
		?>
		</form>
		</th>

	</tr>
</thead>
<thead>
	<tr>
		<th>Start</th>
		<th>CallerID</th>
		<th>End</th>
		<th>Partition</th>
		<th>From Device</th>
		<th>Destination Device</th>
		<th>Destination Number</th>
		<th>Duration Sec.</th>
	</tr>
</thead>
<tbody>

<?php
if ($result->num_rows > 0)
	{
	  while($row = $result->fetch_row())
		{
		echo "<tr><td>".date('m/d/Y H:i:s', $row[0])."</td>";
		$checkcalleridexist = mysqli_query($conn,"SELECT * FROM enduser WHERE userid = '$row[1]'");
		$cidexist = mysqli_fetch_row($checkcalleridexist);
		if(!empty($cidexist[0]))
			{
			echo"<td>".$row[1]."&nbsp;&nbsp; - &nbsp;&nbsp;<font color='blue'>".$cidexist[1]."</font></td>";
			}
		else
			{
			echo"<td>".$row[1]."</td>";
			}
		echo"<td>".date('m/d/Y H:i:s', $row[2])."</td>";
		echo"<td>".$row[3]."</td>";
		echo"<td>".$row[4]."</td>";
		echo"<td>".$row[5]."</td>";
		$checkcalledexist = mysqli_query($conn,"SELECT * FROM enduser WHERE userid = '$row[6]'");
		$calledexist = mysqli_fetch_row($checkcalledexist);
		if(!empty($calledexist[0]))
			{
			echo"<td>".$row[6]."&nbsp;&nbsp; - &nbsp;&nbsp;<font color='blue'>".$calledexist[1]."</font></td>";
			}
		else
			{
			echo"<td>".$row[6]."</td>";
			}
		echo"<td>".$row[7]."</td>
		</tr>";
		}
	echo "</tbody></table></center>";
	}
else
	{
	echo "</tbody></table>";
	echo "<center>No Cdrs</center>";
	}
$conn->close();
?>
