<?php
include "assets/php/configu.php"; // Database connection

if (isset($_GET["post_id"])) {
    $post_id = intval($_GET["post_id"]);

    $stmt = $conn->prepare("SELECT content, created_at FROM comments WHERE post_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }

    echo json_encode(["status" => "success", "comments" => $comments]);

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Missing post_id"]);
}
?>
