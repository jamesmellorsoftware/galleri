<?php
require_once("includes/header.php");
require_once("includes/nav_top.php");

// If no photo is selected, send user back to homepage
if (!isset($_GET['id']) || empty($_GET['id'])) header("Location: index.php");

// Retrieve photo
$photo_id = $_GET['id'];
$photo = Photo::find_by_id($photo_id);
if (!$photo) header("Location: index.php");

// Retrieve comments
$comments = Comment::retrieve($photo->photo_id);

// ----------------- COMMENT SUBMIT -----------------
$comment_successful = false;

$comment_errors = [];

$comment_values = [
    "comment_content"
];


if (isset($_POST['submit'])) {
    if ($session->is_signed_in()) {
        $comment_values['comment_content'] = trim($_POST['comment_content']);
        $comment_author = $session->user_id;
        $comment_date = date("Y-m-d");

        $comment_errors = Comment::verify_comment($comment_values);

        if (!Comment::errors_in_form($comment_errors)) {
            $new_comment = Comment::create_comment($photo_id, $comment_author, $comment_values['comment_content'], $comment_date);
            if ($new_comment && $new_comment->create()) {
                header("Location: photo.php?id={$photo_id}");
            } else {
                $comment_errors['comment_content'] = "Problem saving comment.";
            }
        }
    } else {
        $comment_errors['comment_content'] = "You must be signed in to comment!";
    }
}
// --------------------------------------------------

// User liked the post?
$user_liked = ($session->is_signed_in() && Like::user_liked_photo($photo_id, $session->user_id)) ? true : false;


// ----------------- LIKE SUBMIT --------------------
// Post liked, insert like
if ($session->is_signed_in() && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case "liked":
            if ($user_liked) break;
            if ($new_like = Like::create_like($photo_id, $session->user_id)) $new_like->create();
            break;
        case "unliked":
            if (!$user_liked) break;
            Like::delete_like($photo_id, $session->user_id);
            break;
    }
}

// --------------------------------------------------


// ----------------- COMMENT LIKE SUBMIT --------------------
if ($session->is_signed_in() && isset($_POST['action']) && isset($_POST['liked_comment_id'])) {
    switch ($_POST['action']) {
        case "liked":
            // if ($user_liked) break; // check if user has already liked comment
            if ($new_like = Comment_Like::create_like($_POST['liked_comment_id'], $session->user_id)) $new_like->create();
            break;
        case "unliked":
            // if (!$user_liked) break; // check if user hasn't already liked comment
            Comment_Like::delete_like($_POST['liked_comment_id'], $session->user_id);
            break;
    }
}

// --------------------------------------------------
?>

<div class="blog-entries">
    <div class="container noHeaderVideo">
        <div class="row">
            <div class="col-md-12 single-blog-post">
                <img src="img/photos/<?php echo $photo->photo_filename; ?>" alt="">
                <div class="text-content">
                    <h2>
                        <?php echo $photo->photo_title; ?>
                        <em><?php echo $photo->photo_subtitle; ?></em>
                    </h2>
                    <h3>
                        by <a href="photographergallery.php?id=<?php echo $photo->photo_author_id; ?>">
                            <?php echo User::get_name_from_id($photo->photo_author_id); ?>
                        </a>
                    </h3>
                    <span><?php echo date('jS M Y', strtotime($photo->photo_date)); ?></span>
                    <p><?php echo $photo->photo_text; ?></p>

                    <div class="row">
                        <span style="width: 15px;" id="like_thumb"
                        class="like_thumb glyphicon glyphicon-thumbs-up <?php if ($user_liked) echo "liked"; ?>"></span>
                        <span id="photo_likes"><?php echo Like::count($photo_id); ?></span>
                    </div>

                    <?php require_once("includes/photo_comments_form.php"); ?>
                    <?php require_once("includes/photo_comments.php"); ?>
                    
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    // Photo liked
    $(document).ready(function(){
        var photo_id = <?php echo $photo_id; ?>;
        var num_likes = <?php echo Like::count($photo_id); ?>;
        var isLoggedIn = <?php echo ($session->is_signed_in()) ? "true" : "false"; ?>;

        // Post liked
        // If not logged in, redirect to login page
        // Else increment or decrement likes
        // Send request via POST AJAX and change thumb properties when complete
        $("#like_thumb").click(function(){
            if (!isLoggedIn) {
                window.location = "login.php";
                return;
            }

            if ($(this).hasClass("liked")) {
                var action = "unliked";
                num_likes--;
            } else {
                var action = "liked";
                num_likes++;
            }

            $.ajax({
                url: "photo.php?id=" + photo_id,
                type: "post",
                data: { "action": action }
            }).done(function(){
                $("#like_thumb").toggleClass("liked");
                $("#photo_likes").html(num_likes);
            });

        });

        // Comment liked
        // If not logged in, redirect to login page
        // Else increment or decrement likes
        // Send request via POST AJAX and change thumb properties when complete
        
        $(".comment_like_thumb").click(function(){
            if (!isLoggedIn) {
                window.location = "login.php";
                return;
            }

            var photo_id = <?php echo $photo_id; ?>;
            var clicked_comment_thumb = $(this);
            var liked_comment_id = $(clicked_comment_thumb).attr('rel');
            var num_likes = $(clicked_comment_thumb).siblings(".comment_likes").html();

            if ($(clicked_comment_thumb).hasClass("liked")) {
                var action = "unliked";
                num_likes--;
            } else {
                var action = "liked";
                num_likes++;
            }

            $.ajax({
                url: "photo.php?id=" + photo_id,
                type: "post",
                data: {
                    "action": action,
                    "liked_comment_id": liked_comment_id,
                    "num_likes": num_likes
                }
            }).done(function(){
                $(clicked_comment_thumb).toggleClass("liked");
                $(clicked_comment_thumb).siblings(".comment_likes").html(num_likes);
            });

        });
    });
</script>



<?php require_once("includes/footer.php"); ?>