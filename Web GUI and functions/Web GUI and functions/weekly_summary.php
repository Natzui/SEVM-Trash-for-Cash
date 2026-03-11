<?php
require_once('tcpdf/tcpdf.php');

$conn = new mysqli("localhost", "root", "", "cash_for_trash");

$start = $_GET['start'];
$end = date('Y-m-d', strtotime($start . ' +6 days'));

$result = $conn->query("
    SELECT 
        DATE(created_at) as day,
        COUNT(*) as total_trash,
        SUM(coin_dispensed) as total_coins
    FROM trash_logs
    WHERE DATE(created_at) BETWEEN '$start' AND '$end'
    GROUP BY DATE(created_at)
");

// Create PDF
$pdf = new TCPDF();
$pdf->AddPage();

$pdf->SetFont('helvetica', '', 12);

$pdf->Cell(0, 10, 'Cash for Trash - Weekly Report', 0, 1);
$pdf->Cell(0, 10, "Week: $start to $end", 0, 1);
$pdf->Ln(5);

$totalTrash = 0;
$totalCoins = 0;

while($row = $result->fetch_assoc()){

    $totalCoinsDay = $row['total_coins'] ?? 0;

    $pdf->Cell(0, 8, "Date: ".$row['day'], 0, 1);
    $pdf->Cell(0, 8, "Trash Collected: ".$row['total_trash'], 0, 1);
    $pdf->Cell(0, 8, "Coins Dispensed: ".$totalCoinsDay, 0, 1);
    $pdf->Ln(5);

    $totalTrash += $row['total_trash'];
    $totalCoins += $totalCoinsDay;
}

$pdf->Ln(5);
$pdf->Cell(0, 10, "TOTAL TRASH: $totalTrash", 0, 1);
$pdf->Cell(0, 10, "TOTAL COINS: $totalCoins", 0, 1);

$pdf->Output("Weekly_Report_$start.pdf", "D");

$conn->close();
?>