//for preview the post img

var input=document.querySelector("#select_post_img");

input.addEventListener("change",preview);

function preview(){
    var fileobject = this.files[0];
    var filereader = new FileReader();

    filereader.readAsDataURL(fileobject);

    filereader.onload = function(){
        var img_src = filereader.result;
        var img = document.querySelector("#post_img");
        img.setAttribute('src',img_src);
        img.setAttribute('style','display:');
    }
}

//for follow the user
$(".followbtn").click(function () {
    var u_id_v = $(this).data("user-id");
    var button = $(this);
    $(button).attr('disabled',true);

    $.ajax({
        url: "assets/php/ajax.php?follow",
        method: "POST",
        dataType: "json",
        data: { u_id: u_id_v },
        success: function (response) {
            if (response.status) {
                button.html('<i class="fas fa-check-circle text-white"></i> Following');
                button.removeClass("btn-primary").addClass("bg-primary text-white px-3 py-1 rounded border-0");
            }else{
                $(button).attr('disabled',false);
                alert('something is wrong, try again after sometime');
            }
        }
    });
});

//for unfollow the user
$(".unfollowbtn").click(function () {
    var u_id_v = $(this).data("user-id");
    var button = $(this);
    $(button).attr('disabled',true);

    $.ajax({
        url: "assets/php/ajax.php?unfollow",
        method: "POST",
        dataType: "json",
        data: { u_id: u_id_v },
        success: function (response) {
            if (response.status) {
                button.html('<i class="fas fa-check-circle text-white"></i> Unfollowed');
                button.removeClass("btn-primary").addClass("bg-danger text-white px-3 py-1 rounded border-0");
            }else{
                $(button).attr('disabled',false);
                alert('something is wrong, try again after sometime');
            }
        }
    });
});

//for like the post
$(".like_btn").click(function () {
    var post_id_v = $(this).data("postId");
    var button = $(this);

    $(button).prop("disabled", true);

    $.ajax({
        url: "assets/php/ajax.php?like",
        method: "POST",
        dataType: "json",
        data: { post_id: post_id_v },
        success: function (response) {
            console.log(response);

            if (response.status) {
                $(button).attr("disabled", false);
                $(button).hide();
                $(button).siblings('.unlike_btn').show();
                location.reload();
            } else {
                $(button).attr("disabled", false);
                alert("Something went wrong, please try again later.");
            }
        }
    });
});

// Handle Unlike Button Click
$(".unlike_btn").click(function () {
    var post_id_v = $(this).data("postId");
    var button = $(this);

    $(button).prop("disabled", true);

    $.ajax({
        url: "assets/php/ajax.php?unlike",
        method: "POST",
        dataType: "json",
        data: { post_id: post_id_v },
        success: function (response) {
            console.log(response);

            if (response.status) {
                $(button).attr("disabled", false);
                $(button).hide();
                $(button).siblings('.like_btn').show();
                location.reload();
            } else {
                $(button).attr("disabled", false);
                alert("Something went wrong, please try again later.");
            }
        }
    });
});

//comment
$(document).on("submit", "#commentForm", function (e) {
    e.preventDefault();

    let post_id = $(this).data("post-id");
    let comment_text = $(this).find("textarea").val().trim();

    if (!comment_text) {
        alert("Comment cannot be empty!");
        return;
    }

    $.ajax({
        url: "ajax.php",
        type: "POST",
        data: { post_id: post_id, comment_text: comment_text },
        dataType: "json",
        success: function (response) {
            console.log(response); // Debugging
            if (response.status === "success") {
                alert("Comment added successfully!");
                location.reload();
            } else {
                alert(response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error: ", status, error);
        }
    });
});

//search
$(document).ready(function () {
    $("#pincodeSearchForm").submit(function (event) {
        event.preventDefault(); // Prevent page reload

        var pincode = $("#pincodeInput").val().trim();

        if (pincode.length === 0) {
            $("#searchResults").html('<p class="text-danger">Please enter a pincode.</p>');
            return;
        }

        $.ajax({
            url: "assets/php/ajax.php?searchPincode",
            method: "POST",
            dataType: "json",
            data: { pincode: pincode },
            success: function (response) {
                if (response.status) {
                    var usersHTML = "";
                    $.each(response.users, function (index, user) {
                        usersHTML += `
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center p-2">
                                    <img src="assets/images/profile/${user.profile_pic}" alt="" height="40" width="40" class="rounded-circle border">
                                    <div>&nbsp;&nbsp;</div>
                                    <a href="?u=${user.uname}" class="text-decoration-none text-dark">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 style="margin: 0px; font-size: small;">${user.fname} ${user.lname}</h6>
                                            <p style="margin:0px; font-size:small" class="text-muted">@${user.uname}</p>
                                            <h6 style="margin: 0px; font-size: small; padding: 2px 5px; background-color:rgb(154, 189, 224); display: inline-block;">
                                                ${user.role}
                                            </h6>

                                        </div>
                                    </a>
                                </div>
                            </div>`;
                    });

                    $("#searchResults").html(usersHTML);
                } else {
                    $("#searchResults").html('<p class="text-muted">No users found for this pincode.</p>');
                }

                $("#userSearchModal").modal("show"); // Show modal dynamically
            }
        });
    });
});

//messages|
$(document).on("submit", "#messageForm", function (e) {
    e.preventDefault();

    let receiver_id = $(this).data("receiver-id");
    let message_text = $(this).find("textarea").val().trim();

    if (!message_text) {
        alert("Message cannot be empty!");
        return;
    }

    $.ajax({
        url: "ajax.php",
        type: "POST",
        data: { receiver_id: receiver_id, message_text: message_text },
        dataType: "json",
        success: function (response) {
            console.log(response); // Debugging
            if (response.status === "success") {
                alert("Message sent successfully!");
                location.reload();
            } else {
                alert(response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error: ", status, error);
        }
    });
});




