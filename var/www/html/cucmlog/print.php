<?php
ob_start();
//This page need html2pdf library version 4.01
//Not provided
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
