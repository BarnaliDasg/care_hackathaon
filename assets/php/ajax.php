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

//search
if (isset($_POST['pincode'])) {
    $pincode = $_POST['pincode'];
    $users = getUsersByPincode($pincode); // Call function to get users

    if (!empty($users)) {
        echo "<h3>Users in Pincode: $pincode</h3><ul class='list-group'>";
        foreach ($users as $user) {
            echo "<li class='list-group-item d-flex align-items-center'>
                    <img src='assets/images/profile/" . $user['profile_pic'] . "' width='50' height='50' class='rounded-circle me-3'>
                    <div>
                        <strong>" . $user['fname'] . " " . $user['lname'] . "</strong><br>
                        <small>" . $user['email'] . " | " . $user['phone'] . "</small>
                    </div>
                  </li>";
        }
        echo "</ul>";
    } else {
        echo "<p class='text-danger'>No users found in this pincode.</p>";
    }
}


