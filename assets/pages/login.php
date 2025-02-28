<div style="display: flex; justify-content: center; align-items: center; height: 100vh;">
    <form method="post" action="assets/php/actions.php?login" style=" background: white; width: 70%; max-width: 600px; box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2); border-radius: 10px; padding: 20px;">
        <div class="text-center mb-3">
        <img src="assets/images/abc.jpg" alt="Logo" style="height:60;">
        <h3 class="mt-2">Login</h3>
        </div>

        <!-- Username or Email Input -->
        <div class="form-floating mb-3">
            <input type="text" name="uname_email" class="form-control rounded-0" id="uname" value="<?= showFormData('uname_email') ?>" placeholder="Email address/User Name">
            <label for="uname">Email address/User Name</label>
            <?= showError('uname_email') ?>
        </div>

        <!-- Password Input -->
        <div class="form-floating mb-3">
            <input type="password" name="password" class="form-control rounded-0" id="password" placeholder="Password">
            <label for="password">Password</label>
            <?= showError('password') ?>
        </div>
        
        <!-- Display additional errors if any -->
        <?= showError('checkUser') ?>

        <!-- Login Button -->
        <button style="width: 100%; background-color: #e7c1fd; color: rgb(15, 43, 127);" type="submit" class="btn btn-primary mt-4">Login</button>

        <!-- Links in Single Line -->
        <div class="text-center mt-3">
            <a href="?signup" class="text-decoration-none me-3">Create New Account</a> |
            <a href="?forgotpassword&newfp" class="text-decoration-none ms-3">Forgot password?</a>
        </div>
    </form>
</div>
