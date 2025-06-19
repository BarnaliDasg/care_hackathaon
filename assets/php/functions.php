<?php
require_once 'configu.php'; // Ensure this file sets up the database connection

// Create a global database connection
$db = getDbConnection();

// Function for showing pages
function showPage($page, $data = "") {
    include("assets/pages/$page.php");
}

//function for follow the user
function followUser($u_id){
    global $db;
    $current_user=$_SESSION['userdata']['id'];
    $query="INSERT INTO follow_list(follower_id,u_id) VALUES($current_user,$u_id)";
    return  mysqli_query($db, $query);
}
//function checklike status
function checklikeStatus($post_id){
    global $db;

    $current_user=$_SESSION['userdata']['id'];
    $query="SELECT count(*) as row FROM  likes WHERE u_id=$current_user && post_id=$post_id";
    
    $run = mysqli_query($db, $query);
    return mysqli_fetch_assoc($run)['row'];
}

//function checkcomment status
function checkcommentStatus($post_id){
    global $db;

    $current_user=$_SESSION['userdata']['id'];
    $query="SELECT count(*) as row FROM  comments WHERE u_id=$current_user && post_id=$post_id";
    
    $run = mysqli_query($db, $query);
    return mysqli_fetch_assoc($run)['row'];
}

//function for like
function like($post_id){
    global $db;
    $current_user=$_SESSION['userdata']['id'];
    $query="INSERT INTO likes(post_id,u_id) VALUES($post_id,$current_user)";
    return  mysqli_query($db, $query);
}

//function for unlike
function unlike($post_id){
    global $db;
    $current_user=$_SESSION['userdata']['id'];
    $query="DELETE FROM likes WHERE u_id=$current_user && post_id=$post_id";
    return  mysqli_query($db, $query);
}

//function for getting like counts
function getLikes($post_id){
    global $db;
    $query="SELECT * FROM  likes WHERE post_id=$post_id";
    $run = mysqli_query($db, $query);
    return mysqli_fetch_all($run,true);
}

//function for follow the user
function unfollowUser($u_id){
    global $db;
    $current_user=$_SESSION['userdata']['id'];
    $query="DELETE FROM follow_list WHERE follower_id=$current_user && u_id=$u_id";
    return  mysqli_query($db, $query);
}

// Function for showing errors
function showError($field) {
    if (isset($_SESSION['error'])) {
        $error = $_SESSION['error'];
        if (isset($error['field']) && $field == $error['field']) {
            ?>
            <div class="alert alert-danger my-2" role="alert">
                <?php echo htmlspecialchars($error['msg']); ?>
            </div>
            <?php
        }
    }
}

// Function for showing previous form data
function showFormData($field) {
    if (isset($_SESSION['formdata'])) {
        $formdata = $_SESSION['formdata'];
        return isset($formdata[$field]) ? htmlspecialchars($formdata[$field]) : '';
    }
    return '';
}

// Function for validating signup form
function validateSignupForm($form_data) {
    $response = array();
    $response['status'] = true;

    if (empty($form_data['password'])) {
        $response['msg'] = "Password is not given";
        $response['status'] = false;
        $response['field'] = 'password';
    }
    if (empty($form_data['uname'])) {
        $response['msg'] = "Username is not given";
        $response['status'] = false;
        $response['field'] = 'uname';
    }
    if (empty($form_data['email'])) {
        $response['msg'] = "Email is not given";
        $response['status'] = false;
        $response['field'] = 'email';
    }
    if (empty($form_data['lname'])) {
        $response['msg'] = "Last name is not given";
        $response['status'] = false;
        $response['field'] = 'lname';
    }
    if (empty($form_data['fname'])) {
        $response['msg'] = "First name is not given";
        $response['status'] = false;
        $response['field'] = 'fname';
    }
    if (isEmailRegistered($form_data['email'])) {
        $response['msg'] = "Email ID is already registered";
        $response['status'] = false;
        $response['field'] = 'email';
    }
    if (isUserRegistered($form_data['uname'])) {
        $response['msg'] = "Username is already taken";
        $response['status'] = false;
        $response['field'] = 'uname';
    }
    return $response;
}

// Function for checking duplicate email
function isEmailRegistered($email) {
    global $db;
    $email = mysqli_real_escape_string($db, $email);
    $query = "SELECT COUNT(*) as row FROM user WHERE email='$email'";
    $run = mysqli_query($db, $query);
    if (!$run) {
        die("Query Failed: " . mysqli_error($db));
    }
    $return_data = mysqli_fetch_assoc($run);
    return $return_data['row'] > 0;
}

