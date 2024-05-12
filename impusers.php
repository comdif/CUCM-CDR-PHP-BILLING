<?php
// Import users and exten number from the CUCM
include '/var/www/html/cucminc/varinc.php';
include '/var/www/html/cucminc/connect.php';
include '/var/www/html/cucminc/connectdb.php';
include 'menu.php';
echo'<center><table class="blueTable">';

$returnedTags = array("firstName"=>"","lastName"=>"","userid"=>"");
$searchCriteria = array("userid"=>"%");
try
	{
	$response = $client->listUser(array("returnedTags"=>
	$returnedTags,"searchCriteria"=>$searchCriteria));
	}
catch (SoapFault $sf)
	{
	echo "SoapFault: " . $sf . "<BR>";
	}
catch (Exception $e)
	{
	echo "Exception: ". $e ."<br>";
	}
foreach($response->return->user as $user)
	{
	$testit = mysqli_query($conn,"SELECT userid FROM enduser WHERE userid = '$user->userid'");
	$what = mysqli_fetch_row($testit);

	if(empty($what[0]))
		{
		mysqli_query($conn,"INSERT INTO enduser VALUES ('$user->userid','$user->lastName','$user->firstName')");
		echo("<tr><td>USER: ".$user->userid." -- ".$user->lastName." -- ".$user->firstName." Inserted in the DB</td></tr>");
		}
	else
		{
		mysqli_query($conn,"UPDATE enduser SET lastname = '$user->lastName', 
		firstname = '$user->firstName' WHERE userid = '$user->userid'");
		echo("<tr><td>USER: ".$user->userid." -- ".$user->lastName." -- ".$user->firstName." Updated in the DB</td></tr>");
		}
	}
echo "</table></center>";
?>
