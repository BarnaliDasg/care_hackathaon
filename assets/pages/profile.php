<?php 
global $profile;
global $profile_post;
global $user;
?>
<div class="container col-9 rounded-0">
    <div class="col-12 rounded p-4 mt-4 d-flex gap-5">
        <div class="col-4 d-flex justify-content-end align-items-start">
            <img src="assets/images/profile/<?=$profile['profile_pic']?>" class="img-thumbnail rounded-circle my-3" style="height: 170px;; width:170;" alt="Profile Picture">

        </div>
        <div class="col-8">
            <div class="d-flex flex-column">
                <div class="d-flex gap-3 align-items-center">
                    <span style="font-size: xx-large;"><?=$profile['fname']?> <?=$profile['lname']?></span>
                    
                    <span style="font-size: large; " class="mx-3 badge bg-secondary">
                        <?= ($profile['role'] == 'caregiver') ? 'Caregiver' : 'Care Seeker'; ?>
                    </span>
            </div>

                    <div style="width:20px"></div>
                    <?php if ($user['id'] != $profile['id']) { ?>
                    <!-- Dropdown for three dots -->
                    <div class="dropdown">
                        <span class="dropdown-toggle" style="cursor: pointer; font-size: xx-large;" 
                            type="button" id="dropdownMenuButton1"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-h"></i>  <!-- Three dots -->
                        </span>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <a class="dropdown-item" href="#"><i class="fas fa-comment"></i> Message</a>
                            <a class="dropdown-item text-danger" href="#"><i class="fas fa-ban"></i> Block</a>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <span style="font-size: larger;" class="text-secondary">@<?=$profile['uname']?></span>
                <div class="d-flex gap-2 align-items-center my-3">
                    <a class="btn btn-sm btn-primary"><i class="fas fa-file-alt"></i> <?=count($profile_post)?> posts</a>
                    <div style="width:10px"></div>
                    <a class="btn btn-sm btn-primary"data-toggle='modal' data-target="#followerlist"><i class="fas fa-users"></i> <?=count($profile['followers'])?> followers</a>
                    <div style="width:10px"></div>
                    <a class="btn btn-sm btn-primary"data-toggle='modal' data-target="#followings"><i class="fas fa-user"></i> <?=count($profile['following'])?> following</a>
                </div>

                <?php if ($user['id'] != $profile['id']) { ?>
                <div class="d-flex gap-2 align-items-center my-1">
                    <?php if(checkFollowStatus($profile['id'])){ ?>
                        <button class="btn btn-sm btn-danger Unfollowbtn" data-user-id="<?=$profile['id']?>">Unfollow</button>
                    <?php }else{ ?>
                        <button class="btn btn-sm btn-primary followbtn" data-user-id="<?=$profile['id']?>">Follow</button>
                    <?php } ?>
                </div>
                <?php } ?>

            </div>
        </div>
    </div>

    <div style="width: 95%; margin: auto;">
    <h3 class="border-bottom">Posts</h3>
    <?php if (count($profile_post) < 1): ?>
        <p class="p-2 bg-white border rounded text-center">User doesn't have any posts</p>
    <?php endif; ?>

    <div class="gallery d-flex flex-wrap justify-content-center gap-2 mb-4">
        <?php foreach ($profile_post as $post){ ?>
            <img style="padding: 5px; border-radius: 10px;" 
                src="assets/images/posts/<?= $post['post_img'] ?>" 
                data-toggle="modal" data-target="#postview<?= $post['id'] ?>" 
                width="30%" class="rounded shadow-sm" />

            <!-- Modal for Post View -->
            <div class="modal fade" id="postview<?= $post['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content border-0 rounded-lg">
                        <div class="modal-body p-0">
                            <div class="row no-gutters">
                                
                            <div class="container mt-4">
                                <div class="row">
                                    <!-- Left Side: Image & Post Details -->
                                    <div class="col-md-6">
                                        <img src="assets/images/posts/<?=$post['post_img']?>" class="img-fluid rounded" alt="Post Image" style="height: 500px;">
                                        
                                        <!-- Post Details -->
                                        <div class="mt-3 p-3 border rounded bg-light">
                                            <p><strong>Address:</strong> <?= htmlspecialchars($post['post_address']) ?></p>
                                            <p><strong>Pincode:</strong> <?= htmlspecialchars($post['post_pincode']) ?></p>
                                            <p><strong>Post:</strong> <?= htmlspecialchars($post['post_txt']) ?></p>
                                        </div>

                                        <h4 style="font-size: x-larger" class="p-2 border-bottom">
                                            <span>
                                                <?php
                                                    if(checklikeStatus($post['id'])){
                                                        $like_btn_display = 'none';
                                                        $unlike_btn_display = '';
                                                    } else {
                                                        $unlike_btn_display = 'none';
                                                        $like_btn_display = '';
                                                    }
                                                ?>
                                                <i class="fa fa-heart unlike_btn" style="display:<?=$unlike_btn_display?>" data-post-id="<?=$post['id']?>"></i>
                                                <i class="far fa-heart like_btn" style="display:<?=$like_btn_display?>" data-post-id="<?=$post['id']?>"></i>
                                                <?php 
                                                $likes = getLikes($post['id']);
                                                echo count($likes)?>
                                            </span>
                                            &nbsp;&nbsp;<i class="far fa-comment"></i><br>
                                        </h4>

                                    </div>

                                    <!-- Right Side: Comments Section -->
                                    <div class="col-md-6 d-flex flex-column">
                                        <!-- Post Author -->
                                        <div class="p-3 border-bottom d-flex align-items-center bg-white rounded mx-1 px-3">
                                            <img src="assets/images/profile/<?=$profile['profile_pic']?>" class="rounded-circle me-2" width="40" height="40" alt="User">
                                        <div>

                                                <span class="fw-bold mx-2"><b><?= $profile['fname'] ?> <?= $profile['lname'] ?></b></span><br>
                                                <small class="text-muted mx-3">@<?= $profile['uname'] ?></small>
                                            </div>
                                        </div>

                                        <!-- Comments Section -->
                                        <div class="flex-grow-1 overflow-auto p-3 border rounded" style="height: 600px;">
                                            <?php 
                                                $comments = getComments($post['id']);
                                                foreach ($comments as $comment) {
                                                    echo '<div class="d-flex mb-3 p-2 bg-white rounded">';
                                                    echo '<img class="rounded-circle me-2" width="35" height="35" src="assets/images/profile/' . htmlspecialchars($comment['profile_pic']) . '" alt="Profile">';
                                                    echo '<div>';
                                                    echo '<strong class="text-dark mx-2">' . htmlspecialchars($comment['fname'] . " " . $comment['lname']) . '</strong> ';
                                                    echo '<span class="text-muted mx-1">@'.$comment['uname'].'</span>';
                                                    echo '<p class="mb-1 mx-2">' . htmlspecialchars($comment['comment']) . '</p>';
                                                    echo '<small class="text-muted mx-4">' . $comment['created_at'] . '</small>';
                                                    echo '</div>';
                                                    echo '</div>';
                                                }
                                            ?>
                                        </div>

                                        <!-- Add Comment Section -->
                                        <div class="p-3 border-top bg-white rounded">
                                            <form method="post" action="assets/php/actions.php?addComment">
                                                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                                <div class="d-flex align-items-center">
                                                    <textarea class="form-control me-2" name="post_text" rows="1" placeholder="Write a comment..." style="resize: none;"></textarea>
                                                    <button type="submit" class="btn btn-primary mx-2">Comment</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                </div> <!-- Row Ends -->
                        </div>
                    </div>
                </div>
            </div>



        
            <?php } ?>


    </div>
</div>
<!-- Remove the dropdown arrow -->
<style>
    .dropdown-toggle::after {
        display: none !important;
    }
</style>

<!-- this is for follower list -->
<div class="modal fade" id="followerlist" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Followers</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <!-- Fixed for Bootstrap 4 -->
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                foreach($profile['followers'] as $f){
                    $fuser = getUser($f['follower_id']);
                    $fbtn = "";
                    
                    if (checkFollowStatus($f['follower_id'])) {
                        $fbtn = '<button class="btn btn-sm btn-danger unfollowbtn" data-user-id="' . $fuser['id'] . '">Unfollow</button>';
                    } else if($user['id']==$f['follower_id']){
                        $fbtn = '';
                    }else {
                        $fbtn = '<button class="btn btn-sm btn-primary followbtn" data-user-id="' . $fuser['id'] . '">Follow</button>';
                    }                    
                ?>
                    <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center p-2">
                        <div><img src="assets/images/profile/<?=$fuser['profile_pic']?>" alt="" height="40" width="40" class="rounded-circle border">
                        </div>
                        <div>&nbsp;&nbsp;</div>
                        <a href="?u=<?=$fuser['uname']?>" class="text-decoration-none text-dark">
                        <div class="d-flex flex-column justify-content-center">
                            <h6 style="margin: 0px;font-size: small;"><?=$fuser['fname']?> <?=$fuser['lname']?></h6>
                            <p style="margin:0px;font-size:small" class="text-muted">@<?=$fuser['uname']?></p>
                        </div>
                        </a>
                    </div>
                    <div class="d-flex align-items-center">
                        <?=$fbtn?>

                    </div>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
  </div>
</div>

<!-- this is for following list -->
<div class="modal fade" id="followings" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Followings</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <!-- Fixed for Bootstrap 4 -->
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                foreach($profile['following'] as $f){
                    $fuser = getUser($f['u_id']);
                    $fbtn = "";
                    
                    if (checkFollowStatus($f['u_id'])) {
                        $fbtn = '<button class="btn btn-sm btn-danger unfollowbtn" data-user-id="' . $fuser['id'] . '">Unfollow</button>';
                    } else if($user['id']==$f['u_id']){
                        $fbtn = '';
                    }else {
                        $fbtn = '<button class="btn btn-sm btn-primary followbtn" data-user-id="' . $fuser['id'] . '">Follow</button>';
                    }                    
                ?>
                    <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center p-2">
                        <div><img src="assets/images/profile/<?=$fuser['profile_pic']?>" alt="" height="40" width="40" class="rounded-circle border">
                        </div>
                        <div>&nbsp;&nbsp;</div>
                        <a href="?u=<?=$fuser['uname']?>" class="text-decoration-none text-dark">
                        <div class="d-flex flex-column justify-content-center">
                            <h6 style="margin: 0px;font-size: small;"><?=$fuser['fname']?> <?=$fuser['lname']?></h6>
                            <p style="margin:0px;font-size:small" class="text-muted">@<?=$fuser['uname']?></p>
                        </div>
                        </a>
                    </div>
                    <div class="d-flex align-items-center">
                        <?=$fbtn?>

                    </div>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
  </div>
</div>


