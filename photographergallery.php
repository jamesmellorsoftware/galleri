<?php 
require_once("includes/header.php");
require_once("includes/nav_top.php");

// If no photographer selected, send user back to homepage
if (!isset($_GET['id']) || empty($_GET['id'])) header("Location: index.php");

// Retrieve photographer's details
$photographer_user_id = $_GET['id'];
$photographer_details = User::find_by_id($photographer_user_id);
if (!$photographer_details || empty($photographer_details)) header("Location: index.php");

// Retrieve photographer's gallery, adjust for pagination
$pagination_limit = 2;
$show_pagination = false;
$photographer_gallery_photos = Photo::find_photographer_gallery($photographer_user_id, $pagination_limit+1);
if ($photographer_gallery_photos && !empty($photographer_gallery_photos) && count($photographer_gallery_photos) > $pagination_limit) {
    array_pop($photographer_gallery_photos);
    $show_pagination = true;
}

?>

<div class="grid-portfolio noHeaderVideo" id="portfolio">
    <div class="container">
        <div class="col-md-12 text-center">
            <h1>
                <?php echo $photographer_details->user_firstname; ?>
                <em><?php echo $photographer_details->user_lastname; ?></em>
            </h1>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <?php if (!$photographer_gallery_photos || empty($photographer_gallery_photos)) { ?>
                <div class="col-sm-12 text-center"><h2><?php echo PHOTOGRAPHER_NO_PHOTOS; ?></h2></div>
            <?php } else { ?>
                <?php foreach ($photographer_gallery_photos as $photographer_gallery_photo) : ?>
                    <div class="pagination-block">
                        <div class="col-md-4 col-sm-6 portfolio-item thumb">
                            <a class="photo_link" href="photo.php?id=<?php echo $photographer_gallery_photo->photo_id; ?>">
                                <div class="hover-effect">
                                    <div class="hover-content">
                                        <h1 class="photo_title"><?php echo $photographer_gallery_photo->photo_title; ?></h1>
                                        <p>
                                            <span class="photo_subtitle"><?php echo $photographer_gallery_photo->photo_subtitle; ?></span>
                                            <span class="glyphicon glyphicon-thumbs-up"></span>
                                            <span class="photo_like_count"><?php echo Like::count($photographer_gallery_photo->photo_id); ?></span>
                                            <span class="glyphicon glyphicon-comment"></span>
                                            <span class="photo_comment_count"><?php echo Comment::count($photographer_gallery_photo->photo_id); ?></span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                            <div class="image">
                                <img class="photo_image" src="<?php echo $photographer_gallery_photo->photo_path(); ?>">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php } ?>
        </div>
        
        <?php require_once("includes/pagination.php"); ?>
        
    </div>
</div>

<?php require_once("includes/footer.php"); ?>