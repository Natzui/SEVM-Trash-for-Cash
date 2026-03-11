<?php
$conn = new mysqli("localhost", "root", "", "cash_for_trash");

$result = $conn->query("
    SELECT 
        DATE(created_at) as day,
        COUNT(*) as total_trash,
        SUM(coin_dispensed) as total_coins
    FROM trash_logs
    GROUP BY DATE(created_at)
    ORDER BY day DESC
    LIMIT 7
");

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);

$conn->close();
?>