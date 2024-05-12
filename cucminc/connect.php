<?php
// To connect the call manager no change to do here
$context =  
	stream_context_create(array('ssl'=>array('verify_peer_name'=>false,
	'allow_self_signed'=>true,
	'verify_peer'=>false,
	'cafile'=>"/var/www/html/cucm-pub.abc.inc"
	)));

$client = new SoapClient("/var/www/html/cmanlib/AXLAPI.wsdl",
	array('trace'=>true,
	'exceptions'=>true,
	'location'=>"https://".$axlhost.":8443/axl",
	'login'=>$axlusername,
	'password'=>$axlpassword,
	'stream_context'=>$context
	));
?>
