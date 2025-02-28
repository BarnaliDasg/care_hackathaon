<?php global $user; ?>

<div class="container col-9 rounded-0 d-flex justify-content-between">
    <div class="col-12 bg-white border rounded p-4 mt-4 shadow-sm">
        <form method="post" action="assets/php/actions.php?updateprofile" enctype="multipart/form-data">
            <div class="d-flex justify-content-center"></div>
            <h1 class="h5 mb-3 fw-normal">Edit Profile</h1>

            <?php if (isset($_GET['success'])): ?>
                <p class="text-success">Profile is updated</p>
            <?php endif; ?>

            <div class="form-floating mt-1 col-6">
                <img src="assets/images/profile/<?=$user['profile_pic']?>" class="img-thumbnail my-3" style="height:150px; width: 150px" alt="Profile Picture">
                <div class="mb-3">
                    <label for="formFile" class="form-label">Change Profile Picture</label>
                    <input class="form-control" name="profile_pic" type="file" id="formFile">
                    <?=showError('profile_pic')?>
                </div>
            </div>

            <div class="d-flex">
                <div class="form-floating mt-1 col-6">
                    <input type="text" name="fname" value="<?=htmlspecialchars($user['fname'])?>" class="form-control rounded-0" placeholder="First Name">
                    <label for="floatingInput">First Name</label>
                </div>

                <div class="form-floating mt-1 col-6">
                    <input type="text" name="lname" value="<?=htmlspecialchars($user['lname'])?>" class="form-control rounded-0" placeholder="Last Name">
                    <label for="floatingInput">Last Name</label>
                </div>
            </div>
            <?=showError('fname')?>
            <?=showError('lname')?>

            <div class="d-flex gap-3 my-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" value="1" <?=$user['gender'] == 1 ? 'checked' : ''?>>
                    <label class="form-check-label">Male</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" value="2" <?=$user['gender'] == 2 ? 'checked' : ''?>>
                    <label class="form-check-label">Female</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" value="0" <?=$user['gender'] == 0 ? 'checked' : ''?>>
                    <label class="form-check-label">Other</label>
                </div>
            </div>

            <div class="form-floating mt-1">
                <input type="email" name="email" value="<?=htmlspecialchars($user['email'])?>" class="form-control rounded-0" placeholder="Email" disabled>
                <label for="floatingInput">Email</label>
            </div>

            <div class="form-floating mt-1">
                <input type="text" name="uname" value="<?=htmlspecialchars($user['uname'])?>" class="form-control rounded-0" placeholder="Username">
                <label for="floatingInput">Username</label>
            </div>
            <?=showError('uname')?>

            <div class="form-floating mt-1">
                <input type="password" name="password" class="form-control rounded-0" id="floatingPassword" placeholder="Enter new password">
                <label for="floatingPassword">New Password</label>
            </div>

            <!-- New Fields Added -->
            <div class="form-floating mt-1">
                <input type="text" name="phone" value="<?=htmlspecialchars($user['phone'] ?? '')?>" class="form-control rounded-0" placeholder="Phone Number">
                <label for="floatingInput">Phone Number</label>
            </div>
            
            <div class="form-floating mt-1">
                <input type="text" name="address" value="<?=htmlspecialchars($user['address'] ?? '')?>" class="form-control rounded-0" placeholder="Address">
                <label for="floatingInput">Address</label>
            </div>
            
            <div class="form-floating mt-1">
                <input type="date" name="dob" value="<?=htmlspecialchars($user['dob'] ?? '')?>" class="form-control rounded-0" placeholder="Date of Birth">
                <label for="floatingInput">Date of Birth</label>
            </div>

            <!-- Role Field -->
            <div class="form-floating mt-1">
                <select name="role" class="form-select rounded-0" style="width:100%">
                    <option value="caregiver" <?= $user['role'] == 'caregiver' ? 'selected' : '' ?>>caregiver</option>
                    <option value="careseeker" <?= $user['role'] == 'careseeker' ? 'selected' : '' ?>>careseeker</option>
                    
                </select>
                <label for="floatingInput">Role</label>
            </div>

            <div class="mt-3 d-flex justify-content-between align-items-center">
                <button class="btn btn-primary" type="submit" style="width: 100%; background-color: #e7c1fd; color: rgb(15, 43, 127);">Update Profile</button>
            </div>
        </form>
    </div>
</div>
