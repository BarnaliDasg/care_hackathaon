<?php
// Include configuration and functions
require_once 'configu.php'; // Ensure this file sets up the database connection
require_once 'functions.php';

// Create a global database connection
$db = getDbConnection();
if (!$db) {
    die(json_encode(["status" => "error", "message" => "Database connection failed."]));
}

// Follow User
if (isset($_GET['follow'])) {
    $u_id = $_POST['u_id'];
    $response = ['status' => false]; // Default response

    if (followUser($u_id)) {
        $response['status'] = true;
    }

    echo json_encode($response);
}

// Unfollow User
if (isset($_GET['unfollow'])) {
    $u_id = $_POST['u_id'];
    $response = ['status' => false]; // Default response

    if (unfollowUser($u_id)) {
        $response['status'] = true;
    }

    echo json_encode($response);
}

// Like Post
if (isset($_GET['like'])) {
    $post_id = $_POST['post_id'];
    $response = ['status' => false]; // Default response

    if (!checklikeStatus($post_id)) {
        if (like($post_id)) {
            $response['status'] = true;
        }
    }

    echo json_encode($response);
}

// Unlike Post
if (isset($_GET['unlike'])) {
    $post_id = $_POST['post_id'];
    $response = ['status' => false]; // Default response

    if (checklikeStatus($post_id)) {
        if (unlike($post_id)) {
            $response['status'] = true;
        }
    }

    echo json_encode($response);
}

// ADD COMMENT

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_id'], $_POST['comment_text'])) {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'] ?? null;
    $comment_text = trim($_POST['comment_text']);

    // 🔍 Debugging - Log received data
    file_put_contents("debug_log.txt", "Received: post_id=$post_id, user_id=$user_id, comment_text=$comment_text\n", FILE_APPEND);

    if (!$user_id) {
        echo json_encode(["status" => "error", "message" => "User not logged in."]);
        exit;
    }

    if (empty($comment_text)) {
        echo json_encode(["status" => "error", "message" => "Comment cannot be empty."]);
        exit;
    }

    // 🔍 Debugging - Check SQL Query
    $stmt = $db->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)");
    if (!$stmt) {
        file_put_contents("debug_log.txt", "SQL Prepare Failed: " . $db->error . "\n", FILE_APPEND);
        echo json_encode(["status" => "error", "message" => "SQL Prepare Failed"]);
        exit;
    }

    $stmt->bind_param("iis", $post_id, $user_id, $comment_text);
    if ($stmt->execute()) {
        file_put_contents("debug_log.txt", "Comment Inserted Successfully!\n", FILE_APPEND);
        echo json_encode(["status" => "success"]);
    } else {
        file_put_contents("debug_log.txt", "SQL Execute Failed: " . $stmt->error . "\n", FILE_APPEND);
        echo json_encode(["status" => "error", "message" => "Failed to add comment."]);
    }

    exit;
}

// Search Users by Pincode
if (isset($_GET['searchPincode'])) {
    require_once __DIR__ . '/../php/actions.php';

    $pincode = trim($_POST['pincode']);
    $response = ['status' => false, 'users' => []];

    if (!empty($pincode)) {
        $users = searchUsersByPincode($pincode); // Ensure this function is properly defined

        if (!empty($users)) {
            $response['status'] = true;
            $response['users'] = $users;
        }
    }

    echo json_encode($response);
    exit;
}

//message
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['receiver_id'], $_POST['message_text'])) {
    $receiver_id = $_POST['receiver_id'];
    $sender_id = $_SESSION['user_id'] ?? null;
    $message_text = trim($_POST['message_text']);

    // 🔍 Debugging - Log received data
    file_put_contents("debug_log.txt", "Received: receiver_id=$receiver_id, sender_id=$sender_id, message_text=$message_text\n", FILE_APPEND);

    if (!$sender_id) {
        echo json_encode(["status" => "error", "message" => "User not logged in."]);
        exit;
    }

    if (empty($message_text)) {
        echo json_encode(["status" => "error", "message" => "Message cannot be empty."]);
        exit;
    }

    // 🔍 Debugging - Check SQL Query
    $stmt = $db->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    if (!$stmt) {
        file_put_contents("debug_log.txt", "SQL Prepare Failed: " . $db->error . "\n", FILE_APPEND);
        echo json_encode(["status" => "error", "message" => "SQL Prepare Failed"]);
        exit;
    }

    $stmt->bind_param("iis", $sender_id, $receiver_id, $message_text);
    if ($stmt->execute()) {
        file_put_contents("debug_log.txt", "Message Inserted Successfully!\n", FILE_APPEND);
        echo json_encode(["status" => "success"]);
    } else {
        file_put_contents("debug_log.txt", "SQL Execute Failed: " . $stmt->error . "\n", FILE_APPEND);
        echo json_encode(["status" => "error", "message" => "Failed to send message."]);
    }

    exit;
}





