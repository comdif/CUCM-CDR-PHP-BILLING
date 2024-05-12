<?php
// 2024@christian Zeler COMDIF INNOVATION christian@comdif.com
//This page is just a draft while waiting for new features in progress.
// You can already view your cdrs and import your users from the CUCM

/////////////////////INCLUDE/////////////////////
include '/var/www/html/cucminc/varinc.php';
include '/var/www/html/cucminc/connectdb.php';
//////////////////////DEFINE VAR/////////////////
$now = exec("date +\"%s\"");
//////////////////////PURGE DATA/////////////////
$conn->query("DELETE from calldetails WHERE originalCalledPartyNumber IS NULL");
$conn->query("DELETE from calldetails WHERE callingPartyNumber  IS NULL");
//////////////////CHECK GET VARIABLES/////////////
	if (isset($_GET['dati']))
		{
		if ($_GET['dati'] == "1") { $lw = ($now - 86400); }
		if ($_GET['dati'] == "2") { $lw = ($now - 604800); }
		if ($_GET['dati'] == "3") { $lw = ($now - 2674800); }
		}
	else
		{
		$lw = ($now - 604800);
		}
//////////////////MYSQL QUERIES//////////////////
$sql = "SELECT dateTimeOrigination, callingPartyNumber, dateTimeDisconnect, originalCalledPartyNumberPartition, 
origDeviceName, destDeviceName, originalCalledPartyNumber, duration FROM calldetails WHERE (dateTimeOrigination >= $lw AND originalCalledPartyNumber > 0 AND callingPartyNumber > 0) ORDER BY dateTimeOrigination DESC";
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
		</th>
		<th colspan="2">
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