// Function for checking duplicate username
function isUserRegistered($uname) {
    global $db;
    $uname = mysqli_real_escape_string($db, $uname);
    $query = "SELECT COUNT(*) as row FROM user WHERE uname='$uname'";
    $run = mysqli_query($db, $query);
    if (!$run) {
        die("Query Failed: " . mysqli_error($db));
    }
    $return_data = mysqli_fetch_assoc($run);
    return $return_data['row'] > 0;
}
//username registered by other
function isUserRegisteredbyOther($uname) {
    global $db;
    $user_id=$_SESSION['userdata']['id'];
    $uname = mysqli_real_escape_string($db, $uname);
    $query = "SELECT COUNT(*) as row FROM user WHERE uname='$uname' && id!=$user_id";
    $run = mysqli_query($db, $query);
    if (!$run) {
        die("Query Failed: " . mysqli_error($db));
    }
    $return_data = mysqli_fetch_assoc($run);
    return $return_data['row'] > 0;
}

// Function for creating a new user
function createUser($data) {
    global $db;

    // Sanitize inputs
    $fname = mysqli_real_escape_string($db, $data['fname']);
    $lname = mysqli_real_escape_string($db, $data['lname']);
    $role = mysqli_real_escape_string($db, $data['role']);
    $gender = intval($data['gender']);
    $email = mysqli_real_escape_string($db, $data['email']);
    $uname = mysqli_real_escape_string($db, $data['uname']);
    $password = mysqli_real_escape_string($db, $data['password']);
    $dob = mysqli_real_escape_string($db, $data['dob']);
    $phone = mysqli_real_escape_string($db, $data['phone']);
    $address = mysqli_real_escape_string($db, $data['address']);
    $state = mysqli_real_escape_string($db, $data['state']);
    $pincode = mysqli_real_escape_string($db, $data['pincode']);

    // Hash password using md5 (but bcrypt is recommended for security)
    $password = md5($password);

    // Insert user into the database (excluding profile_pic)
    $query = "INSERT INTO user (fname, lname, role, gender, email, uname, password, dob, phone, address, state, pincode) 
              VALUES ('$fname', '$lname', '$role', $gender, '$email', '$uname', '$password', '$dob', '$phone', '$address', '$state', '$pincode')";

    return mysqli_query($db, $query);
}





// Function for validating login form
function validateLoginForm($form_data) {
    $response = array();
    $response['status'] = true;
    $blank = false;

    if (empty($form_data['password'])) {
        $response['msg'] = "Password is not given";
        $response['status'] = false;
        $response['field'] = 'password';
        $blank = true;
    }
    if (empty($form_data['uname_email'])) {
        $response['msg'] = "Username/email is not given";
        $response['status'] = false;
        $response['field'] = 'uname_email';
        $blank = true;
    }
    if (!$blank && checkUser($form_data)['status'] === false) {
        $response['msg'] = "Invalid username/password!";
        $response['status'] = false;
        $response['field'] = 'checkUser';
    } else {
        $response['user'] = checkUser($form_data)['user'];
    }
    return $response;
}

// Function for checking the user
function checkUser($login_data) {
    global $db;
    $uname_email = mysqli_real_escape_string($db, $login_data['uname_email']);
    $password = md5($login_data['password']);

    $query = "SELECT * FROM user WHERE (email='$uname_email' OR uname='$uname_email') AND password='$password'";
    $run = mysqli_query($db, $query);
    if (!$run) {
        die("Query Failed: " . mysqli_error($db));
    }

    $data['user'] = mysqli_fetch_assoc($run);
    $data['status'] = !empty($data['user']);
    return $data;
}

//for getting user data by ID
function getUser($user_id) {
    global $db;

    // Ensure $user_id is a valid integer
    if (!is_numeric($user_id) || $user_id <= 0) {
        return false;
    }

    $user_id = mysqli_real_escape_string($db, $user_id);
    $query = "SELECT * FROM user WHERE id = $user_id";
    $run = mysqli_query($db, $query);

    if (!$run) {
        die("Query Failed: " . mysqli_error($db));
    }

    return mysqli_fetch_assoc($run);
}

//for getting user data by uname
function getUserbyName($uname) {
    global $db;

    $query = "SELECT * FROM user WHERE uname = '$uname'";
    $run = mysqli_query($db, $query);

    return mysqli_fetch_assoc($run);
}

//verify email
function verifyEmail($email) {
    global $db; 
    $stmt = $db->prepare("UPDATE user SET ac_status = 1 WHERE email = ?");
    if ($stmt === false) {
        error_log("Prepare failed: " . $db->error);
        return false;
    }
    $stmt->bind_param("s", $email); 
    return $stmt->execute();
}

