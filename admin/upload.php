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
            <h4 class="bg-success"><?php echo UPLOAD_SUCCESS; ?></h4>
        <?php } else { ?>
            <?php foreach ($upload_errors as $upload_error) { ?>
                <h4 class="bg-danger"><?php echo $upload_error; ?></h4>
            <?php } ?>

            <form method="post" action="upload.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="file_upload"><?php echo UPLOAD_FILE_LABEL; ?></label>
                    <input class="" type="file" name="file_upload">
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
                    <input type="submit" class="btn btn-primary" name="submit" value="Upload to Galleri">
                </div>
            </form>

        <?php } ?>
    <div>

</div>

<?php include "includes/footer.php"; ?>