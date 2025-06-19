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
  <link rel="stylesheet" href="assets/css/custom.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    #sidebar {
      width: 250px;
      background-color: #333;
      color: #fff;
      position: fixed;
      top: 0;
      left: -260px;
      height: 100%;
      overflow-y: auto;
      transition: left 0.3s;
      padding: 20px;
      z-index: 1000;
    }

    #sidebar.active {
      left: 0;
    }

    #overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 999;
    }

    #overlay.active {
      display: block;
    }

    .close-btn {
      font-size: 24px;
      cursor: pointer;
      color: white;
      position: absolute;
      top: 10px;
      right: 15px;
    }

    body {
    background: linear-gradient(to right, #ffd1d1, #d5dcf9);
}

button, .btn {
    background: linear-gradient(135deg, #e096f9, #7bffdeb5); /* Warm peach to soft aqua */
    border: 1px solid rgba(0, 0, 0, 0.508);
    color: #3D3D3D; /* Soft charcoal for better contrast */
    padding: 8px 15px;
    font-size: 12px;
    font-weight: bold;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }
  
  button:hover, .btn:hover {
    background: linear-gradient(135deg, #bb82cb, #4bbda4); /* Slightly richer pastel shades */
    transform: translateY(-2px);
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
  }
  
  button:active, .btn:active {
    transform: translateY(1px);
    box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1);
  }
  </style>
</head>
<body>

<!-- Open Sidebar Button -->
<div class="container mt-3">
  <button class="btn btn-danger" id="openSidebar">Menu</button>
</div>

<!-- Sidebar -->
<div id="sidebar">
  <span class="close-btn" id="closeSidebar">&times;</span>
  <h4>Tables</h4>
  <ul style="list-style: none; padding-left: 0;">
    <?php
    $tables = getAllTableNames();
    foreach ($tables as $table) {
        $safeTable = htmlspecialchars($table, ENT_QUOTES, 'UTF-8');
        echo "<li class='nav-item'>
                <a href='?table=" . urlencode($safeTable) . "' class='nav-link text-warning'>📁 $safeTable</a>
              </li>";
    }
    ?>
  </ul>

  <!-- <h4>Add Contents</h4>
  <div class="mt-4 text-center">
    <ul>
      <li><a href="#" class="ajax-link text-warning" data-page="add_product">➕ Add Product</a></li>
      <li><a href="#" class="ajax-link text-warning" data-page="add_slide">🖼️ Add Slide</a></li>
    </ul>
  </div> -->
</div>

<!-- Overlay -->
<div id="overlay"></div>

<!-- Main Content -->
<div id="tableContentArea" style="margin-left: 260px; padding: 20px;">
  <?php if ($selectedTable): ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3>📋 Table: <code><?= htmlspecialchars($selectedTable) ?></code></h3>
    </div>

    <?php
    $data = getTableData($selectedTable);
    if (!$data) {
        echo "<p>Error loading table data.</p>";
    } else {
        echo "<table class='table table-bordered'>";
        echo "<thead><tr>";
        foreach ($data['columns'] as $col) {
            echo "<th>" . htmlspecialchars($col) . "</th>";
        }
        echo "</tr></thead><tbody>";

        foreach ($data['rows'] as $row) {
            echo "<tr>";
            foreach ($row as $cell) {
                echo "<td>" . htmlspecialchars($cell) . "</td>";
            }
            echo "</tr>";
        }
        echo "</tbody></table>";
    }
    ?>
  <?php else: ?>
    <p>Select a table from the sidebar to view its contents.</p>
  <?php endif; ?>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9999; overflow:auto;">
  <div class="modal-content" style="background:#fff; margin:5% auto; padding:20px; width:90%; max-width:600px; max-height:90vh; overflow-y:auto; position:relative; border-radius:8px; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
    <span id="closeEditModal" style="position:absolute; top:10px; right:15px; font-size:20px; cursor:pointer;">&times;</span>
    <h4 style="margin-top: 0;">Edit Row</h4>
    <form id="editForm">
      <div id="editFields"></div>
      <input type="hidden" name="id" id="editRowId">
      <button type="submit" class="btn btn-primary mt-2">Save Changes</button>
    </form>
  </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(function () {
    $('#openSidebar').click(function () {
      $('#sidebar').addClass('active');
      $('#overlay').addClass('active');
    });

    $('#closeSidebar, #overlay').click(function () {
      $('#sidebar').removeClass('active');
      $('#overlay').removeClass('active');
    });
  });
</script>

