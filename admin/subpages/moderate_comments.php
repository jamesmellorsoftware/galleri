<?php

if (!$session->user_is_admin()) header("Location: ../index.php");

$pagination_limit = (isset($_POST['results_per_page'])) ? $_POST['results_per_page'] : 2;
$show_pagination = false;

$comments = (isset($_POST['search'])) ? Comment::search($_POST) : Comment::find_all($pagination_limit+1);

if (count($comments) > $pagination_limit) {
    array_pop($comments);
    $show_pagination = true;
}

$bulk_options = Comment::get_bulk_options();

?>

<h1 class="page-title">
    <?php echo MODERATE_COMMENTS_HEADER; ?>
    <i aria-hidden="true" id="" data-toggle="modal" data-target="#searchModal"
    class="fa fa-search pull-right <?php if (isset($_GET['search'])) echo "text-info"; ?>"></i>
</h1>

<?php if (count($comments) == 0 || empty($comments)) { ?>
    <h3><?php echo MODERATE_COMMENTS_NO_RESULTS; ?></h3>
<?php } else { ?>
    <form method="post" action="">
        <?php require_once("includes/bulk_options.php"); ?>
        <table class="table table-bordered table-hover" id="moderate_comments_table">
        <thead></thead>
            <tbody>
                <tr><td class="clickable_td select_all_checkboxes select"><?php echo SELECT_DESELECT_ALL; ?></td></tr>
                <?php foreach ($comments as $comment) { ?>
                    <tr class="pagination-block" href="../photo.php?id=<?php echo $comment->comment_photo_id; ?>">
                        <td class="clickable_td">
                            <div class="col-md-1">
                                <input class='selectCheckbox align-middle' type='checkbox'
                                name='bulk_option_checkboxes[]' value='<?php echo $comment->comment_id; ?>'>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <?php echo MODERATE_COMMENTS_COMMENT; ?> <span class="comment_id"><?php echo $comment->comment_id; ?></span>
                                    <?php echo MODERATE_COMMENTS_ON_PHOTO; ?> <span class="comment_photo_id"><?php echo $comment->comment_photo_id; ?></span>
                                </div>
                                <div class="row">
                                    <?php echo MODERATE_COMMENTS_BY; ?> <span class="comment_author_id"><?php echo User::get_name_from_id($comment->comment_author_id); ?></span>
                                    <?php echo MODERATE_COMMENTS_ON; ?> <span class="comment_date"><?php echo date('jS M Y', strtotime($comment->comment_date)); ?></span>
                                </div>
                                <div class="row">
                                    <span class="comment_approved">
                                        <?php echo ($comment->comment_approved) ? MODERATE_COMMENTS_APPROVED : MODERATE_COMMENTS_UNAPPROVED; ?>
                                    </span>
                                </div>
                                <div class="row">
                                    <span class="glyphicon glyphicon-thumbs-up"></span>
                                    <span class="comment_like_count"><?php echo Comment_Like::count($comment->comment_id); ?></span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <span class="comment_content"><?php echo $comment->comment_content; ?></span>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </form>

    <?php if ($show_pagination) require_once("includes/pagination.php"); ?>
<?php } ?>