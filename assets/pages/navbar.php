<?php global $user; ?>
<nav class="navbar navbar-expand-lg navbar-light bg-white border" style="width:100%;">
    <div class="container-fluid d-flex justify-content-between">
        <div class="d-flex justify-content-between w-75">
            <a class="navbar-brand" href="?">
                <img src="assets/images/abc.jpg" alt="Logo" style="height:45px;">
            </a>
            <!-- 🌟 Search Form -->
            <form id="pincodeSearchForm" class="d-flex">
                <input type="text" id="pincodeInput" name="pincode" class="form-control me-2" placeholder="Enter Pincode">
                <button type="submit" class="btn btn-primary mx-2">Search</button>
            </form>



            <!-- 🌟 Modal (Displays Search Results) -->
            <div class="modal fade" id="userSearchModal" tabindex="-1" role="dialog" aria-labelledby="userSearchModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="userSearchModalLabel">Search Results</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="searchResults">
                            <p class="text-muted">Enter a pincode to search for users.</p>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <ul class="navbar-nav mb-2 mb-lg-0">
            <!-- Home -->
            <li class="nav-item mx-2">
                <a class="nav-link" href="?">
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