<?php
include 'configu.php'; // Ensure database connection is included
header('Content-Type: application/json');

if (isset($_POST['pincode']) && !empty($_POST['pincode'])) {
    $pincode = $_POST['pincode'];
    $users = getUsersByPincode($pincode);

    if (empty($users)) {
        echo json_encode(["status" => "error", "message" => "No active caregiver"]);
    } else {
        echo json_encode(["status" => "success", "users" => $users]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Please enter a valid pincode"]);
}
?>
