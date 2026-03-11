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

$data = [];

while($row = $result->fetch_assoc()){
    $row['total_coins'] = $row['total_coins'] ?? 0;
    $data[] = $row;
}

echo json_encode($data);

$conn->close();
?>