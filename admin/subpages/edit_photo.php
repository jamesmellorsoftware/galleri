<?php

if (!isset($_GET['id'])) header("Location: index.php");

$photo = Photo::find_by_id($_GET['id']);

if (!$photo) header("Location: index.php");

$edit_photo_successful = false;

$edit_photo_errors = [];

if (isset($_POST['submit'])) {
    // Retrieve old photo properties for correct display later
    $photo_id = $photo->photo_id;
    $photo_filename = $photo->photo_filename;

    $photo = new Photo;
    $photo->photo_id = $photo_id;
    $photo->photo_filename = $photo_filename;
    $photo->photo_title     = $_POST['photo_title'];
    $photo->photo_subtitle  = $_POST['photo_subtitle'];
    $photo->photo_text      = $_POST['photo_text'];

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
    <h1 class="page-title">Edit Photo: <?php echo $photo->photo_id; ?></h1>
    <div class="row">
        <?php if ($edit_photo_successful) { ?>
            <h4 class="bg-success">Photo edited successfully.</h4>
        <?php } else { ?>
            <?php foreach ($edit_photo_errors as $edit_photo_error) { ?>
                <h4 class="bg-danger"><?php echo $edit_photo_error; ?></h4>
            <?php } ?>
        <?php } ?>
        <form method="post" enctype="multipart/form-data">
            <div class="col-md-12">
                <img class="photo_image" width="500" height="auto"
                src="<?php echo "../" . $photo->photo_path(); ?>">
            </div>
            <div class="form-group">
                <input type="text" class="form-control"
                name="photo_title" placeholder="Title"
                value="<?php if (!empty($photo->photo_title)) echo $photo->photo_title; ?>">
            </div>
            <div class="form-group">
                <input type="text" class="form-control"
                name="photo_subtitle" placeholder="Subtitle"
                value="<?php if (!empty($photo->photo_subtitle)) echo $photo->photo_subtitle; ?>">
            </div>
            <div class="form-group">
                <textarea class="form-control"
                name="photo_text" placeholder="Post"><?php if (!empty($photo->photo_text)) echo $photo->photo_text; ?></textarea>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" name="submit" value="Submit Changes">
            </div>
        </form>
    <div>
</div>
