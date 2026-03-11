<?php
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

header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=Weekly_Report_$start.txt");

echo "Cash for Trash - Weekly Report\n";
echo "Week: $start to $end\n\n";

$totalTrash = 0;
$totalCoins = 0;

while($row = $result->fetch_assoc()){
    echo "Date: " . $row['day'] . "\n";
    echo "Trash Collected: " . $row['total_trash'] . "\n";
    echo "Coins Dispensed: " . $row['total_coins'] . "\n\n";

    $totalTrash += $row['total_trash'];
    $totalCoins += $row['total_coins'];
}

echo "----------------------------\n";
echo "TOTAL TRASH: $totalTrash\n";
echo "TOTAL COINS: $totalCoins\n";

$conn->close();
?>