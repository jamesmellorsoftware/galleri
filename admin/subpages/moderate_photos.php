<?php

$pagination_limit = (isset($_POST['results_per_page'])) ? $_POST['results_per_page'] : 2;
$show_pagination = false;

if ($session->user_is_photographer()) {
    $photos = (isset($_POST['search'])) ? Photo::search_photographer($session->user_id, $_POST) : Photo::find_photographer_gallery($session->user_id, $pagination_limit+1);
}
if ($session->user_is_admin()) {
    $photos = (isset($_POST['search'])) ? Photo::search($_POST) : Photo::find_all($pagination_limit+1);
}

if ($photos && count($photos) > $pagination_limit) {
    array_pop($photos);
    $show_pagination = true;
}

$bulk_options = Photo::get_bulk_options();

?>

<h1 class="page-title">
    Moderate: Photos
    <i aria-hidden="true" id="" data-toggle="modal" data-target="#searchModal"
    class="fa fa-search pull-right <?php if (isset($_GET['search'])) echo "text-info"; ?>"></i>
</h1>

<?php if (!$photos || count($photos) == 0 || empty($photos)) { ?>
    <h3>No posts. Clear search or add a post.</h3>
<?php } else { ?>
    <form method="post" action="">
        <?php require_once("includes/bulk_options.php"); ?>

        <table class="table table-bordered table-hover" id="moderate_photos_table">
            <thead></thead>    
            <tbody>
                <tr><td class="clickable_td select_all_checkboxes select">Select / Deselect All</td></tr>
                <?php foreach ($photos as $photo) { ?>
                    <tr class="pagination-block">
                        <td class="clickable_td">
                            <div class="col-md-1">
                                <input class='selectCheckbox align-middle' type='checkbox'
                                name='bulk_option_checkboxes[]' value='<?php echo $photo->photo_id; ?>'>
                            </div>
                            <div class="col-md-3">
                                <div class="row ">
                                    Photo <span class="photo_id"><?php echo $photo->photo_id; ?></span>
                                    by <span class="photo_author"><?php echo User::get_name_from_id($photo->photo_author_id); ?></span>
                                </div>
                                <div class="row">
                                    <span class="photo_date"><?php echo date('jS M Y', strtotime($photo->photo_date)); ?></span>
                                </div>
                                <div class="row">
                                    <span class="photo_title"><?php echo $photo->photo_title; ?></span>
                                    -
                                    <span class="photo_subtitle"><?php echo $photo->photo_subtitle; ?></span>
                                </div>
                                <div class="row">
                                    <span class="glyphicon glyphicon-thumbs-up"></span>
                                    <span class="photo_like_count"><?php echo Like::count($photo->photo_id); ?></span>
                                    <span class="glyphicon glyphicon-comment"></span>
                                    <span class="photo_comment_count"><?php echo Comment::count($photo->photo_id); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <span class="photo_text"><?php echo $photo->photo_text; ?></span>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <img class="photo_image" width="200" height="auto"
                                    src="<?php echo "../" . $photo->photo_path(); ?>">
                                </div>
                                <div class="row">
                                    <span class="photo_filename"><?php echo $photo->photo_filename; ?></span>
                                </div>
                                <div class="row">
                                    <a class="photo_edit_link"
                                    href="edit.php?action=edit_photo&id=<?php echo $photo->photo_id ?>">
                                        Edit
                                    </a>
                                    <a class="photo_view_link"
                                    href="../photo.php?id=<?php echo $photo->photo_id ?>">
                                        View
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </form>

    <?php if ($show_pagination) require_once("includes/pagination.php"); ?>
<?php } ?>