<?php

if (!$session->user_is_admin()) header("Location: ../index.php");

$add_user_successful = false;

$add_user_errors = [];

if (isset($_POST['add_user'])) {
    $new_user = new User;

    $new_user->user_username  = trim($_POST['user_username']);
    $new_user->user_password  = trim($_POST['user_password']);
    $new_user->user_firstname = trim($_POST['user_firstname']);
    $new_user->user_lastname  = trim($_POST['user_lastname']);
    $new_user->user_email     = trim($_POST['user_email']);
    $new_user->user_role      = trim($_POST['user_role']);
    $new_user_image           = $_FILES['user_image'];

    $add_user_errors = $new_user->verify_registration($new_user_image);

    if (!$new_user->set_file($new_user_image)) $add_user_errors["file_upload"] = join("<br>", $new_user->errors);

    if (!User::errors_in_form($add_user_errors)) {
        $new_user->user_password = password_hash($_POST['user_password'], PASSWORD_BCRYPT, array('cost' => 12) );
        if ($new_user->save()) {
            $add_user_successful = true;
        } else {
            $add_user_errors["file_upload"] = join("<br>", $new_user->errors);
        }
    }
}

?>

<div class="">
    <h1 class="page-title"><?php echo ADD_USER_TITLE; ?></h1>
    <div class="row">
        <?php if ($add_user_successful) { ?>
            <div class="col-xs-12">
                <h4 class="bg-success success"><?php echo ADD_USER_SUCCESS; ?></h4>
            </div>
        <?php } else { ?>
            <?php foreach ($add_user_errors as $add_user_error) { ?>
                <div class="col-xs-12">
                    <h4 class="bg-danger error"><?php echo $add_user_error; ?></h4>
                </div>
            <?php } ?>
        
            <form method="post" enctype="multipart/form-data">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="user_image"
                        class="<?php if (isset($add_user_errors['file_upload'])) echo "text-danger"; ?>">
                            <?php echo ADD_USER_LABEL . "*"; ?>
                        </label>
                        <input class="" type="file" name="user_image">
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <input type="text"
                        class="form-control <?php if (isset($add_user_errors['username'])) echo "is-invalid"; ?>"
                        name="user_username" placeholder="<?php echo ADD_USER_PLACEHOLDER_USERNAME . "*"; ?>"
                        maxlength="<?php echo LIMIT_USERNAME; ?>"
                        value="<?php if (isset($new_user->user_username)) echo $new_user->user_username; ?>">
                    </div>
                    <div class="form-group">
                        <input type="password"
                        class="form-control <?php if (isset($add_user_errors['password'])) echo "is-invalid"; ?>"
                        name="user_password" placeholder="<?php echo ADD_USER_PLACEHOLDER_PASSWORD . "*"; ?>"
                        maxlength="<?php echo LIMIT_PASSWORD; ?>"
                        value="<?php if (isset($new_user->user_password)) echo $new_user->user_password; ?>">
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <input type="text"
                        class="form-control <?php if (isset($add_user_errors['firstname'])) echo "is-invalid"; ?>"
                        name="user_firstname" placeholder="<?php echo ADD_USER_PLACEHOLDER_FIRSTNAME . "*"; ?>"
                        maxlength="<?php echo LIMIT_FIRSTNAME; ?>"
                        value="<?php  if (isset($new_user->user_firstname)) echo $new_user->user_firstname; ?>">
                    </div>
                    <div class="form-group">
                        <input type="text"
                        class="form-control <?php if (isset($add_user_errors['lastname'])) echo "is-invalid"; ?>"
                        name="user_lastname" placeholder="<?php echo ADD_USER_PLACEHOLDER_LASTNAME . "*"; ?>"
                        maxlength="<?php echo LIMIT_LASTNAME; ?>"
                        value="<?php if (isset($new_user->user_lastname)) echo $new_user->user_lastname; ?>">
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <input type="text"
                        class="form-control <?php if (isset($add_user_errors['email'])) echo "is-invalid"; ?>"
                        name="user_email" placeholder="<?php echo ADD_USER_PLACEHOLDER_EMAIL . "*"; ?>"
                        maxlength="<?php echo LIMIT_EMAIL; ?>"
                        value="<?php if (isset($new_user->user_email)) echo $new_user->user_email; ?>">
                    </div>
                    <div class="form-group">
                        <select name="user_role"
                        class="form-control <?php if (isset($add_user_errors['role'])) echo "is-invalid"; ?>">
                            <option value=""><?php echo ADD_USER_ROLE_SELECT . "*"; ?></option>
                            <option value="User"
                            <?php if (isset($new_user->user_role) && $new_user->user_role == "User") echo "selected"; ?>>
                                <?php echo ADD_USER_ROLE_USER; ?>
                            </option>
                            <option value="Photographer"
                            <?php if (isset($new_user->user_role) && $new_user->user_role == "Photographer") echo "selected"; ?>>
                                <?php echo ADD_USER_ROLE_PHOTOGRAPHER; ?>
                            </option>
                            <option value="Admin"
                            <?php if (isset($new_user->user_role) && $new_user->user_role == "Admin") echo "selected"; ?>>
                                <?php echo ADD_USER_ROLE_ADMIN; ?>
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-sm-12">
                    <input type="submit" class="btn btn-primary btn-fullwidth" name="add_user" value="<?php echo ADD_USER_BUTTON; ?>">
                </div>
            </form>
        <?php } ?>
    <div>

</div>