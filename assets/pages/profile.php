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
                                
                                <!-- Left Side: Post Image -->
                                <div class="col-md-6">
                                    <img src="assets/images/posts/<?=$post['post_img']?>" class="img-fluid rounded-left w-100" alt="Post Image">
                                </div>

                                <!-- Right Side: Comments Section -->
                                <div class="col-md-6 d-flex flex-column">
                                    
                                    <!-- User Info -->
                                    <div class="p-3 border-bottom d-flex align-items-center">
                                        <img src="assets/images/profile/<?=$profile['profile_pic']?>" class="rounded-circle mr-2" width="35" height="35" alt="User">
                                        <div>
                                            <span class="text-secondary" style="font-size: larger;"><?= $profile['fname'] ?> <?= $profile['lname'] ?></span><br>

                                             <span class="text-secondary">@<?= $profile['uname'] ?></span>
                                        </div>
                                    </div>

                                    <!-- Comments Section -->
                                     <div>
                                            <?php 
                                                $comments = getComments($post['id']); // Get comments for post with ID 5

                                                foreach ($comments as $comment) {
                                                    echo "<div class='comment-box'>";
                                                    echo '<img class="rounded-circle mr-2" width="35" height="35" src="assets/images/profile/' . htmlspecialchars($comment['profile_pic']) . '" alt="Profile">';
                                                    echo "<strong>" . htmlspecialchars($comment['fname'] . " " . $comment['lname']) . "</strong> ";
                                                    echo "<span>@".$comment['uname']."</span>";
                                                    echo "<p>" . htmlspecialchars($comment['comment']) . "</p>";
                                                    echo "<small>" . $comment['created_at'] . "</small>";
                                                    echo "</div>";
                                                }
                                            ?>
                                     </div>
                                    


                                    </div>

                                    <!-- Add Comment Box -->
                                    <form class="comment-form" onsubmit="return addComment(event, <?= $post['id'] ?>)">
                                        <input type="text" id="comment_text_<?= $post['id'] ?>" placeholder="Write a comment..." required>
                                        <button type="submit">Comment</button>
                                    </form>
                                    <div id="comments_<?= $post['id'] ?>"></div>


                            </div>
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