// Resend verification code
function resendCode($email) {
    // Generate a new verification code
    $verificationCode = rand(111111, 999999);
    // Attempt to send the new code to the user's email
    if (sendCode($email, 'Verify your email', $verificationCode)) {
        $_SESSION['code'] = $verificationCode; // Store the new code in session
        return "Verification code has been resent to $email.";
    } else {
        return "Failed to resend the verification code. Please try again.";
    }
}
//resetpassword
function resetpassword($email,$password) {
    global $db; 
    $password=md5($password);
    $query = "UPDATE user SET password='$password' WHERE email = '$email'";
    return ($db->query($query));
}

//for validating update form
function validateUpdateForm($form_data,$image_data) {
    $response = array();
    $response['status'] = true;

    if (empty($form_data['uname'])) {
        $response['msg'] = "Username is not given";
        $response['status'] = false;
        $response['field'] = 'uname';
    }
    if (empty($form_data['lname'])) {
        $response['msg'] = "Last name is not given";
        $response['status'] = false;
        $response['field'] = 'lname';
    }
    if (empty($form_data['fname'])) {
        $response['msg'] = "First name is not given";
        $response['status'] = false;
        $response['field'] = 'fname';
    }
    if (isUserRegisteredbyOther($form_data['uname'])) {
        $response['msg'] = $form_data['uname']." is already taken";
        $response['status'] = false;
        $response['field'] = 'uname';
    }

    if($image_data['name']){
        $image=basename($image_data['name']);
        $type=strtolower(pathinfo($image,PATHINFO_EXTENSION));
        $size=$image_data['size']/1000;

        if ($type!='jpg' && $type!='jpeg' && $type!='png') {
            $response['msg'] = "only jpg/jpeg/png images allowed";
            $response['status'] = false;
            $response['field'] = 'profile_pic';
        }

        if($size>1000){
            $response['msg'] = "upload image less tham 1MB";
            $response['status'] = false;
            $response['field'] = 'profile_pic';
        }
    }

    return $response;
}

function updateProfile($data, $imagedata = null) {
    global $db;

    // Validate session user
    if (!isset($_SESSION['userdata']['id'])) {
        return ['status' => false, 'msg' => 'Unauthorized access.'];
    }

    $user_id = intval($_SESSION['userdata']['id']);

    // Sanitize inputs
    $fname = mysqli_real_escape_string($db, $data['fname']);
    $lname = mysqli_real_escape_string($db, $data['lname']);
    $uname = mysqli_real_escape_string($db, $data['uname']);
    $role = mysqli_real_escape_string($db, $data['role']);

    // Update password only if provided
    $password_query = "";
    if (!empty($data['password'])) {
        $passWord = md5($data['password']); // Using md5 as requested
        $password_query = ", password='$passWord'";
    }

    // Initialize profile_pic update
    $profile_pic_query = "";
    if (!empty($imagedata['name'])) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($imagedata['type'], $allowed_types)) {
            return ['status' => false, 'msg' => 'Invalid image type.'];
        }
        if ($imagedata['size'] > 2 * 1024 * 1024) {
            return ['status' => false, 'msg' => 'File size exceeds 2MB.'];
        }

        $image_name = uniqid() . "_" . basename($imagedata['name']);
        $image_dir = "../images/profile/$image_name";
        if (move_uploaded_file($imagedata['tmp_name'], $image_dir)) {
            $profile_pic_query = ", profile_pic='$image_name'";
        } else {
            return ['status' => false, 'msg' => 'Image upload failed.'];
        }
    }

    // Construct query
    $query = "UPDATE user SET fname='$fname', lname='$lname', uname='$uname', role='$role' $password_query $profile_pic_query WHERE id=$user_id";

    // Execute query
    if (mysqli_query($db, $query)) {
        return ['status' => true, 'msg' => 'Profile updated successfully.'];
    } else {
        error_log(mysqli_error($db));
        return ['status' => false, 'msg' => 'Database update failed.'];
    }
}


//validating addpost
function validatePostImage($image_data) {
    $response = array();
    $response['status'] = true;

    if(!$image_data['name']){
        $response['msg']="No image is selected";
        $response['status']=false;
        $response['field']='post_img';
    }

    if($image_data['name']){
        $image=basename($image_data['name']);
        $type=strtolower(pathinfo($image,PATHINFO_EXTENSION));
        $size=$image_data['size']/1000;

        if ($type!='jpg' && $type!='jpeg' && $type!='png') {
            $response['msg'] = "only jpg/jpeg/png images allowed";
            $response['status'] = false;
            $response['field'] = 'post_img';
        }

        if($size>1000){
            $response['msg'] = "upload image less tham 1MB";
            $response['status'] = false;
            $response['field'] = 'post_img';
        }
    }

    return $response;
}

