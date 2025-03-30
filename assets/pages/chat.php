<?php
session_start();

if (!isset($_SESSION['Auth']) || $_SESSION['Auth'] != 1 || !isset($_SESSION['userdata']['id'])) {
    echo "Please log in first.";
    exit();
}

// Store logged-in user ID in a variable
$user_id = $_SESSION['userdata']['id'];

// Check if receiver_id exists
if (!isset($_GET['receiver_id']) || empty($_GET['receiver_id'])) {
    echo "Receiver ID is missing!";
    exit();
}

$receiver_id = $_GET['receiver_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Private Chat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/custom.js"></script>
    <style>
        .chat-box {
            height: 400px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .msg {
            padding: 8px 12px;
            margin: 5px;
            border-radius: 10px;
            max-width: 70%;
        }
        .sent {
            background-color: #007bff;
            color: white;
            align-self: flex-end;
        }
        .received {
            background-color: #f1f1f1;
            color: black;
            align-self: flex-start;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h3 class="text-center">Private Chat</h3>
        <div class="chat-box d-flex flex-column" id="chat-box"></div>
        
        <form id="chat-form" class="mt-3">
            <input type="hidden" name="receiver_id" id="receiver_id" value="<?= $receiver_id ?>">
            <div class="d-flex">
                <textarea id="message" class="form-control me-2" name="message" rows="1" placeholder="Type a message..." required></textarea>
                <button type="submit" class="btn btn-primary">Send</button>
            </div>
        </form>
    </div>
</body>
</html>
