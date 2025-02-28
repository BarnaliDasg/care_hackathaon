<?php

// Determine the action based on session variable
if (isset($_SESSION['forgot_code']) && !isset($_SESSION['auth_temp'])) {
    $action = 'verify';
} elseif (isset($_SESSION['forgot_code']) && isset($_SESSION['auth_temp'])) {
    $action = 'changepassword';
} else {
    $action = 'forgotpassword';
}
?>

<div class="login d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div style="width: 70%; max-width: 600px; box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2); border: 1px solid #ddd; border-radius: 10px;" class="col-4 bg-white border rounded p-4">
        <form method="post" action="assets/php/actions.php?<?=$action?>">
            <h1 class="h5 mb-3 fw-normal text-center">Forgot Your Password?</h1>
            
            <?php 
            if ($action == 'forgotpassword') { 
            ?>
                <!-- Email input for forgot password -->
                <div class="form-floating mb-3">
                    <input type="email" name="email" class="form-control rounded-0" id="floatingInput" placeholder="username/email" required>
                    <label for="floatingInput">Enter your email</label>
                </div>
                <?=showError('email')?>
                <button style="width: 100%;background-color: #e7c1fd; color: rgb(15, 43, 127);" class="btn btn-primary" type="submit">Send Verification Code</button>
            <?php 
            } 
            ?>

            <?php 
            if ($action == 'verify') { 
            ?>
                <!-- Verification code input -->
                <p class="text-center">Enter the 6-digit code sent to <?=$_SESSION['forgot_email']?></p>
                <div class="form-floating mb-3">
                    <input type="text" name="verification_code" class="form-control rounded-0" id="floatingCode" placeholder="######" maxlength="6" required>
                    <label for="floatingCode">######</label>
                </div>
                <?=showError('email_verify')?>

                <p class="text-center text-danger">Your verification code: <strong><?= htmlspecialchars($_SESSION['forgot_code']) ?></strong></p>


                <button style="width: 100%;background-color: #e7c1fd; color: rgb(15, 43, 127);" class="btn btn-primary" type="submit">Verify Code</button>
            <?php
            } 
            ?>

            <?php 
            if ($action == 'changepassword') { 
            ?>
                <!-- New password input for resetting the password -->
                <p class="text-center">Set a new password for <?=$_SESSION['forgot_email']?></p>
                <div class="form-floating mb-3">
                    <input type="password" name="password" class="form-control rounded-0" id="floatingNewPassword" placeholder="New Password" required>
                    <label for="floatingNewPassword">New Password</label>
                </div>
                <?=showError('password')?>
                <button style="width: 100%;background-color: #e7c1fd; color: rgb(15, 43, 127);" class="btn btn-primary" type="submit">Change Password</button>
            <?php
            } 
            ?>
            
            <br>
            <a href="?login" class="text-decoration-none mt-3 d-block text-center"><i class="bi bi-arrow-left-circle-fill"></i> Go Back To Login</a>
        </form>
    </div>
</div>