function createPost($text, $image, $address, $pincode) {
    global $db;
    $u_id = $_SESSION['userdata']['id'];

    // Sanitize input
    $post_text = mysqli_real_escape_string($db, trim($text)); 
    $address = mysqli_real_escape_string($db, trim($address));
    $pincode = mysqli_real_escape_string($db, trim($pincode));

    // Validate image upload
    if (!isset($image['tmp_name']) || empty($image['tmp_name'])) {
        return false; // No image uploaded
    }

    // Check file extension for security
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $image_ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

    if (!in_array($image_ext, $allowed_extensions)) {
        return false; // Invalid file type
    }

    // Generate a unique filename to prevent overwriting
    $unique_name = uniqid('post_', true) . '.' . $image_ext;
    $image_dir = "../images/posts/$unique_name";

    if (move_uploaded_file($image['tmp_name'], $image_dir)) {
        // Insert post into database
        $query = "INSERT INTO posts (u_id, post_txt, post_img, post_address, post_pincode) 
                  VALUES ('$u_id', '$post_text', '$unique_name', '$address', '$pincode')";

        return mysqli_query($db, $query) ?: false; // Return false on failure
    }

    return false; // Image upload failed
}

//for getting post
function getPost() {
    global $db;

    $query = "SELECT posts.id, posts.u_id, posts.post_img, posts.post_txt, posts.post_address, posts.post_pincode, 
                     posts.created_at, user.fname, user.lname, user.uname, user.profile_pic 
              FROM posts 
              JOIN user ON user.id = posts.u_id 
              ORDER BY posts.id DESC";

    $run = mysqli_query($db, $query);
    return mysqli_fetch_all($run, MYSQLI_ASSOC); // Use MYSQLI_ASSOC to return associative array
}


//getting post dynamically
function filterpost() {
    $list = getPost();
    $filter_list = array();

    if (!isset($_SESSION['userdata']['id'])) {
        return $filter_list; // Prevents undefined index error
    }

    foreach ($list as $post) {
        if (checkFollowStatus($post['u_id']) || $post['u_id'] == $_SESSION['userdata']['id']) {
            $filter_list[] = $post;
        }
    }
    return $filter_list;
}


//for filtering suggestions
function filterFollowSuggestions(){
    $list=getFollowSuggestions();
    $filter_list=array();

    foreach($list as $user){
        if(!checkFollowStatus($user['id']) && count($filter_list)<5){
            $filter_list[]=$user;
        }
    }
    return $filter_list;
}

//for checking the user is follwed by current user
function checkFollowStatus($u_id){
    global $db;

    $current_user=$_SESSION['userdata']['id'];
    $query="SELECT count(*) as row FROM  follow_list WHERE follower_id=$current_user && u_id=$u_id";
    
    $run = mysqli_query($db, $query);
    return mysqli_fetch_assoc($run)['row'];
}

//for getting users foe follow suggestions
function getFollowSuggestions(){
    global $db;
    $current_user=$_SESSION['userdata']['id'];

    $query="SELECT * from user WHERE id!=$current_user";

    $run = mysqli_query($db, $query);
    return mysqli_fetch_all($run,true);
}

//get followers count
function getfollowers($u_id){
    global $db;

    $query="SELECT * from follow_list WHERE u_id=$u_id";

    $run = mysqli_query($db, $query);
    return mysqli_fetch_all($run,true);
}

//get followers count
function getfollowing($u_id){
    global $db;

    $query="SELECT * from follow_list WHERE follower_id=$u_id";

    $run = mysqli_query($db, $query);
    return mysqli_fetch_all($run,true);
}


//for getting post by id
function getPostbyId($u_id) {
    global $db;

    $query = "SELECT * from posts WHERE u_id=$u_id ORDER BY id DESC";

    $run = mysqli_query($db, $query);

    return mysqli_fetch_all($run,true);
}

//add comment
function addComment($post_id, $comment_text) {
    global $db;
    $user_id = $_SESSION['userdata']['id'];
    
    $post_id = intval($post_id); // Ensure post_id is a valid integer
    $text = mysqli_real_escape_string($db, trim($comment_text)); 

    $query = "INSERT INTO comments (user_id, post_id, comment) VALUES ('$user_id', '$post_id', '$text')";
    
    return mysqli_query($db, $query) ? true : mysqli_error($db);
}


