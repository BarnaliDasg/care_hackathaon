<?php
// Include database connection
include 'assets/php/configu.php';

// Get database connection
$conn = getDbConnection(); // Call function to get connection

if (!$conn) {
    die("Database connection error.");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .action-btn { padding: 5px 10px; text-decoration: none; margin-right: 5px; }
        .edit-btn { background-color: rgb(76, 86, 175); color: white; }
        .delete-btn { background-color: #f44336; color: white; }
    </style>
</head>
<body>

<h2>Admin Dashboard - Care Database</h2>

<?php
$tables = ["comments", "follow_list", "likes", "posts", "user"];

foreach ($tables as $table) {
    echo "<h3>Table: $table</h3>";

    $sql = "SELECT * FROM `$table`";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        echo "<table><tr>";

        // Fetch column names dynamically
        while ($fieldInfo = $result->fetch_field()) {
            echo "<th>{$fieldInfo->name}</th>";
        }
        echo "<th>Actions</th>"; // Add Actions column
        echo "</tr>";

        // Fetch rows
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $key => $value) {
                echo "<td>$value</td>";
            }

            // Fetch Primary Key ID
            $id = isset($row['id']) ? $row['id'] : reset($row);

            // Edit & Delete buttons
            echo "<td>
                <a href='assets/pages/update.php?id=$id&table=$table' class='action-btn edit-btn'>Edit</a>
                <a href='assets/pages/delete.php?id=$id&table=$table' class='action-btn delete-btn' onclick='return confirm(\"Are you sure?\")'>Delete</a>
            </td>";

            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No records found in <strong>$table</strong></p>";
    }
}

$conn->close();
?>

</body>
</html>
