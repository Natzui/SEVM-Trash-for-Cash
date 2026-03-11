<?php
$conn = new mysqli("localhost", "root", "", "cash_for_trash");

$event = $_POST['event'];

if($event == "trash"){
    $conn->query("INSERT INTO trash_logs (coin_dispensed) VALUES (0)");
}

if($event == "coin"){
    // Deduct from machine storage
    $conn->query("UPDATE machine_settings 
                  SET coins_remaining = 
                      CASE
                          WHEN coins_remaining > 0
                          THEN coins_remaining -1
                          ELSE 0
                      END
                  WHERE id = 1");

    $conn->query("INSERT INTO trash_logs (coin_dispensed) VALUES (1)");
}

$conn->close();
?>