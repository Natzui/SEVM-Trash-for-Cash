<?php
$conn = new mysqli("localhost", "root", "", "cash_for_trash");

$date = $_GET['date'];

$result = $conn->query("
    SELECT 
        id,
        created_at,
        TIME(created_at) as time_collected,
        coin_dispensed
    FROM trash_logs
    WHERE DATE(created_at) = '$date'
    ORDER BY created_at DESC
");

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);

$conn->close();
?>