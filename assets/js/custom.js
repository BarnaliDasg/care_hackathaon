// Preview post image
const input = document.querySelector("#select_post_img");
if (input) {
  input.addEventListener("change", function () {
    const fileobject = this.files[0];
    const filereader = new FileReader();
    filereader.readAsDataURL(fileobject);
    filereader.onload = function () {
      const img_src = filereader.result;
      const img = document.querySelector("#post_img");
      img.setAttribute("src", img_src);
      img.setAttribute("style", "display:");
    };
  });
}

$(document).ready(function () {
  const BASE = "/care/assets/php/ajax.php";

  // Follow user
  $(document).on("click", ".followbtn", function () {
    const button = $(this);
    const u_id_v = button.data("user-id");
    button.prop("disabled", true);

    $.ajax({
      url: BASE + "?follow",
      method: "POST",
      dataType: "json",
      data: { u_id: u_id_v },
      success(response) {
        if (response.status) {
          button.html('<i class="fas fa-check-circle text-white"></i> Following')
            .removeClass("btn-primary")
            .addClass("bg-primary text-white px-3 py-1 rounded border-0");
        } else {
          button.prop("disabled", false);
          alert("Something went wrong, please try again later.");
        }
      },
      error() {
        button.prop("disabled", false);
        alert("AJAX error — follow.");
      },
    });
  });

  // Unfollow user
  $(document).on("click", ".unfollowbtn", function () {
    const button = $(this);
    const u_id_v = button.data("user-id");
    button.prop("disabled", true);

    $.ajax({
      url: BASE + "?unfollow",
      method: "POST",
      dataType: "json",
      data: { u_id: u_id_v },
      success(response) {
        if (response.status) {
          button.html('<i class="fas fa-check-circle text-white"></i> Unfollowed')
            .removeClass("btn-primary")
            .addClass("bg-danger text-white px-3 py-1 rounded border-0");
        } else {
          button.prop("disabled", false);
          alert("Something went wrong, please try again later.");
        }
      },
      error() {
        button.prop("disabled", false);
        alert("AJAX error — unfollow.");
      },
    });
  });

  // Like post
  $(document).on("click", ".like_btn", function () {
    const button = $(this);
    const post_id_v = button.data("post-id");
    button.prop("disabled", true);

    $.ajax({
      url: BASE + "?like",
      method: "POST",
      dataType: "json",
      data: { post_id: post_id_v },
      success(response) {
        if (response.status) {
          button.hide();
          button.siblings(".unlike_btn").show();
        } else {
          button.prop("disabled", false);
          alert("Something went wrong — like failed.");
        }
      },
      error() {
        button.prop("disabled", false);
        alert("AJAX error — like.");
      },
    });
  });

  // Unlike post
  $(document).on("click", ".unlike_btn", function () {
    const button = $(this);
    const post_id_v = button.data("post-id");
    button.prop("disabled", true);

    $.ajax({
      url: BASE + "?unlike",
      method: "POST",
      dataType: "json",
      data: { post_id: post_id_v },
      success(response) {
        if (response.status) {
          button.hide();
          button.siblings(".like_btn").show();
        } else {
          button.prop("disabled", false);
          alert("Something went wrong — unlike failed.");
        }
      },
      error() {
        button.prop("disabled", false);
        alert("AJAX error — unlike.");
      },
    });
  });

  // Comment form
  $(document).on("submit", "#commentForm", function (e) {
    e.preventDefault();
    const $form = $(this);
    const post_id = $form.data("post-id");
    const comment_text = $form.find("textarea").val().trim();

    if (!comment_text) {
      alert("Comment cannot be empty!");
      return;
    }

    $.ajax({
      url: BASE + "?comment",
      method: "POST",
      dataType: "json",
      data: { post_id, comment_text },
      success(response) {
        if (response.status === "success") {
          location.reload();
        } else {
          alert(response.message || "Comment failed.");
        }
      },
      error() {
        alert("AJAX error — comment.");
      },
    });
  });

  // Pincode user search
  $(document).on("submit", "#pincodeSearchForm", function (e) {
    e.preventDefault();
    const pincode = $("#pincodeInput").val().trim();

    if (!pincode) {
      $("#searchResults").html('<p class="text-danger">Please enter a pincode.</p>');
      return;
    }

    $.ajax({
      url: BASE + "?searchPincode",
      method: "POST",
      dataType: "json",
      data: { pincode },
      success(response) {
        if (response.status) {
          const usersHTML = response.users
            .map(
              (user) => `
            <div class="d-flex justify-content-between p-2 align-items-center">
              <a href="?u=${user.uname}" class="d-flex align-items-center text-decoration-none text-dark">
                <img src="assets/images/profile/${user.profile_pic}" height="40" width="40" class="rounded-circle border">
                <div class="ms-2">
                  <h6 class="mb-0">${user.fname} ${user.lname}</h6>
                  <p class="mb-0 text-muted">@${user.uname}</p>
                </div>
              </a>
              <span class="badge bg-secondary">${user.role}</span>
            </div>
          `
            )
            .join("");
          $("#searchResults").html(usersHTML);
        } else {
          $("#searchResults").html("<p class='text-muted'>No users found for this pincode.</p>");
        }

        $("#userSearchModal").modal("show");
      },
      error() {
        $("#searchResults").html('<p class="text-danger">AJAX error — search.</p>');
      },
    });
  });

  // Handle message sending
  $(document).on("submit", "#messageForm", function (e) {
    e.preventDefault();
    const receiver_id = $(this).data("receiver-id");
    const message_text = $(this).find("textarea").val().trim();

    if (!message_text) {
      alert("Message cannot be empty!");
      return;
    }

    $.ajax({
      url: "ajax.php",
      method: "POST",
      dataType: "json",
      data: { receiver_id, message_text },
      success(response) {
        if (response.status === "success") {
          alert("Message sent successfully!");
          location.reload();
        } else {
          alert(response.message);
        }
      },
      error(xhr, status, error) {
        console.error("AJAX Error:", status, error);
      },
    });
  });

  // Handle sidebar/table links (with .table-link class only)
  $(document).on("click", ".nav-link.table-link", function (e) {
    e.preventDefault();
    const tableName = $(this).text().trim().replace("📁", "").trim();
    if (!tableName) {
      console.error("Table name is empty after extraction.");
      $("#tableContentArea").html("<p>Error: Table name is empty.</p>");
      return;
    }

    function loadTableData(tableName) {
      $.ajax({
        url: "/care/assets/php/actions.php",
        type: "POST",
        data: {
          action: "get_table_data",
          table: tableName,
        },
        dataType: "json",
        success(response) {
          if (response.status === "success") {
            if (!response.columns || !response.rows) {
              $("#tableContentArea").html("<p>Error: Invalid data format received.</p>");
              return;
            }

            let html = `<h3>📋 Table: <code>${tableName}</code></h3>`;
            html += "<table border='1' cellpadding='5'><thead><tr>";

            response.columns.forEach((col) => {
              html += `<th>${col}</th>`;
            });

            html += "<th>Actions</th></tr></thead><tbody>";

            response.rows.forEach((row, index) => {
              html += "<tr>";
              row.forEach((cell) => {
                html += `<td>${cell}</td>`;
              });

              const id = row[0];
              html += `<td>
                        <button class='edit-btn' data-row-index='${index}'>Edit</button>
                        <button class='delete-btn' data-id='${id}'>Delete</button>
                       </td></tr>`;
            });

            html += "</tbody></table>";
            $("#tableContentArea").html(html);

            $(".edit-btn").on("click", function () {
              const rowIndex = $(this).data("row-index");
              const rowData = response.rows[rowIndex];
              const rowId = rowData[0];
              handleEdit(tableName, rowId, response.columns, rowData);
            });

            $(".delete-btn").on("click", function () {
              const id = $(this).data("id");
              handleDelete(tableName, id);
            });
          } else {
            $("#tableContentArea").html(`<p>Error: ${response.message}</p>`);
          }
        },
        error(xhr, status, error) {
          console.error("AJAX Error:", status, error);
          $("#tableContentArea").html("<p>Error loading table data.</p>");
        },
      });
    }

    loadTableData(tableName);
  });

  // Close sidebar
  $(document).on("click", "#closeSidebar, #overlay", function () {
    $("#sidebar").removeClass("active");
    $("#overlay").fadeOut();
  });

  // Load Add Product Form
  $(document).on("click", "a[data-page='add_product']", function (e) {
    e.preventDefault();
    const container = $("#tableContentArea");
    const link = $(this);
    link.prop("disabled", true);
    container.html("<p>Loading Add Product form...</p>");

    $.ajax({
      url: "assets/pages/add_product.php",
      method: "GET",
      success(response) {
        container.html(response);
        link.prop("disabled", false);
      },
      error() {
        container.html("<p>Error loading form. Please try again later.</p>");
        link.prop("disabled", false);
      },
    });
  });

  // Edit Modal Submit
  $("#editForm").on("submit", function (e) {
    e.preventDefault();
    if (!selectedTableGlobal) {
      alert("No table selected to update!");
      return;
    }

    const rowId = $("#editRowId").val();
    const formDataArray = $(this).serializeArray();
    const updates = {};
    formDataArray.forEach((field) => {
      if (field.name !== "id") {
        updates[field.name] = field.value;
      }
    });

    if (!rowId || Object.keys(updates).length === 0) {
      alert("Row ID or data missing!");
      return;
    }

    $.ajax({
      url: "/care/assets/php/actions.php",
      type: "POST",
      data: {
        action: "edit_row",
        table: selectedTableGlobal,
        id: rowId,
        updates: JSON.stringify(updates),
      },
      dataType: "json",
      success(response) {
        if (response.status === "success") {
          alert("Row updated successfully!");
          $("#editModal").fadeOut();
          loadTableData(selectedTableGlobal);
        } else {
          alert("Error updating row: " + response.message);
        }
      },
      error(xhr) {
        console.error("Edit AJAX Error:", xhr.responseText);
        alert("AJAX error during edit");
      },
    });
  });
});

let selectedTableGlobal = "";
function handleEdit(tableName, rowId, columns, rowData) {
  selectedTableGlobal = tableName;
  $("#editFields").empty();
  $("#editRowId").val(rowId);

  for (let i = 0; i < columns.length; i++) {
    if (columns[i].toLowerCase() === "id") continue;

    const fieldHtml = `
      <div class="form-group mb-2">
        <label>${columns[i]}</label>
        <input type="text" class="form-control" name="${columns[i]}" value="${rowData[i] || ''}">
      </div>
    `;
    $("#editFields").append(fieldHtml);
  }

  $("#editModal").fadeIn();
}

function handleDelete(tableName, id) {
  if (confirm("Are you sure you want to delete this row?")) {
    $.ajax({
      url: "/care/assets/php/actions.php",
      type: "POST",
      data: {
        action: "delete_row",
        table: tableName,
        id: id,
      },
      dataType: "json",
      success(response) {
        if (response.status === "success") {
          alert("Row deleted successfully!");
          loadTableData(tableName);
        } else {
          alert("Error deleting row: " + response.message);
        }
      },
      error(xhr, status, error) {
        console.error("AJAX Error (delete):", status, error);
        alert("AJAX Error (delete): Check console");
      },
    });
  }
}
