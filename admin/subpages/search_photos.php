<div class="modal-body">
    <div class="form-group col-md-9 col-xs-12">
        <input type="text" class="form-control"
        name="photo_author" placeholder="<?php echo SEARCH_PHOTOS_PLACEHOLDER_AUTHOR; ?>"
        maxlength="<?php echo LIMIT_USERNAME; ?>"
        value="<?php if (isset($_POST['photo_author'])) echo $_POST['photo_author']; ?>">
    </div>
    <div class="form-group col-md-3 col-xs-12">
        <input type="number" class="form-control"
        name="photo_id" placeholder="<?php echo SEARCH_PHOTOS_PLACEHOLDER_ID; ?>"
        maxlength="<?php echo LIMIT_USER_ID; ?>"
        value="<?php if (isset($_POST['photo_id'])) echo $_POST['photo_id']; ?>">
    </div>
    <div class="form-group col-md-6 col-xs-12">
        <input type="text" class="form-control"
        name="photo_title" placeholder="<?php echo SEARCH_PHOTOS_PLACEHOLDER_TITLE; ?>"
        maxlength="<?php echo LIMIT_PHOTO_TITLE; ?>"
        value="<?php if (isset($_POST['photo_title'])) echo $_POST['photo_title']; ?>">
    </div>
    <div class="form-group col-md-6 col-xs-12">
        <input type="text" class="form-control"
        name="photo_subtitle" placeholder="<?php echo SEARCH_PHOTOS_PLACEHOLDER_SUBTITLE; ?>"
        maxlength="<?php echo LIMIT_PHOTO_SUBTITLE; ?>"
        value="<?php if (isset($_POST['photo_subtitle'])) echo $_POST['photo_subtitle']; ?>">
    </div>
    <div class="form-group col-md-12 col-xs-12">
        <input type="text" class="form-control"
        name="photo_text" placeholder="<?php echo SEARCH_PHOTOS_PLACEHOLDER_CONTENT; ?>"
        maxlength="<?php echo LIMIT_PHOTO_POST; ?>"
        value="<?php if (isset($_POST['photo_text'])) echo $_POST['photo_text']; ?>">
    </div>
    <div class="form-group col-md-4 col-xs-12">
        <input type="date" class="form-control"
        name="photo_date_from" placeholder="<?php echo SEARCH_PHOTOS_PLACEHOLDER_DATE_FROM; ?>"
        value="<?php if (isset($_POST['photo_date_from'])) echo $_POST['photo_date_from']; ?>">
    </div>
    <div class="form-group col-md-4 col-xs-12">
        <input type="date" class="form-control"
        name="photo_date_to" placeholder="<?php echo SEARCH_PHOTOS_PLACEHOLDER_DATE_TO; ?>"
        value="<?php if (isset($_POST['photo_date_to'])) echo $_POST['photo_date_to']; ?>">
    </div>
    <div class="form-group col-md-4 col-xs-12">
        <?php require_once("search_results_per_page.php"); ?>
    </div>
</div>