<?php

if (!isset($_GET['id'])) header("Location: index.php");

$photo = Photo::find_by_id($db->connection->real_escape_string($_GET['id']));

if (!$photo) header("Location: index.php");

if (!$session->user_is_admin() && $session->user_id != $photo->photo_author_id) header("Location: index.php");

$edit_photo_successful = false;

$edit_photo_errors = [];

if (isset($_POST['submit'])) {
    // Retrieve old photo properties for correct display later
    $photo_id = $photo->photo_id;
    $photo_filename = $photo->photo_filename;

    $photo = new Photo;
    $photo->photo_id        = $photo_id;
    $photo->photo_filename  = $photo_filename;
    $photo->photo_title     = $db->connection->real_escape_string($_POST['photo_title']);
    $photo->photo_subtitle  = $db->connection->real_escape_string($_POST['photo_subtitle']);
    $photo->photo_text      = $db->connection->real_escape_string($_POST['photo_text']);

    $edit_photo_errors = $photo->verify();

    if (!Photo::errors_in_form($edit_photo_errors)) {
        if ($photo->save()) {
            $edit_photo_successful = true;
        } else {
            $edit_photo_errors["file_upload"] = join("<br>", $photo->errors);
        }
    }
}

?>

<div class="">
    <h1 class="page-title"><?php echo EDIT_PHOTO_HEADER . " " . $photo->photo_id; ?></h1>
    <div class="row">
        <?php if ($edit_photo_successful) { ?>
            <div class="col-xs-12">
                <h4 class="bg-success success"><?php echo EDIT_PHOTO_SUCCESS; ?></h4>
            </div>
        <?php } else { ?>
            <?php foreach ($edit_photo_errors as $edit_photo_error) { ?>
                <div class="col-xs-12">
                    <h4 class="bg-danger error"><?php echo $edit_photo_error; ?></h4>
                </div>
            <?php } ?>
        <?php } ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group col-md-12">
                <img class="photo_image" width="500" height="auto"
                src="<?php echo "../" . $photo->photo_path(); ?>">
            </div>
            <div class="form-group col-xs-12">
                <input type="text"
                class="form-control <?php if (isset($edit_photo_errors['title'])) echo "is-invalid"; ?>"
                name="photo_title" placeholder="<?php echo EDIT_PHOTO_PLACEHOLDER_TITLE . "*"; ?>"
                value="<?php if (!empty($photo->photo_title)) echo $photo->photo_title; ?>">
            </div>
            <div class="form-group col-xs-12">
                <input type="text"
                class="form-control <?php if (isset($edit_photo_errors['subtitle'])) echo "is-invalid"; ?>"
                name="photo_subtitle" placeholder="<?php echo EDIT_PHOTO_PLACEHOLDER_SUBTITLE . "*"; ?>"
                value="<?php if (!empty($photo->photo_subtitle)) echo $photo->photo_subtitle; ?>">
            </div>
            <div class="form-group col-xs-12">
                <textarea
                class="form-control <?php if (isset($edit_photo_errors['text'])) echo "is-invalid"; ?>"
                placeholder="<?php echo EDIT_PHOTO_PLACEHOLDER_POST . "*"; ?>"
                name="photo_text"><?php if (!empty($photo->photo_text)) echo $photo->photo_text; ?></textarea>
            </div>
            <div class="form-group col-xs-12">
                <input type="submit" class="btn btn-primary btn-fullwidth" name="submit" value="<?php echo EDIT_PHOTO_BUTTON; ?>">
            </div>
        </form>
    <div>
</div>
