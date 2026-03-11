<?php
$conn = new mysqli("localhost", "root", "", "cash_for_trash");

$result = $conn->query("SELECT * FROM machine_settings WHERE id = 1");
$row = $result->fetch_assoc();

echo json_encode($row);

$conn->close();
?>