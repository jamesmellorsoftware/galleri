<?php

if (!isset($_GET['id'])) header("Location: index.php");

$user = User::find_by_id($_GET['id']);

if (!$user) header("Location: index.php");

$edit_user_successful = false;

$edit_user_errors = [];

$user_values = [
    "user_username"  => "",
    "user_password"  => "",
    "user_firstname" => "",
    "user_lastname"  => "",
    "user_email"     => ""
];

if (isset($_POST['edit_user'])) {
    $user_values['user_username']  = trim($_POST['user_username']);
    $user_values['user_password']  = empty($_POST['user_password']) ? "" : password_hash($_POST['user_password'], PASSWORD_BCRYPT, array('cost' => 12) );
    $user_values['user_firstname'] = trim($_POST['user_firstname']);
    $user_values['user_lastname']  = trim($_POST['user_lastname']);
    $user_values['user_email']     = trim($_POST['user_email']);
    $user_values['user_role']      = trim($_POST['user_role']);

    // if (isset($_FILES['user_image']) && !empty($_FILES['user_image'])) {
    //     if (!$new_user->set_file($_FILES['user_image'])) $edit_user_errors["file_upload"] = join("<br>", $new_user->errors);
    // }

    // Check if inputs empty & if username or email are already in use
    $edit_user_errors = User::verify_user_edit($user, $user_values);

    if (!User::errors_in_form($edit_user_errors)) {
        // Create user and set properties to user_values
        $new_user = User::retrieved_row_to_object_instance($user_values);
        $new_user->user_id = $user->user_id;

        if ($new_user->update()) {
            $edit_user_successful = true;
        } else {
            $edit_user_errors['username'] = "Error editing user.";
        }
    }
}

?>

<div class="">
    <h1 class="page-title">Edit User: <?php echo $user->user_username; ?></h1>
    <div class="row">
        <?php if ($edit_user_successful) { ?>
            <h4 class="bg-success">User edited successfully.</h4>
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
                            <option value="">Select User Role</option>
                            <option value="User" <?php if ($user->user_role == "User") echo "selected"; ?>>User</option>
                            <option value="Photographer" <?php if ($user->user_role == "Photographer") echo "selected"; ?>>Photographer</option>
                            <option value="Admin" <?php if ($user->user_role == "Admin") echo "selected"; ?>>Administrator</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" name="edit_user" value="Edit User">
                </div>
            </form>
        <?php } ?>
    <div>

</div>