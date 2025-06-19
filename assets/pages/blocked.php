<?php
global $user;
?>

<div class="login d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="col-4 bg-white border rounded p-4 shadow-sm" style="width: 70%; max-width: 600px; box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2); border-radius: 10px;">
        <form>
            <div class="text-center">
                <img class="mb-4" src="assets/images/cds.png" alt="Company Logo" height="45" >
            </div>
            <h1 class="h5 mb-3 fw-normal text-center">
                Hello, <?= htmlspecialchars($user['fname']) . ' ' . htmlspecialchars($user['lname']) ?> 
                <span class="d-block">(<?= htmlspecialchars($user['email']) ?>)</span>
                <br>Your account has been blocked by the admin.
            </h1>
            <div class="mt-3 text-center">
                <a href="assets/php/actions.php?logout" class="btn btn-danger">Logout</a>
            </div>
        </form>
    </div>
</div>
