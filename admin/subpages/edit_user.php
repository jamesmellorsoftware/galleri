<?php

if (!isset($_GET['id'])) header("Location: index.php");

if (!$session->user_is_admin() && $_GET['id'] != $session->user_id) header("Location: index.php");

$user = User::find_by_id($db->connection->real_escape_string($_GET['id']));

if (!$user) header("Location: index.php");

$edit_user_successful = false;

$edit_user_errors = [];

if (isset($_POST['edit_user'])) {
    $old_user_values = $user;

    $user = new User;
    $user->user_id        = $old_user_values->user_id;
    $user->user_username  = $db->connection->real_escape_string($_POST['user_username']);
    $user->user_firstname = $db->connection->real_escape_string($_POST['user_firstname']);
    $user->user_lastname  = $db->connection->real_escape_string($_POST['user_lastname']);
    $user->user_email     = $db->connection->real_escape_string($_POST['user_email']);
    $user->user_role      = $db->connection->real_escape_string($_POST['user_role']);
    $user->user_password  = empty($_POST['user_password']) ? "" : password_hash($db->connection->real_escape_string($_POST['user_password']), PASSWORD_BCRYPT, array('cost' => 12) );
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
            <div class="col-xs-12">
                <h4 class="bg-success success"><?php echo EDIT_USER_SUCCESS; ?></h4>
            </div>
        <?php } else { ?>
            <?php foreach ($edit_user_errors as $edit_user_error) { ?>
                <div class="col-xs-12">
                    <h4 class="bg-danger error"><?php echo $edit_user_error; ?></h4>
                </div>
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
                        <input type="text"
                        class="form-control <?php if (isset($edit_user_errors['username'])) echo "is-invalid"; ?>"
                        name="user_username" placeholder="<?php echo EDIT_USER_PLACEHOLDER_USERNAME . "*"; ?>"
                        maxlength="<?php echo LIMIT_USERNAME; ?>"
                        value="<?php echo !empty($user_values['user_username']) ? $user_values['user_username'] : $user->user_username; ?>">
                    </div>
                    <div class="form-group">
                        <input type="password"
                        class="form-control <?php if (isset($edit_user_errors['password'])) echo "is-invalid"; ?>"
                        maxlength="<?php echo LIMIT_PASSWORD; ?>"
                        name="user_password" placeholder="<?php echo EDIT_USER_PLACEHOLDER_PASSWORD . "*"; ?>">
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <input type="text"
                        class="form-control <?php if (isset($edit_user_errors['firstname'])) echo "is-invalid"; ?>"
                        name="user_firstname" placeholder="<?php echo EDIT_USER_PLACEHOLDER_FIRSTNAME . "*"; ?>"
                        maxlength="<?php echo LIMIT_FIRSTNAME; ?>"
                        value="<?php echo !empty($user_values['user_firstname']) ? $user_values['user_firstname'] : $user->user_firstname; ?>">
                    </div>
                    <div class="form-group">
                        <input type="text"
                        class="form-control <?php if (isset($edit_user_errors['lastname'])) echo "is-invalid"; ?>"
                        name="user_lastname" placeholder="<?php echo EDIT_USER_PLACEHOLDER_LASTNAME . "*"; ?>"
                        maxlength="<?php echo LIMIT_LASTNAME; ?>"
                        value="<?php echo !empty($user_values['user_lastname']) ? $user_values['user_lastname'] : $user->user_lastname; ?>">
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <input type="text"
                        class="form-control <?php if (isset($edit_user_errors['email'])) echo "is-invalid"; ?>"
                        name="user_email" placeholder="<?php echo EDIT_USER_PLACEHOLDER_EMAIL . "*"; ?>"
                        maxlength="<?php echo LIMIT_EMAIL; ?>"
                        value="<?php echo !empty($user_values['user_email']) ? $user_values['user_email'] : $user->user_email; ?>">
                    </div>
                    <div class="form-group">
                        <select name="user_role"
                        class="form-control <?php if (isset($edit_user_errors['role'])) echo "is-invalid"; ?>">
                            <option value=""><?php echo EDIT_USER_ROLE_SELECT . "*"; ?></option>
                            <option value="User" <?php if ($user->user_role == "User") echo "selected"; ?>><?php echo EDIT_USER_ROLE_USER; ?></option>
                            <option value="Photographer" <?php if ($user->user_role == "Photographer") echo "selected"; ?>><?php echo EDIT_USER_ROLE_PHOTOGRAPHER; ?></option>
                            <option value="Admin" <?php if ($user->user_role == "Admin") echo "selected"; ?>><?php echo EDIT_USER_ROLE_ADMIN; ?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-xs-12">
                    <input type="submit" class="btn btn-primary btn-fullwidth" name="edit_user" value="<?php echo EDIT_USER_BUTTON; ?>">
                </div>
            </form>
        <?php } ?>
    <div>

</div>