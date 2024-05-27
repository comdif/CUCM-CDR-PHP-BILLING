<?php
include 'cucminc/varinc.php';
include 'cucminc/connect.php';
include 'cucminc/connectdb.php';
include 'menu.php';
echo'<center><table class="blueTable">';
echo '<tr><td colspan="3" ><center><a href="'.$_SERVER['PHP_SELF'].'?update=yes">Update Phone List</a></center></td></tr>';
if (isset($_GET['update']))
	{
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
			echo"<tr><td>".$user->userid."</td><td>".$user->lastName."</td><td>".$user->firstName." Inserted in the DB</td></tr>";
			}
		else
			{
			mysqli_query($conn,"UPDATE enduser SET lastname = '$user->lastName', 
			firstname = '$user->firstName' WHERE userid = '$user->userid'");
			echo"<tr><td>USER: ".$user->userid."</td><td>".$user->lastName."</td><td>".$user->firstName." Updated in the DB</td></tr>";
			}
		}
	}
else
	{
	$listit = mysqli_query($conn,"SELECT * FROM enduser");
	while($lu = mysqli_fetch_row($listit))
		{
		echo"<tr><td>".$lu[0]."</td><td>".$lu[1]."</td><td>".$lu[2]."</td></tr>";
		}
	}
echo "</table></center>";
?>