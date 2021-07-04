<?php 
require_once("includes/header.php");
require_once("includes/nav_top.php");

if (!$session->is_signed_in()) header("Location: index.php");

$pagination_limit = 2;
$show_pagination = false;

$liked_photos = Photo::find_user_likes($session->user_id, $pagination_limit+1);

if ($liked_photos && !empty($liked_photos) && count($liked_photos) > $pagination_limit) {
    array_pop($liked_photos);
    $show_pagination = true;
}

?>

<div class="grid-portfolio noHeaderVideo" id="portfolio">
    <div class="container">
        <div class="col-md-12 text-center">
            <h1><?php echo LIKED_HEADER; ?></h1>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <?php if (!$liked_photos || empty($liked_photos)) { ?>
                <div class="col-sm-12 text-center"><h2><?php echo LIKED_NO_PHOTOS; ?></h2></div>
            <?php } else { ?>
                <?php foreach ($liked_photos as $liked_photo) : ?>
                    <div class="pagination-block">
                        <div class="col-md-4 col-sm-6 portfolio-item">
                            <div class="thumb">
                                <a class="photo_link" href="photo.php?id=<?php echo $liked_photo->photo_id; ?>"><div class="hover-effect">
                                    <div class="hover-content">
                                        <h1 class="photo_title"><?php echo $liked_photo->photo_title; ?></h1>
                                        <p>
                                            <span class="photo_subtitle"><?php echo $liked_photo->photo_subtitle; ?></span>
                                            <span class="glyphicon glyphicon-thumbs-up"></span>
                                            <span class="photo_like_count"><?php echo Like::count($liked_photo->photo_id); ?></span>
                                            <span class="glyphicon glyphicon-comment"></span>
                                            <span class="photo_comment_count"><?php echo Comment::count($liked_photo->photo_id); ?></span>
                                            <br><?php echo User::get_name_from_id($liked_photo->photo_author_id); ?>
                                        </p>
                                    </div>
                                </div></a>
                                <div class="image">
                                    <img class="photo_image" src="<?php echo $liked_photo->photo_path(); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php } ?>
        </div>

        <?php if ($show_pagination) require_once("includes/pagination.php"); ?>
        
    </div>
</div>


<?php require_once("includes/footer.php"); ?>