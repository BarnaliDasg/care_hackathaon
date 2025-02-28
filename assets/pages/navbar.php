<?php global $user; ?>
<nav class="navbar navbar-expand-lg navbar-light bg-white border" style="width:100%;">
    <div class="container-fluid d-flex justify-content-between">
        <div class="d-flex justify-content-between w-75">
            <a class="navbar-brand" href="?">
                <img src="assets/images/abc.jpg" alt="Logo" style="height:45px;">
            </a>
            <form class="d-flex w-50">
                <input class="form-control" type="search" placeholder="Looking for someone..." aria-label="Search">
            </form>
        </div>
        <ul class="navbar-nav mb-2 mb-lg-0">
            <!-- Home -->
            <li class="nav-item mx-2">
                <a class="nav-link" href="?home" aria-label="Home">
                    <i class="fas fa-home fa-2x"></i>
                </a>
            </li>

            <!-- Add Post Icon -->
            <li class="nav-item mx-2">
                <a class="nav-link" href="#" id="Modal" data-toggle="modal" data-target="#exampleModal" aria-label="addpost">
                    <i class="fas fa-plus-square fa-2x"></i>
                </a>
            </li>

            <!-- About us -->
            <li class="nav-item mx-2">
                <a class="nav-link" href="?about_us" aria-label="About">
                <i class="fas fa-info-circle fa-2x"></i>
                </a>
            </li>


            <!-- User Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-dark" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="assets/images/profile/<?=$user['profile_pic']?>" alt="Profile" height="40" width="40" class="rounded-circle border">
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="?edit_profile">Edit Profile</a>
                    <a class="dropdown-item" href="#">Account Settings</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="assets/php/actions.php?logout">Logout</a>
                </div>
            </li>
        </ul>
    </div>
</nav>