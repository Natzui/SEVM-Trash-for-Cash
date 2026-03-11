<?php
$conn = new mysqli("localhost","root","","cash_for_trash");
$today = date('Y-m-d');

$res = $conn->query("SELECT counter FROM daily_counters WHERE day='$today'");
$row = $res->fetch_assoc();
echo json_encode(["counter"=> $row['counter'] ?? 0]);
$conn->close();
?>