<div class="modal-body">
    <div class="form-group col-md-3 col-xs-12">
        <input type="number" class="form-control"
        name="comment_id" placeholder="<?php echo SEARCH_COMMENTS_PLACEHOLDER_ID; ?>"
        maxlength="<?php echo LIMIT_USER_ID; ?>"
        value="<?php if (isset($_POST['comment_id'])) echo $_POST['comment_id']; ?>">
    </div>
    <div class="form-group col-md-3 col-xs-12">
        <input type="number" class="form-control"
        name="comment_photo_id" placeholder="<?php echo SEARCH_COMMENTS_PLACEHOLDER_PHOTO_ID; ?>"
        maxlength="<?php echo LIMIT_USER_ID; ?>"
        value="<?php if (isset($_POST['comment_photo_id'])) echo $_POST['comment_photo_id']; ?>">
    </div>
    <div class="form-group col-md-6 col-xs-12">
        <input type="text" class="form-control"
        name="comment_author" placeholder="<?php echo SEARCH_COMMENTS_PLACEHOLDER_AUTHOR; ?>"
        maxlength="<?php echo LIMIT_USERNAME; ?>"
        value="<?php if (isset($_POST['comment_author'])) echo $_POST['comment_author']; ?>">
    </div>
    <div class="form-group col-md-6 col-xs-12">
        <input type="date" class="form-control"
        name="comment_date_from" placeholder="<?php echo SEARCH_COMMENTS_PLACEHOLDER_DATE_FROM; ?>"
        value="<?php if (isset($_POST['comment_date_from'])) echo $_POST['comment_date_from']; ?>">
    </div>
    <div class="form-group col-md-6 col-xs-12">
        <input type="date" class="form-control"
        name="comment_date_to" placeholder="<?php echo SEARCH_COMMENTS_PLACEHOLDER_DATE_TO; ?>"
        value="<?php if (isset($_POST['comment_date_to'])) echo $_POST['comment_date_to']; ?>">
    </div>
    <div class="form-group col-md-12 col-xs-12">
        <input type="text" class="form-control"
        name="comment_content" placeholder="<?php echo SEARCH_COMMENTS_PLACEHOLDER_CONTENT; ?>"
        maxlength="<?php echo LIMIT_PHOTO_COMMENT; ?>"
        value="<?php if (isset($_POST['comment_content'])) echo $_POST['comment_content']; ?>">
    </div>
    <div class="form-group col-md-6 col-xs-12">
        <input type="checkbox" name="comment_approved[1]"
        <?php if (isset($_POST['comment_approved'][1])) echo "checked"; ?>>
        <label><?php echo SEARCH_COMMENTS_APPROVED; ?></label>
        <input type="checkbox" name="comment_approved[0]"
        <?php if (isset($_POST['comment_approved'][0])) echo "checked"; ?>>
        <label><?php echo SEARCH_COMMENTS_UNAPPROVED; ?></label>
    </div>
    <div class="form-group col-md-6 col-xs-12">
        <?php require_once("search_results_per_page.php"); ?>
    </div>
</div>