<?php

if (!isset($_GET['id'])) header("Location: index.php");

$photo = Photo::find_by_id($_GET['id']);

if (!$photo) header("Location: index.php");

$edit_photo_successful = false;

$edit_photo_errors = [];

$edit_photo_values = [
    "photo_title"    => "",
    "photo_subtitle" => "",
    "photo_content"  => "",
    "photo_filename" => ""
];

if (isset($_POST['submit'])) {
    $photo = new Photo;
    $photo->photo_title     = $_POST['photo_title'];
    $photo->photo_subtitle  = $_POST['photo_subtitle'];
    $photo->photo_text      = $_POST['photo_text'];
    $photo->photo_date      = date("Y-m-d");
    $photo->photo_author_id = $session->user_id;
    // If user didn't upload a new image, don't change the current one - don't move any image or delete filepath
    // If user did upload a new image, delete the old one and move the new one
    // $photo->photo_filename  = empty($_FILES['user_image']['name']) ? $user->user_image : trim($_FILES['user_image']['name']);

    $upload_errors = Photo::verify_upload($photo);

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
    <h1 class="page-title">Edit Photo: <?php echo $photo->photo_id; ?></h1>
    <div class="row">
        <?php if ($edit_photo_successful) { ?>
            <h4 class="bg-success">Photo edited successfully.</h4>
        <?php } else { ?>
            <?php foreach ($edit_photo_errors as $edit_photo_error) { ?>
                <h4 class="bg-danger"><?php echo $edit_photo_error; ?></h4>
            <?php } ?>

            <form method="post" enctype="multipart/form-data">
                <div class="col-md-12">
                    <img class="photo_image" width="500" height="auto"
                    src="<?php echo "../" . $photo->photo_path(); ?>">
                </div>
                <div class="form-group">
                    <label for="file_upload">&nbsp;</label>
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
                    <input type="submit" class="btn btn-primary" name="submit" value="Submit Changes">
                </div>
            </form>
        <?php } ?>
    <div>

</div>
