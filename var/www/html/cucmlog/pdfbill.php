<?php
include 'cucminc/varinc.php';
include 'cucminc/connectdb.php';
?>
<style type="text/css">
ttable.blueTable { border: 5px double #1C6EA4;  background-color: #EEEEEE; width: 80%; height: px; text-align: left; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; padding: 2px 4px 2px 4px; }
table.blueTable th { border: 1px solid #AAAAAA; padding: 6px 8px; }
table.blueTable td { border: 1px solid #AAAAAA; padding: 6px 8px; }
table.blueTable tbody td { font-size: 11px; font-weight: bold; }
table.blueTable tr:nth-child(even) { background: #D0E4F5; }
table.blueTable thead { background: #1C6EA4; background: -moz-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%); background: -webkit-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
background: linear-gradient(to bottom, #5592bb 0%, #327cad 66%, #1C6EA4 100%); border-bottom: 2px solid #444444; }
table.blueTable thead th { font-size: 15px; font-weight: bold; olor: #FFFFFF; order-left: 2px solid #D0E4F5; }
table.blueTable thead th:first-child { border-left: none; }
</style>
<?php
$now = exec("date +\"%s\""); $lw = ($now - 604800);
$sCdrsql = "select * from billing WHERE dateTimeConnect >= $lw ORDER BY callingPartyNumber DESC";
?>
<table align="left">
	<tr><td><img src="ico/pdflogo.png"></td></tr>
</table>
<br/>
<table class="blueTable" align="center">
	<thead>
	<tr>
	<th width="78">Start date</th>
	<th width="38">From</th>
	<th width="65">End time</th>
	<th width="63">Number</th>
	<th width="100">Destination</th>
	<th width="80">Duration</th>
	<th width="55">Cost</th>
	</tr>
	</thead><tbody>
	  
<?php
function ConvTime($Time)
	{
	if($Time < 3600)
		{ 
		$heures = 0;
			if($Time < 60)
				{$minutes = 0;} 
			else
				{$minutes = round($Time / 60);} 
			$secondes = floor($Time % 60); 
		} 
	else
		{ 
		$heures = round($Time / 3600); 
		$secondes = round($Time % 3600); 
		$minutes = floor($secondes / 60); 
		} 
	$secondes2 = round($secondes % 60); 
	$TimeFinal = "$heures H: $minutes : $secondes2"; 
	return $TimeFinal; 
	}

$res = mysqli_query($conn,$sCdrsql);
while($row=mysqli_fetch_row($res))
	{
	$checkcalleridexist = mysqli_query($conn,"SELECT * FROM enduser WHERE userid = '$row[1]'");
	$cidexist = mysqli_fetch_row($checkcalleridexist);
	if(!empty($cidexist[0]))
		{
		if(isset($u) && $u != $row[1])
			{
			echo'<tr><td colspan="3"></td><td colspan="2">TOTAL:</td><td style="text-align:center;">'.ConvTime($t).'</td><td style="text-align:center;">'.round(($tprice / 10000),2).'</td></tr>';
			unset($t);
			unset($tprice);
			}
		$bgcl="#ceeaf5";
		echo'<tr>
		<td style="border: 1px solid blue; text-align:center;background-color: '.$bgcl.';">'.date('m/d/Y H:i:s', $row[0]).'</td>
		<td style="border: 1px solid blue; text-align:center;background-color: '.$bgcl.';">'.$row[1].'</td>
		<td style="border: 1px solid blue; text-align:center;background-color: '.$bgcl.';">'.date('H:i:s', $row[2]).'</td>
		<td style="border: 1px solid blue; text-align:center;background-color: '.$bgcl.';">'.$row[6].'</td>
		<td width="100" style="border: 1px solid blue; text-align:center;background-color: '.$bgcl.';">'.$row[9].'</td>
		<td style="border: 1px solid blue; text-align:center;background-color: '.$bgcl.';">'.$row[7].' Sec</td>
		<td style="border: 1px solid blue; text-align:center;background-color: '.$bgcl.';">'.round(($row[10] / 10000),3).'</td>
		</tr>';
		$t = ($row[7] + $t);
		$tprice = ($row[10] + $tprice);
		$u = $row[1];
		}
	}
echo'<tr><td colspan="3"></td><td colspan="2"></td><td style="text-align:center;">'.ConvTime($t).'</td><td style="text-align:center;">'.round(($tprice / 10000),2).'</td></tr>';
echo'</tbody></table>';
?>
