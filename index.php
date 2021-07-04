<?php
require_once("includes/header.php");
require_once("includes/nav_top.php");
require_once("includes/index_video.php");

$pagination_limit = PAGINATION_LIMIT_INDEX;
$show_pagination = false;
$gallery_photos = Photo::find_all($pagination_limit+1);
if ($gallery_photos && !empty($gallery_photos) && count($gallery_photos) > $pagination_limit) {
    array_pop($gallery_photos);
    $show_pagination = true;
}

?>

<div class="grid-portfolio" id="portfolio">
    <div class="container">
        <div class="row">
            <?php if (!$gallery_photos || empty($gallery_photos)) { ?>
                <div class="col-sm-12 text-center"><h2><?php echo INDEX_NO_PHOTOS; ?></h2></div>
            <?php } else { ?>
                <?php foreach ($gallery_photos as $gallery_photo) : ?>
                    <div class="pagination-block">
                        <div class="col-md-4 col-sm-6 portfolio-item thumb">
                            <a class="photo_link" href="photo.php?id=<?php echo $gallery_photo->photo_id; ?>">
                                <div class="hover-effect">
                                    <div class="hover-content">
                                        <h1 class="photo_title"><?php echo $gallery_photo->photo_title; ?></h1>
                                        <p>
                                            <span class="photo_subtitle"><?php echo $gallery_photo->photo_subtitle; ?></span>
                                            <span class="glyphicon glyphicon-thumbs-up"></span>
                                            <span class="photo_like_count"><?php echo Like::count($gallery_photo->photo_id); ?></span>
                                            <span class="glyphicon glyphicon-comment"></span>
                                            <span class="photo_comment_count"><?php echo Comment::count($gallery_photo->photo_id); ?></span>
                                            <br>
                                            <span class="photo_author"><?php echo User::get_name_from_id($gallery_photo->photo_author_id); ?></span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                            <div class="image">
                                <img class="photo_image" src="<?php echo $gallery_photo->photo_path(); ?>">
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