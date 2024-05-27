<?php
$conn = new mysqli($dbhost,$dbuser,$dbpwd,$dbname);
if ($conn -> connect_errno)
	{
  	echo "Failed to connect to MySQL: " . $conn -> connect_error; exit();
	}
?>
