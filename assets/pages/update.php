<?php
include '../php/configu.php';
$conn = getDbConnection();

if (!$conn) {
    die("Database connection failed.");
}

if (isset($_GET['id']) && isset($_GET['table'])) {
    $id = intval($_GET['id']);
    $table = $_GET['table'];

    // Validate Table Name
    $allowedTables = ["comments", "follow_list", "likes", "posts", "user"];
    if (!in_array($table, $allowedTables)) {
        echo "<script>alert('Invalid table name!'); window.location='admin.php';</script>";
        exit();
    }

    // Fetch the record
    $sql = "SELECT * FROM `$table` WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        echo "<script>alert('Record not found!'); window.location='admin.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid request!'); window.location='admin.php';</script>";
    exit();
}

// Handle update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $updateQuery = "UPDATE `$table` SET ";
    $params = [];
    $types = "";

    foreach ($_POST as $column => $value) {
        // Skip ID and timestamp columns
        if (!in_array($column, ['id', 'created_at', 'updated_at', 'timestamp'])) {
            $updateQuery .= "`$column` = ?, ";
            $params[] = $value;
            $types .= "s";
        }
    }

    $updateQuery = rtrim($updateQuery, ", ") . " WHERE id = ?";
    $params[] = $id;
    $types .= "i";

    if (!empty($params)) {
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            echo "<script>alert('Record updated successfully!'); window.location='admin.php';</script>";
        } else {
            echo "<script>alert('Error updating record: " . $stmt->error . "');</script>";
        }
    } else {
        echo "<script>alert('No valid fields to update!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f9f9f9; }
        form { background: white; padding: 20px; border-radius: 5px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); }
        label { font-weight: bold; margin-top: 10px; display: block; }
        input { padding: 8px; width: 100%; max-width: 400px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: 10px 15px; background: #4CAF50; color: white; border: none; cursor: pointer; border-radius: 4px; }
        button:hover { background: #45a049; }
    </style>
</head>
<body>

<h2>Edit Record in <?= htmlspecialchars($table) ?></h2>

<form method="POST">
    <?php foreach ($row as $column => $value): ?>
        <?php if (!in_array($column, ['id', 'created_at', 'updated_at','followed_at', 'timestamp','user_id','post_id'])): ?> <!-- Don't edit ID & timestamps -->
            <label><?= htmlspecialchars($column) ?>:</label>
            <input type="text" name="<?= htmlspecialchars($column) ?>" value="<?= htmlspecialchars($value) ?>" required>
        <?php endif; ?>
    <?php endforeach; ?>
    
    <button type="submit">Update</button>
</form>

</body>
</html>
