<?php
session_start();
require 'functions.php'; // Include functions

// Fetch post details (you can modify this based on your post structure)
$post_id = $_GET['post_id']; // Get the post ID from the URL
$post = getPost($post_id); // Assuming you have a getPost function
?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .comment {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Post Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Display Post Content -->
                <div class="post-content mb-3">
                    <p><?php echo $post['post_txt']; ?></p>
                    <?php if (!empty($post['post_img'])): ?>
                        <img src="<?php echo $post['post_img']; ?>" class="w-100 rounded border" alt="Post Image">
                    <?php endif; ?>
                </div>

                <!-- Display Comments -->
                <h6>Comments</h6>
                <div id="commentsSection">
                    <!-- Comments will be loaded here dynamically -->
                </div>

                <!-- Add Comment Form -->
                <form id="commentForm" class="mt-3">
                    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                    <div class="mb-3">
                        <label for="commentText" class="form-label">Add a Comment</label>
                        <textarea class="form-control" id="commentText" name="comment" rows="2" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Post Comment</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load comments when the page loads
        function loadComments() {
            const post_id = document.querySelector('input[name="post_id"]').value;

            fetch(`php/ajax.php?post_id=${post_id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const commentsSection = document.getElementById('commentsSection');
                        commentsSection.innerHTML = ''; // Clear existing comments

                        data.comments.forEach(comment => {
                            const commentDiv = document.createElement('div');
                            commentDiv.className = 'comment';
                            commentDiv.innerHTML = `
                                <img src="${comment.profile_pic}" alt="Profile Picture" style="width: 30px; height: 30px; border-radius: 50%;">
                                <strong>${comment.username}</strong>
                                <p>${comment.comment}</p>
                                <small>${new Date(comment.created_at).toLocaleString()}</small>
                                <hr>
                            `;
                            commentsSection.appendChild(commentDiv);
                        });
                    } else {
                        alert('Failed to load comments');
                    }
                });
        }

        // Add a comment
        document.getElementById('commentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('php/ajax.php', {
                method: 'POST',
                body: formData
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      alert('Comment added successfully');
                      loadComments(); // Refresh comments after adding
                      document.getElementById('commentText').value = ''; // Clear the textarea
                  } else {
                      alert('Failed to add comment');
                  }
              });
        });

        // Load comments when the page loads
        window.onload = loadComments;