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
            <h4 class="bg-success"><?php echo ADD_USER_SUCCESS; ?></h4>
        <?php } else { ?>
            <?php foreach ($add_user_errors as $add_user_error) { ?>
                <h4 class="bg-danger"><?php echo $add_user_error; ?></h4>
            <?php } ?>
        
            <form method="post" enctype="multipart/form-data">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="user_image"><?php echo ADD_USER_LABEL; ?></label>
                        <input class="" type="file" name="user_image">
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control"
                        name="user_username" placeholder="Username"
                        value="<?php if (isset($new_user->user_username)) echo $new_user->user_username; ?>">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control"
                        name="user_password" placeholder="Password"
                        value="<?php if (isset($new_user->user_password)) echo $new_user->user_password; ?>">
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control"
                        name="user_firstname" placeholder="First name"
                        value="<?php  if (isset($new_user->user_firstname)) echo $new_user->user_firstname; ?>">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control"
                        name="user_lastname" placeholder="Last name"
                        value="<?php if (isset($new_user->user_lastname)) echo $new_user->user_lastname; ?>">
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control"
                        name="user_email" placeholder="Email"
                        value="<?php if (isset($new_user->user_email)) echo $new_user->user_email; ?>">
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="user_role">
                            <option value="">Select User Role</option>
                            <option value="User"
                            <?php if (isset($new_user->user_role) && $new_user->user_role == "User") echo "selected"; ?>>User</option>
                            <option value="Photographer"
                            <?php if (isset($new_user->user_role) && $new_user->user_role == "Photographer") echo "selected"; ?>>Photographer</option>
                            <option value="Admin"
                            <?php if (isset($new_user->user_role) && $new_user->user_role == "Admin") echo "selected"; ?>>Administrator</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" name="add_user" value="Add User">
                </div>
            </form>
        <?php } ?>
    <div>

</div>