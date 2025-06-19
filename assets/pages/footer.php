<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Post</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <!-- Fixed for Bootstrap 4 -->
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="" style="display:none" id="post_img" class="w-100 rounded border">
                <form method="post" action="assets/php/actions.php?addpost" enctype="multipart/form-data">
                    <div class="my-3">
                        <input class="form-control" name="post_img" type="file" id="select_post_img">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Say Something</label>
                        <textarea class="form-control" name="post_text" id="exampleFormControlTextarea1" rows="1"></textarea>
                    </div>

                    <!-- Address -->
                    <div class="mb-3">
                        <label for="post_address" class="form-label">Address</label>
                        <input type="text" class="form-control" name="post_address" id="post_address" placeholder="Enter your address">
                    </div>

                    <!-- Pincode -->
                    <div class="mb-3">
                        <label for="post_pincode" class="form-label">Pincode</label>
                        <input type="text" class="form-control" name="post_pincode" id="post_pincode" placeholder="Enter your pincode">
                    </div>

                    <div class="d-flex justify-content-end"> <!-- Align buttons properly -->
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Cancel</button> <!-- Fixed for Bootstrap 4 -->
                        <button type="submit" class="btn btn-primary">Post</button>
                    </div>
                </form>
            </div>
        </div>
  </div>
</div>

<!-- jQuery (Required for Bootstrap 4) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Popper.js (Required for Bootstrap 4) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<!-- Bootstrap 4 JS -->
<script src="assets/bootstrap/js/bootstrap.min.js"></script>

<!-- Custom JS (if any) -->
<script src="assets/js/custom.js?v=<?=time()?>"></script>

</body>
</html>