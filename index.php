<?php
require_once 'assets/php/functions.php';

if (isset($_GET['newfp'])) {
    unset($_SESSION['auth_temp']);
    unset($_SESSION['forgot_email']);
    unset($_SESSION['forgot_code']);
}

// Check if user is authenticated
if (isset($_SESSION['Auth']) && isset($_SESSION['userdata']['id'])) {
    $user_id = $_SESSION['userdata']['id'];
    $user = getUser($user_id); // Ensure valid user retrieval

    if ($user) {
        $posts = filterpost();
        $follow_suggestions = filterFollowSuggestions();
    } else {
        $user = false; // Handle case where user data is missing
    }
}

$pageCount = count($_GET);

// Manage pages 
if (isset($_SESSION['Auth']) && $user && $user['ac_status'] == 1 && !$pageCount) {
    showPage('header', ['page_title' => 'Home']);
    showPage('navbar');
    showPage('wall');
    showPage('footer');

} elseif (isset($_SESSION['Auth']) && $user && $user['ac_status'] == 0 && !$pageCount) {
    showPage('header', ['page_title' => 'Verify your email']);
    showPage('verify_email');
    showPage('footer');

} elseif (isset($_SESSION['Auth']) && $user && $user['ac_status'] == 2 && !$pageCount) {
    showPage('header', ['page_title' => 'Blocked']);
    showPage('blocked');
    showPage('footer');

}elseif (isset($_SESSION['Auth']) && $user && $user['ac_status'] == 3 && !$pageCount) {
    showPage('header', ['page_title' => 'Admin Panel']);
    showPage('admin');
    showPage('footer');

}  elseif (isset($_GET['signup'])) {
    showPage('header', ['page_title' => 'SignUp']);
    showPage('signup');
    showPage('footer');

} elseif (isset($_GET['login'])) {
    showPage('header', ['page_title' => 'Login']);
    showPage('login');
    showPage('footer');

} elseif (isset($_GET['forgotpassword'])) {
    showPage('header', ['page_title' => 'Forgot password']);
    showPage('forgot_password');
    showPage('footer');

} elseif (isset($_SESSION['Auth']) && isset($_GET['edit_profile']) && $user && $user['ac_status'] == 1) {
    showPage('header', ['page_title' => 'Edit Profile']);
    showPage('navbar');
    showPage('edit_profile');
    showPage('footer');

} elseif (isset($_SESSION['Auth']) && isset($_GET['u']) && $user && $user['ac_status'] == 1) {
    $profile = getUserbyName($_GET['u']);
    if (!$profile) {
        showPage('header', ['page_title' => 'User not found']);
        showPage('navbar');
        showPage('user_not_found');
        showPage('footer');
        exit; 
    } else {
        $profile_post = getPostbyId($profile['id']);
        $profile['followers']=getfollowers($profile['id']);
        $profile['following']=getfollowing($profile['id']);
        showPage('header', ['page_title' => $profile['fname'] . ' ' . $profile['lname']]);
        showPage('navbar');
        showPage('profile');
        showPage('footer');
    }

} elseif (isset($_GET['changepassword'])) {
    showPage('header', ['page_title' => 'Change Password']);
    showPage('forgot_password');
    showPage('footer');

} elseif (isset($_GET['about_us'])) {
    showPage('header', ['page_title' => 'About Us']);
    showPage('navbar');
    showPage('about_us');
    showPage('footer');

}else if ($pageCount === 0) {
    showPage('header', ['page_title' => 'Login']);
    showPage('login');
    showPage('footer');
    exit;
} else {
    if (isset($_SESSION['Auth']) && $user && $user['ac_status'] == 1) {
        showPage('header', ['page_title' => 'Home']);
        showPage('navbar');
        showPage('wall');
        showPage('footer');

    } elseif (isset($_SESSION['Auth']) && $user && $user['ac_status'] == 0) {
        showPage('header', ['page_title' => 'Verify your email']);
        showPage('verify_email');
        showPage('footer');

    } elseif (isset($_SESSION['Auth']) && $user && $user['ac_status'] == 2) {
        showPage('header', ['page_title' => 'Blocked']);
        showPage('blocked');
        showPage('footer');

    } else {
        showPage('header', ['page_title' => 'Login']);
        showPage('login');
        showPage('footer');
    }
}

// Unset session variables
unset($_SESSION['error']);
unset($_SESSION['formdata']);
?>
