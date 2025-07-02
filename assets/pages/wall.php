<?php 
    global $user; 
    global $posts;
    global $follow_suggestions;

    

?>
    <div class="container col-9 rounded-0 d-flex justify-content-between">
        <div class="col-8">
            <?php

            showError('post_img');
            
                if(count($posts)<1){
                    echo "<p class='p-2 bg-white border rounded text-center my-3'>Follow someone or add a new post<p>";
                }
                
                foreach ($posts as $post){
                    $likes = getLikes($post['id']);
                    $comments=getComments($post['id']);
                ?>
                <div class="card mt-4">
                    <div class="card-title d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center p-2">
                            <img src="assets/images/profile/<?=$post['profile_pic']?>" alt="" height="30" width="30" class="rounded-circle border">
                            &nbsp;&nbsp;<a href="?u=<?=$post['uname']?>" class="text-decoration-none text-dark"><?=$post['fname']?> <?=$post['lname']?></a>
                        </div>
                        <div class="p-2">
                            <i class="bi bi-three-dots-vertical"></i>
                        </div>
                    </div>
                    <img src="assets/images/posts/<?=$post['post_img']?>" 
                        class="card-img-top" 
                        style="max-height: 500px; width: 100%; object-fit: contain; display: block; margin: 0 auto;" 
                        alt="Post Image">
                    <div class="card-body">
                        <!-- Post Text -->
                        <?php if ($post['post_txt']) { ?>
                        <p class="mb-1"><?=$post['post_txt']?></p>
                        <?php } ?>
                
                        <!-- Address -->
                        <?php if (isset($post['post_address']) && !empty($post['post_address'])) { ?>
                            <p class="text-muted mb-1"><strong>Address: </strong><?= htmlspecialchars($post['post_address']) ?></p>
                        <?php } ?>

                        <!-- Pincode -->
                        <?php if (isset($post['post_pincode']) && !empty($post['post_pincode'])) { ?>
                            <p class="text-muted mb-2"><strong>Pincode: </strong><?= htmlspecialchars($post['post_pincode']) ?></p>
                        <?php } ?>

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
                            <a href="#" data-toggle="modal" data-target="#likes<?=$post['id']?>" class="p-1 " style="text-decoration: none; color: inherit; cursor: pointer;">
                            <?=count($likes)?> 
                        </a>
                        </span>
                        &nbsp;&nbsp;
                        <a href="#" data-toggle="modal" data-target="#comments<?=$post['id']?>" class="p-1 " style="text-decoration: none; color: inherit; cursor: pointer;">
                            <i class="far fa-comment"></i>
                            <?=count($comments)?>
                        </a><br>
                    </h4>
                    <span>
                        
                        
                    </span>

                    
                
                    <div class="input-group p-2 border-top">
                        <form method="post" action="assets/php/actions.php?addComment">
                            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                            <div class="d-flex align-items-center">
                                <textarea class="form-control me-2" name="post_text" rows="1" placeholder="Write a comment..." style="resize: none;"></textarea>
                                <button type="submit" class="btn btn-primary mx-2">Comment</button>
                            </div>
                        </form>
                    </div>
                
                    <div class="modal fade" id="likes<?=$post['id']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Likes</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <?php if (count($likes) < 1) { ?>
                                        <p>No likes yet</p>
                                    <?php } ?>
                                    <?php foreach ($likes as $f) {
                                        $fuser = getUser($f['u_id']);
                                        $fbtn = "";
                
                                        if (checkFollowStatus($f['u_id'])) {
                                            $fbtn = '<button class="btn btn-sm btn-danger unfollowbtn" data-user-id="' . $fuser['id'] . '">Unfollow</button>';
                                        } else if($user['id'] == $f['u_id']) {
                                            $fbtn = '';
                                        } else {
                                            $fbtn = '<button class="btn btn-sm btn-primary followbtn" data-user-id="' . $fuser['id'] . '">Follow</button>';
                                        }                    
                                    ?>
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex align-items-center p-2">
                                            <div>
                                                <img src="assets/images/profile/<?=$fuser['profile_pic']?>" alt="" height="40" width="40" class="rounded-circle border">
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
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="comments<?=$post['id']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Comments</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" style="max-height: 500px;">
                                    <div class="col-md-12 d-flex flex-column">
                                        <!-- Comments Section -->
                                        <div class="flex-grow-1 overflow-auto p-3 border rounded" style="max-height: 400px; overflow-y: auto;">
                                            <?php 
                                                $comments = getComments($post['id']);
                                                if (empty($comments)) {
                                                    echo '<p class="text-center text-muted">No comments yet.</p>';
                                                } else {
                                                    foreach ($comments as $comment) {
                                                        echo '<div class="d-flex mb-3 p-2 bg-white rounded">';
                                                        echo '<img class="rounded-circle me-2" width="35" height="35" src="assets/images/profile/' . htmlspecialchars($comment['profile_pic']) . '" alt="Profile">';
                                                        echo '<div>';
                                                        echo '<a href="?u=' . urlencode($comment['uname']) . '" class="text-decoration-none text-dark">';
                                                        echo '<strong class="text-dark mx-2">' . htmlspecialchars($comment['fname'] . " " . $comment['lname']) . '</strong>';
                                                        echo '<span class="text-muted mx-1">@' . htmlspecialchars($comment['uname']) . '</span>';
                                                        echo '</a>';
                                                        echo '<p class="mb-1 mx-2">' . htmlspecialchars($comment['comment']) . '</p>';
                                                        echo '<small class="text-muted mx-4">' . htmlspecialchars($comment['created_at']) . '</small>';
                                                        echo '</div>';
                                                        echo '</div>';

                                                    }
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
                        </div>
                    </div>



                </div>
                <?php } ?>
                
            

        </div>

        <div class="col-4 mt-4 p-3">
            <div class="d-flex align-items-center p-2">
                <div><img src="assets/images/profile/<?=$user['profile_pic']?>" alt="" height="60" width="60" class="rounded-circle border">
                </div>
                <div>&nbsp;&nbsp;&nbsp;</div>
                <a href="?u=<?=$user['uname']?>" class="text-decoration-none text-dark">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <h6 style="margin: 0px;"><?=$user['fname']?> <?=$user['lname']?></h6></>
                    <p style="margin:0px;" class="text-muted">@<?=$user['uname']?></p>
                </div>
                </a>
            </div>
        <div>
                <h6 class="text-muted p-2">You Can Follow Them</h6>
                <?php
                    foreach($follow_suggestions as $suser){
                ?>
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center p-2">
                        <div><img src="assets/images/profile/<?=$suser['profile_pic']?>" alt="" height="40" width="40" class="rounded-circle border">
                        </div>
                        <div>&nbsp;&nbsp;</div>
                        <a href="?u=<?=$suser['uname']?>" class="text-decoration-none text-dark">
                        <div class="d-flex flex-column justify-content-center">
                            <h6 style="margin: 0px;font-size: small;"><?=$suser['fname']?> <?=$suser['lname']?></h6>
                            <p style="margin:0px;font-size:small" class="text-muted">@<?=$suser['uname']?></p>
                        </div>
                        </a>
                    </div>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-sm btn-primary followbtn" data-user-id="<?=$suser['id']?>">Follow</button>

                    </div>
                </div>
                <?php
                }
                if(count($follow_suggestions)<1){
                    echo "<p class='p-2 bg-white border rounded text-center'> No suggestions for you <p>";
                }
                ?>
                
            </div>
        </div>
    </div>