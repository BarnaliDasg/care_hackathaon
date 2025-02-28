<?php
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
    exit;
}

// Debugging: Check if POST data is received
echo json_encode([
    "status" => "success",
    "message" => "POST method received",
    "received_data" => $_POST
]);
exit;
