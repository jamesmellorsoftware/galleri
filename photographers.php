<?php 
require_once("includes/header.php");
require_once("includes/nav_top.php");

$pagination_limit = PAGINATION_LIMIT_PHOTOGRAPHERS;
$show_pagination = false;

// Find photographers
$photographers = User::find_photographers($pagination_limit+1);

if ($photographers && !empty($photographers) && count($photographers) > $pagination_limit) {
    array_pop($photographers);
    $show_pagination = true;
}

?>

<div class="grid-portfolio noHeaderVideo" id="portfolio">
    <div class="container">
        <div class="row">
            <?php if (!$photographers || empty($photographers)) { ?>
                <div class="col-sm-12 text-center"><h2><?php echo PHOTOGRAPHERS_NO_PHOTOGRAPHERS; ?></h2></div>
            <?php } else { ?>
                <?php foreach ($photographers as $photographer) : ?>
                    <div class="pagination-block">
                        <div class="col-md-4 col-sm-6 portfolio-item">
                            <div class="thumb">
                                <a class="photographer_link" href="photographergallery.php?id=<?php echo $photographer->user_id; ?>">
                                    <div class="hover-effect">
                                        <div class="hover-content">
                                            <h1 class="photographer_name">
                                                <?php echo $photographer->user_firstname; ?>
                                                <em><?php echo $photographer->user_lastname; ?></em>
                                            </h1>
                                            <p>&nbsp;</p>
                                        </div>
                                    </div>
                                </a>
                                <div class="image">
                                    <img class="photographer_image" src="<?php echo $photographer->get_user_image(); ?>">
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