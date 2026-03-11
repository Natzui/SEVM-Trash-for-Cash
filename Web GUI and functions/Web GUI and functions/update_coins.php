<?php
session_start();
$conn = new mysqli("localhost", "root", "", "cash_for_trash");

if(!isset($_SESSION['user'])){
    exit("Unauthorized");
}

$newAmount = intval($_POST['coins']);

if($newAmount < 0){
    exit("Invalid amount");
}

$stmt = $conn->prepare("
    UPDATE machine_settings 
    SET coins_remaining = ? 
    WHERE id = 1
");

$stmt->bind_param("i", $newAmount);
$stmt->execute();

echo "Updated";

$conn->close();
?>