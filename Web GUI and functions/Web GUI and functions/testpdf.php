<?php
require_once('tcpdf/tcpdf.php');

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->Write(0, 'Hello World PDF Test');
$pdf->Output('test.pdf', 'D');
?>