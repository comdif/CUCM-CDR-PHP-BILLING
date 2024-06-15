<?php
###############  Comdif Innovation CUCM Billing software  ###############
###########################  @Christian Zeler ###########################
echo'<head>
<style>
table.blueTable { border: 5px double #1C6EA4;  background-color: #EEEEEE; width: 80%; height: px;
text-align: left; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; padding: 2px 4px 2px 4px; }
table.blueTable th { border: 1px solid #AAAAAA; padding: 6px 8px; }
table.blueTable td { border: 1px solid #AAAAAA; padding: 6px 8px; }
table.blueTable tbody td { font-size: 11px; font-weight: bold; }
table.blueTable tr:nth-child(even) { background: #D0E4F5; }
table.blueTable thead { background: #1C6EA4; background: -moz-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%); background: -webkit-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
background: linear-gradient(to bottom, #5592bb 0%, #327cad 66%, #1C6EA4 100%); border-bottom: 2px solid #444444; }
table.blueTable thead th { font-size: 15px; font-weight: bold; olor: #FFFFFF; order-left: 2px solid #D0E4F5; }
table.blueTable thead th:first-child { border-left: none; }
table.blueTable tfoot { font-size: 14px; font-weight: bold; color: #FFFFFF; background: #D0E4F5; background: -moz-linear-gradient(top, #dcebf7 0%, #d4e6f6 66%, #D0E4F5 100%);
background: -webkit-linear-gradient(top, #dcebf7 0%, #d4e6f6 66%, #D0E4F5 100%); background: linear-gradient(to bottom, #dcebf7 0%, #d4e6f6 66%, #D0E4F5 100%); border-top: 2px solid #444444; }
table.blueTable tfoot td { font-size: 14px; }
table.blueTable tfoot .links { ext-align: right; }
table.blueTable tfoot .links a { display: inline-block; background: #1C6EA4; }
select { appearance: none; background-color: #E8E8E8; border: 1px solid; border-radius: 8px; padding: 2 8 2 8; margin: 0; background: #bbd0f2; }
input[type="date"] { background: #bbd0f2; border-radius: 8px; padding: 2 8 2 8;}
.link_bt:link, .link_bt:visited {-webkit-border-radius: 20px;-moz-border-radius: 20px;border-radius: 20px;background-color: #a5b9d9;color: black;border: 2px solid #1C6EA4;padding: 5px 20px;
text-align: center;text-decoration: none;display: inline-block;}
.link_bt:hover, .link_bt:active { background-color: #051733; color: white; }

</style>
</head>';
//if( isset($_SERVER['HTTP_SEC_FETCH_DEST']) && $_SERVER['HTTP_SEC_FETCH_DEST'] == 'iframe' )
if( 1 == 1 )
	{
	echo'<center><table class="blueTable">
	<thead>
	<tr>
		<th colspan="5" style="border: 0px">
		<a href="index.php" class="link_bt">Cdrs</a> 
		<a href="impusers.php" class="link_bt">Phones-Users</a> 
 		<a href="bill.php" class="link_bt">Billing</a> 
		<a href="imprates.php" class="link_bt">Rates</a> 
		<a href="conf.php" class="link_bt">Configuration</a>
		
		<a href="print.php" class="link_bt">One Week Billing</a>

		</th>
		<th style="border: 0px" align="center">
 		<img src="ico\ekium.svg" alt="" border=0 height=30 width=130>
		</th>

	</tr>
	</thead>
	</table></center>';
	}
else
	{
	echo'<center>To use this application, please login to <a href="https://'.$_SERVER['SERVER_NAME'].'">https://'.$_SERVER['SERVER_NAME'].'</a> and use the Cisco-CDR tab (billuser/billuser) or any admin login</center>';
	exit(0);
	}
?>
