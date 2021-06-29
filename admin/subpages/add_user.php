<?php

$add_user_successful = false;

$add_user_errors = [];

$user_values = [
    "user_username"  => "",
    "user_password"  => "",
    "user_firstname" => "",
    "user_lastname"  => "",
    "user_email"     => "",
    "user_role"      => ""
];

if (isset($_POST['add_user'])) {
    $user_values['user_username']  = trim($_POST['user_username']);
    $user_values['user_password']  = trim($_POST['user_password']);
    $user_values['user_firstname'] = trim($_POST['user_firstname']);
    $user_values['user_lastname']  = trim($_POST['user_lastname']);
    $user_values['user_email']     = trim($_POST['user_email']);
    $user_values['user_role']      = trim($_POST['user_role']);
    $user_image                    = $_FILES['user_image'];

    // Check if inputs empty & if username or email are already in use
    $add_user_errors = User::verify_registration($user_values, $user_image);

    if (!User::errors_in_form($add_user_errors)) {
        // Create user and set properties to user_values
        $new_user = User::retrieved_row_to_object_instance($user_values);
        $new_user->user_password = password_hash($new_user->user_password, PASSWORD_BCRYPT, array('cost' => 12) );

        if (!$new_user->set_file($user_image)) $registration_errors["file_upload"] = join("<br>", $new_user->errors);

        if (!User::errors_in_form($add_user_errors)) {
            if ($new_user->save()) {
                $add_user_successful = true;
            } else {
                $add_user_errors["file_upload"] = join("<br>", $new_user->errors);
            }
        }
    }
}

?>

<div class="">
    <h1 class="page-title">Add User</h1>
    <div class="row">

        <?php if ($add_user_successful) { ?>
            <h4 class="bg-success">User added successfully.</h4>
        <?php } else { ?>
            <?php foreach ($add_user_errors as $add_user_error) { ?>
                <h4 class="bg-danger"><?php echo $add_user_error; ?></h4>
            <?php } ?>
        
            <form method="post" enctype="multipart/form-data">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="user_image">User image</label>
                        <input class="" type="file" name="user_image">
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control"
                        name="user_username" placeholder="Username"
                        value="<?php echo $user_values['user_username']; ?>">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control"
                        name="user_password" placeholder="Password"
                        value="<?php echo $user_values['user_password']; ?>">
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control"
                        name="user_firstname" placeholder="First name"
                        value="<?php echo $user_values['user_firstname']; ?>">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control"
                        name="user_lastname" placeholder="Last name"
                        value="<?php echo $user_values['user_lastname']; ?>">
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control"
                        name="user_email" placeholder="Email"
                        value="<?php echo $user_values['user_email']; ?>">
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="user_role">
                            <option value="">Select User Role</option>
                            <option value="User"
                            <?php if ($user_values['user_role'] == "User") echo "selected"; ?>>User</option>
                            <option value="Photographer"
                            <?php if ($user_values['user_role'] == "Photographer") echo "selected"; ?>>Photographer</option>
                            <option value="Admin"
                            <?php if ($user_values['user_role'] == "Admin") echo "selected"; ?>>Administrator</option>
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