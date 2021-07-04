<?php

if (!isset($_GET['id'])) header("Location: index.php");

$user = User::find_by_id($_GET['id']);

if (!$user) header("Location: index.php");

$edit_user_successful = false;

$edit_user_errors = [];

if (isset($_POST['edit_user'])) {
    $old_user_values = $user;

    $user = new User;
    $user->user_id        = $old_user_values->user_id;
    $user->user_username  = $_POST['user_username'];
    $user->user_firstname = $_POST['user_firstname'];
    $user->user_lastname  = $_POST['user_lastname'];
    $user->user_email     = $_POST['user_email'];
    $user->user_role      = $_POST['user_role'];
    $user->user_password  = empty($_POST['user_password']) ? "" : password_hash($_POST['user_password'], PASSWORD_BCRYPT, array('cost' => 12) );
    $user->user_image = $old_user_values->user_image;
    $uploaded_image = $_FILES['user_image'];
    
    // If user has uploaded a new image, check it, verify it, upload it, then update db, then delete prev image
    // otherwise just keep it the same as $old_user_values->user_image
    if (is_uploaded_file($uploaded_image['tmp_name'])) {
        if ($user->delete_photo()) {
            if ($user->set_file($uploaded_image)){
                $user->save_photo();
            } else {
                $edit_user_errors["file_upload"] = join("<br>", $user->errors);
            }
        }
    }

    $edit_user_errors = $user->verify_edit($old_user_values);

    if (!User::errors_in_form($edit_user_errors)) {
        if ($user->save()) {
            $edit_user_successful = true;
        } else {
            $edit_user_errors["file_upload"] = join("<br>", $user->errors);
        }
    }
}

?>

<div class="">
    <h1 class="page-title"><?php echo EDIT_USER_HEADER . " " . $user->user_username; ?></h1>
    <div class="row">
        <?php if ($edit_user_successful) { ?>
            <h4 class="bg-success"><?php echo EDIT_PHOTO_SUCCESS; ?></h4>
        <?php } else { ?>
            <?php foreach ($edit_user_errors as $edit_user_error) { ?>
                <h4 class="bg-danger"><?php echo $edit_user_error; ?></h4>
            <?php } ?>
        
            <form method="post" enctype="multipart/form-data">
                <div class="col-md-12">
                    <img class="user_image" width="200" height="auto"
                    src="<?php echo "../" . $user->get_user_image(); ?>">
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="user_image"></label>
                        <input class="" type="file" name="user_image">
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control"
                        name="user_username" placeholder="Username"
                        value="<?php echo !empty($user_values['user_username']) ? $user_values['user_username'] : $user->user_username; ?>">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control"
                        name="user_password" placeholder="Password">
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control"
                        name="user_firstname" placeholder="First name"
                        value="<?php echo !empty($user_values['user_firstname']) ? $user_values['user_firstname'] : $user->user_firstname; ?>">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control"
                        name="user_lastname" placeholder="Last name"
                        value="<?php echo !empty($user_values['user_lastname']) ? $user_values['user_lastname'] : $user->user_lastname; ?>">
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control"
                        name="user_email" placeholder="Email"
                        value="<?php echo !empty($user_values['user_email']) ? $user_values['user_email'] : $user->user_email; ?>">
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="user_role">
                            <option value=""><?php echo EDIT_USER_ROLE_SELECT; ?></option>
                            <option value="User" <?php if ($user->user_role == "User") echo "selected"; ?>><?php echo EDIT_USER_ROLE_USER; ?></option>
                            <option value="Photographer" <?php if ($user->user_role == "Photographer") echo "selected"; ?>><?php echo EDIT_USER_ROLE_PHOTOGRAPHER; ?></option>
                            <option value="Admin" <?php if ($user->user_role == "Admin") echo "selected"; ?>><?php echo EDIT_USER_ROLE_ADMIN; ?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" name="edit_user" value="<?php echo EDIT_USER_BUTTON; ?>">
                </div>
            </form>
        <?php } ?>
    <div>

</div>