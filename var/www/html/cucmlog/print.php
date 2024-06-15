<?php
ob_start();
//$cabineID = $_GET['cabineID']; $startDate = $_GET['startDate']; $endDate = $_GET['endDate']; $sipnummer = $_GET['cabineID'];
//$Mo = date("Y-m",$startDate);
include(dirname(__FILE__).'/pdfbill.php');
$content = ob_get_clean();
require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
	try
		{
		$html2pdf = new HTML2PDF('P','A4', 'fr', false, 'ISO-8859-15');
		$html2pdf->pdf->SetDisplayMode('fullpage');
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		$html2pdf->Output(''.$Mo.'_'.toto.'.pdf');
		}
catch(HTML2PDF_exception $e) { echo $e; }
?>
