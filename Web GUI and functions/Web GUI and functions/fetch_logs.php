<?php
$conn = new mysqli("localhost", "root", "", "cash_for_trash");

$result = $conn->query("
    SELECT id, coin_dispensed, created_at 
    FROM trash_logs 
    ORDER BY id DESC
");

$logs = array();

while($row = $result->fetch_assoc()) {
    $logs[] = $row;
}

echo json_encode($logs);

$conn->close();
?>