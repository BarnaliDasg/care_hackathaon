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
        }

        header("Location: ../../?verify");
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


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $post_id = $_POST["post_id"] ?? null;
    $content = $_POST["content"] ?? null;

    if ($post_id && $content) {
        if (addComment($post_id, $content)) {
            echo json_encode(["status" => "success", "message" => "Comment added"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to add comment"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing data"]);
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
    $post_id = $_GET["post_id"] ?? null;
    if ($post_id) {
        $comments = getComments($post_id);
        echo json_encode(["status" => "success", "comments" => $comments]);
    } else {
        echo json_encode(["status" => "error", "message" => "Missing post_id"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

if (isset($_GET['action']) && $_GET['action'] == "search_users") {
    $pincode = $_GET['pincode'];
    echo json_encode(getUsersByPincode($pincode));
    exit;
}


?>
