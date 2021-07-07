<?php
include "includes/head.php";

$upload_successful = false;

$upload_errors = [];

if (isset($_POST['submit'])) {
    // Add upload image to correct folder and add entry to DB

    $photo = new Photo;
    $photo->photo_title     = $_POST['photo_title'];
    $photo->photo_subtitle  = $_POST['photo_subtitle'];
    $photo->photo_text      = $_POST['photo_text'];
    $photo->photo_date      = date("Y-m-d");
    $photo->photo_author_id = $session->user_id;

    $upload_errors = $photo->verify();

    if ($photo->set_file($_FILES['file_upload'])) {
        if ($photo->save()) {
            $upload_successful = true;
        } else {
            $upload_errors["file_upload"] = join("<br>", $photo->errors);
        }
    } else {
        $upload_errors["file_upload"] = join("<br>", $photo->errors);
    }
}

?>

<div class="">
    <h1 class="page-title"><?php echo UPLOAD_HEADER; ?></h1>
    <div class="row">
        <?php if ($upload_successful) { ?>
            <div class="col-xs-12">
                <h4 class="bg-success success"><?php echo UPLOAD_SUCCESS; ?></h4>
            </div>
        <?php } else { ?>
            <?php foreach ($upload_errors as $upload_error) { ?>
                <div class="col-xs-12">
                    <h4 class="bg-danger error"><?php echo $upload_error; ?></h4>
                </div>
            <?php } ?>

            <form method="post" action="upload.php" enctype="multipart/form-data">
                <div class="form-group col-xs-12">
                    <label for="file_upload" class="<?php if (isset($upload_errors['file_upload'])) echo "text-danger" ?>">
                        <?php echo UPLOAD_FILE_LABEL . "*"; ?>
                    </label>
                    <input type="file" name="file_upload" class="">
                </div>
                <div class="form-group col-xs-12">
                    <input type="text"
                    class="form-control <?php if (isset($upload_errors['title'])) echo "is-invalid"; ?>"
                    name="photo_title" placeholder="<?php echo UPLOAD_PLACEHOLDER_TITLE . "*"; ?>"
                    maxlength="<?php echo LIMIT_PHOTO_TITLE; ?>"
                    value="<?php if (!empty($photo->photo_title)) echo $photo->photo_title; ?>">
                </div>
                <div class="form-group col-xs-12">
                    <input type="text"
                    class="form-control <?php if (isset($upload_errors['subtitle'])) echo "is-invalid"; ?>"
                    name="photo_subtitle" placeholder="<?php echo UPLOAD_PLACEHOLDER_SUBTITLE . "*"; ?>"
                    maxlength="<?php echo LIMIT_PHOTO_SUBTITLE; ?>"
                    value="<?php if (!empty($photo->photo_subtitle)) echo $photo->photo_subtitle; ?>">
                </div>
                <div class="form-group col-xs-12">
                    <textarea class="form-control <?php if (isset($upload_errors['text'])) echo "is-invalid"; ?>"
                    maxlength="<?php echo LIMIT_PHOTO_POST; ?>" placeholder="<?php echo UPLOAD_PLACEHOLDER_POST . "*"; ?>"
                    name="photo_text"><?php if (!empty($photo->photo_text)) echo $photo->photo_text; ?></textarea>
                </div>
                <div class="form-group col-xs-12">
                    <input type="submit" class="btn btn-primary btn-fullwidth" name="submit" value="<?php echo UPLOAD_UPLOAD; ?>">
                </div>
            </form>

        <?php } ?>
    <div>

</div>

<?php include "includes/footer.php"; ?>