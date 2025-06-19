<div style="display: flex; justify-content: center; align-items: center; padding:40px 0;">
    <div style="width: 75%; background: white; max-width: 650px; box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.3); border-radius: 12px; padding: 30px;">
        <form method="post" action="assets/php/actions.php?signup" style="width: 100%;">
            <div style="text-align: center;">
            <img src="assets/images/abc.jpg" alt="Logo" style="height:60;"> 
                <h3>Create New Account</h3>
            </div>

            <!-- Role Dropdown -->
            <div class=class="d-flex gap-3 mt-2" >
            <select name="role" required style="width: 100%;">
                <option value="caregiver">Caregiver</option>
                <option value="careseeker">Careseeker</option>
            </select>

                <label for="role" class="form-label">Select Your Role</label>
            </div>

            <div class="d-flex gap-3 mt-2">
                <div class="form-floating flex-fill" style="width: 45%;">
                    <input type="text" name="fname" class="form-control rounded-3" id="fname" placeholder="First Name" value="<?= showFormData('fname') ?>">
                    <label for="fname" class="form-label">First Name</label>
                    <?= showError('fname') ?>
                </div>
                <div style="width: 10%;"></div>
                <div class="form-floating flex-fill" style="width: 45%;">
                    <input type="text" name="lname" class="form-control rounded-3" id="lname" placeholder="Last Name" value="<?= showFormData('lname') ?>">
                    <label for="lname" class="form-label">Last Name</label>
                    <?= showError('lname') ?>
                </div>
            </div>

            <div class="d-flex gap-3 mt-2">
                <div class="form-floating flex-fill" style="width: 45%;">
                    <input type="email" name="email" class="form-control rounded-3" id="email" placeholder="Email Address" value="<?= showFormData('email') ?>">
                    <label for="email" class="form-label">Email Address</label>
                    <?= showError('email') ?>
                </div>
                <div style="width: 10%;"></div>
                <div class="form-floating flex-fill" style="width: 45%;">
                    <input type="text" name="uname" class="form-control rounded-3" id="uname" placeholder="Username" value="<?= showFormData('uname') ?>">
                    <label for="uname" class="form-label">Username</label>
                    <?= showError('uname') ?>
                </div>
            </div>

            <div class="form-floating mt-2">
                <input type="password" name="password" class="form-control rounded-3" id="password" placeholder="Password">
                <label for="password" class="form-label">Password</label>
                <?= showError('password') ?>
            </div>

            <div class="d-flex justify-content-between mt-2 mx-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" id="male" value="1" <?= showFormData('gender') == '1' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="male">Male</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" id="female" value="2" <?= showFormData('gender') == '2' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="female">Female</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" id="other" value="0" <?= showFormData('gender') == '0' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="other">Others</label>
                </div>
            </div>

            <div class="form-floating mt-2">
                <input type="text" name="address" class="form-control rounded-3" id="address" placeholder="Full Address" value="<?= showFormData('address') ?>">
                <label for="address" class="form-label">Full Address</label>
                <?= showError('address') ?>
            </div>

            <div class="d-flex gap-3 mt-2">
                <div class="form-floating flex-fill" style="width: 45%;">
                    <input type="date" name="dob" class="form-control rounded-3" id="dob" placeholder="Date of Birth" value="<?= showFormData('dob') ?>">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <?= showError('dob') ?>
                </div>
                <div style="width: 10%;"></div>
                <div class="form-floating flex-fill" style="width: 45%;">
                    <input type="text" name="phone" class="form-control rounded-3" id="phone" placeholder="Phone Number" value="<?= showFormData('phone') ?>">
                    <label for="phone" class="form-label">Phone Number</label>
                    <?= showError('phone') ?>
                </div>
            </div>

            <div class="d-flex gap-3 mt-2">
                <div class="form-floating flex-fill" style="width: 45%;">
                    <input type="text" name="state" class="form-control rounded-3" id="state" placeholder="State" value="<?= showFormData('state') ?>">
                    <label for="state" class="form-label">State</label>
                    <?= showError('state') ?>
                </div>
                <div style="width: 10%;"></div>
                <div class="form-floating flex-fill" style="width: 45%;">
                    <input type="text" name="pincode" class="form-control rounded-3" id="pincode" placeholder="Pin Code" value="<?= showFormData('pincode') ?>">
                    <label for="pincode" class="form-label">Pin Code</label>
                    <?= showError('pincode') ?>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3" style="width: 100%;">Submit</button>
            
            <div style="text-align: center; margin-top: 5px;">
                <a href="?login" class="text-decoration-none">Already have an account?</a>
            </div>
        </form>
    </div>
</div>
