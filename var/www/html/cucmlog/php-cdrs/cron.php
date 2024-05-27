<?php

//THIS FILE IS NOT IN USE 

$path = '/var/www/html/CDRS';
$opath = '/var/www/html/CDRS/';

if ($handle = opendir($path))
	{
	while (false !== ($entry = readdir($handle)))
		{
		if ($entry != "." && $entry != "..")
			{
			$start=explode("_",$entry);
			if($start[0] == "cdr")
				{
 				//echo $entry."<br/>";
				$fto = file($opath.$entry);
				$total = count($fto);
				for($i = 2; $i < $total; $i++)
					{
					$op= nl2br($fto[$i]);
					$ndat = str_replace("\"","",$op);
					$data = explode(",",$ndat);
					$sql = "INSERT INTO calldetails ( dateTimeOrigination, callingPartyNumber, dateTimeDisconnect, originalCalledPartyNumberPartition, 
					origDeviceName, destDeviceName, originalCalledPartyNumber, duration ) VALUE ( $data[4], $data[8], $data[48], $data[50], $data[51], 
					$data[56], $data[57], $data[29], $data[55]	)";
					mysqli_query($conn,$sql);

					echo" LINE ";
					echo $data[4]; //dateTimeOrigination
					echo" ---- ";
					echo $data[8]; //callingPartyNumber
					echo" ---- ";
					echo $data[48]; //dateTimeDisconnect
					echo" ---- ";
					echo $data[50]; //pkid
					echo" ---- ";
					echo $data[51]; //originalCalledPartyNumberPartition
					echo" ---- ";
					echo $data[56]; //origDeviceName
					echo" ---- ";
					echo $data[57]; //destDeviceName
					echo" ---- ";
					echo $data[29]; //originalCalledPartyNumber
					echo" ---- ";
					echo $data[55]; //duration
					echo" INSERTED ";
					echo"<br/>";
					}
				}	

			}
		}
	closedir($handle);
	}