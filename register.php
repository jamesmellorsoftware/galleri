<?php 
require_once("includes/header.php");
require_once("includes/nav_top.php");

if ($session->is_signed_in()) header("Location: index.php");

$registration_successful = false;

$registration_errors = [];

if (isset($_POST['register'])) {
    $new_user = new User;

    $new_user->user_username  = $db->connection->real_escape_string($_POST['user_username']);
    $new_user->user_password  = $db->connection->real_escape_string($_POST['user_password']);
    $new_user->user_firstname = $db->connection->real_escape_string($_POST['user_firstname']);
    $new_user->user_lastname  = $db->connection->real_escape_string($_POST['user_lastname']);
    $new_user->user_email     = $db->connection->real_escape_string($_POST['user_email']);
    $new_user->user_role      = "User";
    $new_user_image           = $_FILES['user_image'];

    $registration_errors = $new_user->verify_registration($new_user_image);

    if (!$new_user->set_file($new_user_image)) $registration_errors["file_upload"] = join("<br>", $new_user->errors);

    if (!User::errors_in_form($registration_errors)) {
        $new_user->user_password = password_hash($new_user->user_password, PASSWORD_BCRYPT, array('cost' => 12) );
        if ($new_user->save()) {
            $registration_successful = true;
        } else {
            $registration_errors["file_upload"] = join("<br>", $new_user->errors);
        }
    }
}

?>
<div class="wrapper">
    <div class="noHeaderVideo grid-portfolio">
        <div class="container text-center">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <?php if ($registration_successful) { ?>
                    <h4 class="text-success"><?php echo REGISTRATION_SUCCESS_MESSAGE; ?></h4>
                    <a href="index.php"><?php echo REGISTRATION_BACK_TO_INDEX; ?></a>
                <?php } else { ?>
                    <?php foreach ($registration_errors as $registration_error) { ?>
                        <h4 class="text-danger"><?php echo $registration_error; ?></h4>
                    <?php } ?>
                    <form method="post" action="register.php"  enctype="multipart/form-data">
                        <div class="form-group">
                            <input type="text"
                            class="form-control <?php if (isset($registration_errors['username'])) echo "is-invalid"; ?>"
                            name="user_username" placeholder="<?php echo REGISTRATION_PLACEHOLDER_USERNAME . "*"; ?>"
                            maxlength="<?php echo LIMIT_USERNAME; ?>"
                            value="<?php if (isset($new_user->user_username)) echo htmlentities($new_user->user_username); ?>">
                        </div>
                        <div class="form-group">
                            <input type="password"
                            class="form-control <?php if (isset($registration_errors['password'])) echo "is-invalid"; ?>"
                            name="user_password" placeholder="<?php echo REGISTRATION_PLACEHOLDER_PASSWORD . "*"; ?>"
                            maxlength="<?php echo LIMIT_PASSWORD; ?>"
                            value="<?php if (isset($new_user->user_password)) echo htmlentities($new_user->user_password); ?>">
                        </div>
                        <div class="form-group">
                            <input type="text"
                            class="form-control <?php if (isset($registration_errors['firstname'])) echo "is-invalid"; ?>"
                            name="user_firstname" placeholder="<?php echo REGISTRATION_PLACEHOLDER_FIRSTNAME . "*"; ?>"
                            maxlength="<?php echo LIMIT_FIRSTNAME; ?>"
                            value="<?php if (isset($new_user->user_firstname)) echo htmlentities($new_user->user_firstname); ?>">
                        </div>
                        <div class="form-group">
                            <input type="text"
                            class="form-control <?php if (isset($registration_errors['lastname'])) echo "is-invalid"; ?>"
                            name="user_lastname" placeholder="<?php echo REGISTRATION_PLACEHOLDER_LASTNAME . "*"; ?>"
                            maxlength="<?php echo LIMIT_LASTNAME; ?>"
                            value="<?php if (isset($new_user->user_lastname)) echo htmlentities($new_user->user_lastname); ?>">
                        </div>
                        <div class="form-group">
                            <input type="email"
                            class="form-control <?php if (isset($registration_errors['email'])) echo "is-invalid"; ?>"
                            name="user_email" placeholder="<?php echo REGISTRATION_PLACEHOLDER_EMAIL . "*"; ?>"
                            maxlength="<?php echo LIMIT_EMAIL; ?>"
                            value="<?php if (isset($new_user->user_email)) echo htmlentities($new_user->user_email); ?>">
                        </div>
                        <div class="form-group">
                            <label for="user_image"
                            class="<?php if (isset($registration_errors['file_upload'])) echo "text-danger"; ?>">
                                <?php echo REGISTRATION_LABEL_USERIMAGE . "*"; ?>
                            </label>
                            <input type="file" name="user_image"
                            class="form-control <?php if (isset($registration_errors['file_upload'])) echo "is-invalid"; ?>">
                        </div>
                        <div class="form-group">
                            <input type="submit" name="register" class="btn btn-primary" value="<?php echo REGISTRATION_REGISTER; ?>">
                        </div>
                    </form>
                    <p><a href="login.php"><?php echo REGISTRATION_ALREADY_HAVE_ACCOUNT; ?></a></p>
                <?php } ?>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div>
</div>
<?php require_once("includes/footer.php"); ?>