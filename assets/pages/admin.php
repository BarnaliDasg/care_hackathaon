<?php
require_once __DIR__ . '/../php/configu.php';
require_once __DIR__ . '/../php/functions.php';
$selectedTable = $_GET['table'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<div class="container mt-3 d-flex justify-content-between align-items-center">
    <button class="btn" id="openSidebar">Menu</button>

    <a href="../../assets/php/actions.php?logout" class="btn" id="logout">Logout</a>
</div>


<div id="sidebar">
    <span class="close-btn" id="closeSidebar">&times;</span>
    <h4>Tables</h4>
    <ul class="list-unstyled">
        <?php
        $tables = getAllTableNames();
        foreach ($tables as $table) {
            $safeTable = htmlspecialchars($table, ENT_QUOTES, 'UTF-8');
            echo "<li><a href='?table=" . urlencode($safeTable) . "' class='nav-link'>📁 $safeTable</a></li>";
        }
        ?>
    </ul>
</div>

<div id="overlay"></div>

<div id="tableContentArea">
    <?php if ($selectedTable): ?>
        <?php
        $data = getTableData($selectedTable);
        if (!$data) {
            echo "<p>Error loading table data.</p>";
        } else {
            echo "<h3 style='margin-left: 300px'>📋 Table: <code>" . htmlspecialchars($selectedTable) . "</code></h3>";
            echo "<table class='table table-bordered'><thead><tr>";
            foreach ($data['columns'] as $col) {
                echo "<th>" . htmlspecialchars($col) . "</th>";
            }
            echo "<th>Actions</th></tr></thead><tbody>";
            foreach ($data['rows'] as $index => $row) {
                echo "<tr>";
                foreach ($row as $cell) {
                    echo "<td>" . htmlspecialchars($cell) . "</td>";
                }
                $assocRow = array_combine($data['columns'], $row);
                echo "<td>
                        <button class='btn btn-sm btn-edit me-2 edit-btn' data-row-index='$index'>Edit</button>
                        <button class='btn btn-sm btn-delete delete-btn' data-id='" . htmlspecialchars($row[0]) . "'>Delete</button>
                      </td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        }
        ?>
    <?php else: ?>
        <p style='margin-left: 300px'>Select a table from the sidebar to view its contents.</p>
    <?php endif; ?>
</div>

<div id="editModal" class="modal">
    <div class="modal-content">
        <span id="closeEditModal" class="close-btn">&times;</span>
        <h4>Edit Row</h4>
        <form id="editForm">
            <input type="hidden" id="editRowId" name="id">
            <div id="editFields"></div>
            <button type="submit" class="btn mt-2">Save Changes</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let selectedTableGlobal = "<?= $selectedTable ?>";
const rowDataGlobal = <?= json_encode($data['rows'] ?? []) ?>;
const columnDataGlobal = <?= json_encode($data['columns'] ?? []) ?>;

$(document).ready(function () {
    $('#openSidebar').click(() => $('#sidebar, #overlay').addClass('active'));
    $('#closeSidebar, #overlay').click(() => $('#sidebar, #overlay').removeClass('active'));
    $('#closeEditModal').click(() => $('#editModal').fadeOut());

    $(".edit-btn").click(function () {
        const index = $(this).data("row-index");
        const row = rowDataGlobal[index];
        const columns = columnDataGlobal;

        $("#editFields").html("");
        $("#editRowId").val(row[0]);
        for (let i = 0; i < columns.length; i++) {
            if (columns[i].toLowerCase() === "id") continue;
            const html = `
                <div class='mb-2'>
                    <label>${columns[i]}</label>
                    <input type='text' class='form-control edit-input' name='${columns[i]}' value='${row[i]}'>
                </div>`;
            $("#editFields").append(html);
        }
        $("#editModal").fadeIn();
    });

    $(".delete-btn").click(function () {
        const id = $(this).data("id");
        if (confirm("Are you sure you want to delete ID: " + id + "?")) {
            $.ajax({
                url: "../../assets/php/actions.php",
                type: "POST",
                data: {
                    action: "delete_row",
                    table: selectedTableGlobal,
                    id: id
                },
                dataType: "json",
                success: function (response) {
                    if (response.status === "success") {
                        alert("Row deleted!");
                        location.reload();
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function () {
                    alert("AJAX error during delete.");
                }
            });
        }
    });

    $("#editForm").submit(function (e) {
        e.preventDefault();
        const updates = {};
        const rowId = $("#editRowId").val();

        $(this).serializeArray().forEach(field => {
            if (field.name !== "id") updates[field.name] = field.value;
        });

        $.ajax({
            url: "../../assets/php/actions.php",
            type: "POST",
            data: {
                action: "edit_row",
                table: selectedTableGlobal,
                id: rowId,
                updates: JSON.stringify(updates)
            },
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    alert("Row updated!");
                    $("#editModal").fadeOut();
                    location.reload();
                } else {
                    alert("Update failed: " + response.message);
                }
            },
            error: function () {
                alert("AJAX error during edit.");
            }
        });
    });
});
</script>
</body>
</html>
