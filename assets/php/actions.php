<?php
require_once 'functions.php';
require_once 'send_code.php';

// For managing signup
if (isset($_GET['signup'])) {
    $response = validateSignupForm($_POST);
    
    if ($response['status']) {
        if (createUser($_POST)) {
            header("Location: ../../?login&newuser");
            exit();
        } else {
            echo "<script>alert('Something went wrong!')</script>";
        }
    } else {
        $_SESSION['formdata'] = $_POST;
        $_SESSION['error'] = $response;
        header("Location: ../../?signup");
        exit();
    }
}

// For managing login
if (isset($_GET['login'])) {
    $response = validateLoginForm($_POST);
    
    if ($response['status']) {
        $_SESSION['Auth'] = true;
        $_SESSION['userdata'] = $response['user'];

        if ($response['user']['ac_status'] == 0) {
            $code = rand(111111, 999999);
            $_SESSION['code'] = $code;
            sendCode($response['user']['email'], 'Verify your email', $code);
            header("Location: ../../?verify");
            exit();
        } elseif ($response['user']['ac_status'] == 3) {
            header("Location: /care/assets/pages/admin.php");

            exit();
        }

        header("Location: ../../");
        exit();
    } else {
        $_SESSION['formdata'] = $_POST;
        $_SESSION['error'] = $response;
        header("Location: ../../?login");
        exit();
    }
}


// For verifying email
if (isset($_GET['verify_email'])) {
    $user_code = $_POST['code'] ?? null;
    $code = $_SESSION['code'] ?? null;

    if ($code && $code == $user_code) {
        if (verifyEmail($_SESSION['userdata']['email'])) {
            header('Location: ../../?verified');
            exit();
        } else {
            echo "Something went wrong during email verification.";
        }
    } else {
        $response = [
            'msg' => empty($user_code) ? 'Enter six-digit verification code' : 'Incorrect verification code',
            'field' => 'email_verify'
        ];
        $_SESSION['error'] = $response;
        header('Location: ../../?verify');
        exit();
    }
}

// For resending verification code
if (isset($_GET['resendCode'])) {
    $email = $_SESSION['userdata']['email'] ?? null;
    
    if ($email) {
        $_SESSION['code'] = rand(111111, 999999);
        $newCode=$_SESSION['code'];
        $message = sendCode($email, 'Your new verification code', $newCode);
        $_SESSION['message'] = "Verification code resent successfully.";
    } else {
        $_SESSION['error'] = ['msg' => 'Unable to resend verification code.'];
    }

    header('Location: ../../?verify');
    exit();
}

// For logging out the user
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ../../');
    exit();
}

// Forgot Password Process
if (isset($_GET['forgotpassword'])) {
    if (empty($_POST['email'])) {
        $response = ['msg' => "Enter your email ID", 'field' => 'email'];
        $_SESSION['error'] = $response;
        header('Location: ../../?forgotpassword');
        exit();
    } elseif (!isEmailRegistered($_POST['email'])) {
        $response = ['msg' => "Email ID is not registered", 'field' => 'email'];
        $_SESSION['error'] = $response;
        header('Location: ../../?forgotpassword');
        exit();
    } else {
        $_SESSION['forgot_email'] = $_POST['email'];
        $_SESSION['forgot_code'] = $forgotCode = rand(111111, 999999);
        sendCode($_POST['email'], 'Forgot password', $forgotCode);
        header('Location: ../../?forgotpassword&resended');
        exit();
    }
}

// Verify Forgot Password Code
if (isset($_GET['verify'])) {
    $user_code = $_POST['verification_code'] ?? null;
    $code = $_SESSION['forgot_code'] ?? null;

    if ($code && $code == $user_code) {
        $_SESSION['auth_temp'] = true;
        header('Location: ../../?changepassword');
        exit();
    } else {
        $response = [
            'msg' => empty($user_code) ? 'Enter 6-digit code' : 'Incorrect verification code',
            'field' => 'email_verify'
        ];
        $_SESSION['error'] = $response;
        header('Location: ../../?forgotpassword');
        exit();
    }
}
//changePassword
if(isset($_GET['changepassword'])){
    if (empty($_POST['password'])) {
        $response['msg'] ="Enter your new password";
        $response['field']='password';
        $_SESSION['error'] = $response;
        header('Location: ../../?forgotpassword');
    }else{
        resetPassword($_SESSION['forgot_email'],$_POST['password']);
        header('Location: ../../?reseted');
    }
    
}
//edit_profile
if (isset($_GET['updateprofile'])) {
    // Check if the 'profile_pic' file was uploaded and set it to null if not
    $profilePicData = isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK ? $_FILES['profile_pic'] : null;

    // Validate the form data and file (if provided)
    $response = validateUpdateForm($_POST, $profilePicData);
   
    if ($response['status']) {
        // Attempt to update the profile
        $updateResponse = updateProfile($_POST, $profilePicData);
       
        if ($updateResponse['status']) {
            header("Location: ../../?edit_profile");
        } else {
            echo "Something went wrong: " . $updateResponse['msg'];
        }
    } else {
        // If validation fails, store the error response in the session and redirect
        $_SESSION['error'] = $response;
        header("Location: ../../?edit_profile");
        exit();
    }
}

// for managing addpost
if (isset($_GET['addpost'])) {
    $image = isset($_FILES['post_img']) && $_FILES['post_img']['size'] > 0 ? $_FILES['post_img'] : null; // Handle optional image

    // Validate the image only if it's provided
    if ($image) {
        $response = validatePostImage($image);
        if (!$response['status']) {
            $_SESSION['error'] = $response;
            header("location:../../");
            exit();
        }
    }

    // Create post

    $result = createPost($_POST['post_text'], $_FILES['post_img'], $_POST['post_address'], $_POST['post_pincode']);

    if ($result === true) {
        header("Location: ../../");
        exit();
    } else {
        echo $result; // Display error message if any
    }

}

//add comment
if (isset($_GET['addComment'])) {
    if (!empty($_POST['post_text']) && !empty($_POST['post_id'])) {  
        $post_id = $_POST['post_id']; // Get post_id from form
        $result = addComment($post_id, $_POST['post_text']);

        if ($result === true) {
            // Redirect back to the same page
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            echo $result; // Display error message if any
            exit;
        }
    } else {
        showError('post_text'); // Call error function
        exit;
    }
}

//search
if (isset($_GET['SearchUsers'])) {
    if (!empty($_POST['pincode'])) {  
        $pincode = trim($_POST['pincode']); // Get pincode from form

        $result = searchUsersByPincode($pincode); // Call function to get users

        if ($result !== false) {
            // Redirect back to the same page (or to the results page)
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            echo "No users found for this pincode."; // Display error message if no users found
            exit;
        }
    } else {
        showError('pincode'); // Call error function if input is empty
        exit;
    }
}


if (isset($_GET['action']) && $_GET['action'] == "search_users") {
    $pincode = $_GET['pincode'];
    echo json_encode(getUsersByPincode($pincode));
    exit;
}
// Messages
if (isset($_GET['addMessage'])) {
    if (!empty($_POST['message_text']) && !empty($_POST['receiver_id'])) {  
        $receiver_id = $_POST['receiver_id']; // Get receiver ID from form
        $result = addMessage($receiver_id, $_POST['message_text']);

        if ($result === true) {
            // Redirect back to the same page
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            echo $result; // Display error message if any
            exit;
        }
    } else {
        showError('message_text'); // Call error function
        exit;
    }
}



?>