//get comment
function getComments($post_id) {
    global $db; // Assuming $db is your database connection

    $query = "SELECT comments.id, comments.user_id, comments.post_id, comments.comment, comments.created_at, 
                     user.fname, user.lname, user.uname, user.profile_pic 
              FROM comments 
              JOIN user ON user.id = comments.user_id 
              WHERE comments.post_id = ? 
              ORDER BY comments.id ASC";

    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "i", $post_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


//search user by pincode
function searchUsersByPincode($pincode) {
    global $db;

    $query = "SELECT id, fname, lname, uname, profile_pic, role FROM user WHERE pincode = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "s", $pincode);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    return $users;
}
// Add Message (Similar to addComment)
function addMessage($receiver_id, $message_text) {
    global $db;
    $sender_id = $_SESSION['userdata']['id'];
    
    $receiver_id = intval($receiver_id);
    $text = mysqli_real_escape_string($db, trim($message_text));

    $query = "INSERT INTO chats (sender_id, receiver_id, message) VALUES ('$sender_id', '$receiver_id', '$text')";
    
    return mysqli_query($db, $query) ? true : mysqli_error($db);
}


// Get Messages (Similar to getComments)
function getMessages($receiver_id) {
    global $db;
    $sender_id = $_SESSION['userdata']['id'];

    $query = "SELECT messages.id, messages.sender_id, messages.receiver_id, messages.message, messages.created_at, 
                     sender.fname AS sender_fname, sender.lname AS sender_lname, sender.uname AS sender_uname, sender.profile_pic AS sender_pic,
                     receiver.fname AS receiver_fname, receiver.lname AS receiver_lname, receiver.uname AS receiver_uname, receiver.profile_pic AS receiver_pic
              FROM chats 
              JOIN user AS sender ON sender.id = messages.sender_id 
              JOIN user AS receiver ON receiver.id = messages.receiver_id 
              WHERE (messages.sender_id = ? AND messages.receiver_id = ?) 
                 OR (messages.sender_id = ? AND messages.receiver_id = ?) 
              ORDER BY messages.id ASC";

    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getAllTableNames() {
    $db = getDbConnection();
    $result = mysqli_query($db, "SHOW TABLES");
    $tables = [];
    if ($result) {
        while ($row = mysqli_fetch_array($result)) {
            $tables[] = $row[0];
        }
    }
    return $tables;
}
//get tabledata

function getTableData($table) {
    $db = getDbConnection();
    // Validate table name to avoid SQL injection
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
        return false;
    }
    // Get columns
    $columnsResult = mysqli_query($db, "DESCRIBE `$table`");
    if (!$columnsResult) return false;
    $columns = [];
    while ($col = mysqli_fetch_assoc($columnsResult)) {
        $columns[] = $col['Field'];  // Only store column names
    }
    // Get rows (limit 100)
    $rowsResult = mysqli_query($db, "SELECT * FROM `$table` LIMIT 100");
    if (!$rowsResult) return false;
    $rows = [];
    while ($row = mysqli_fetch_row($rowsResult)) {
        $rows[] = $row;
    }
    return ['columns' => $columns, 'rows' => $rows];
}
// Function to edit a row (USE PREPARED STATEMENTS!)
function editRow($table, $rowIndex, $columnName, $newValue) {
    $db = getDbConnection();

    error_log("Table name received: " . $table);

    // Escape values to avoid injection
    $table = mysqli_real_escape_string($db, $table);
    $columnName = mysqli_real_escape_string($db, $columnName);
    $newValue = mysqli_real_escape_string($db, $newValue);
    $rowIndex = mysqli_real_escape_string($db, $rowIndex);

    $sql = "UPDATE `$table` SET `$columnName` = '$newValue' WHERE id = '$rowIndex'";

    if (mysqli_query($db, $sql)) {
        return true;
    } else {
        error_log("Error updating row: " . mysqli_error($db));
        return false;
    }
}

// Function to delete a row (USE PREPARED STATEMENTS!)
function deleteRow($table, $id) {
    $db = getDbConnection();

    // Validate table name to allow only alphanumeric + underscore
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
        error_log("Invalid table name.");
        return false;
    }

    // Validate that ID is a number
    if (!is_numeric($id)) {
        error_log("Invalid ID.");
        return false;
    }

    // Use prepared statements to avoid SQL injection
    $stmt = $db->prepare("DELETE FROM `$table` WHERE id = ?");
    if (!$stmt) {
        error_log("Prepare failed: " . $db->error);
        return false;
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        return true;
    } else {
        error_log("Execute failed: " . $stmt->error);
        return false;
    }
}