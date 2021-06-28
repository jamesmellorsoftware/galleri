<?php 
require_once("includes/header.php");
require_once("includes/nav_top.php");

if ($session->is_signed_in()) header("Location: index.php");

$registration_successful = false;

$registration_errors = [];

$user_values = [
    "user_username"  => "",
    "user_password"  => "",
    "user_firstname" => "",
    "user_lastname"  => "",
    "user_email"     => ""
];

if (isset($_POST['register'])) {
    // Assign POST values to user values array
    $user_values['user_username']  = trim($_POST['user_username']);
    $user_values['user_password']  = trim($_POST['user_password']);
    $user_values['user_firstname'] = trim($_POST['user_firstname']);
    $user_values['user_lastname']  = trim($_POST['user_lastname']);
    $user_values['user_email']     = trim($_POST['user_email']);

    // Check if inputs empty & if username or email are already in use
    $registration_errors = User::verify_registration($user_values);

    if (!User::errors_in_form($registration_errors)) {
        // Create user and set properties to user_values
        $new_user = User::retrieved_row_to_object_instance($user_values);
        $new_user->user_password = password_hash($new_user->user_password, PASSWORD_BCRYPT, array('cost' => 12) );
        $new_user->user_role = "User";

        // Create user
        if ($new_user->create($user_values)) {
            $registration_successful = true;
        } else {
            $registration_errors['username'] = "Error registering user.";
        }
    }
}

?>

<div class="noHeaderVideo grid-portfolio">
    <div class="container text-center">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <?php if ($registration_successful) { ?>
                <h4 class="bg-success">
                    Registration successful.<br />
                    Your account is awaiting approval.<br />
                    You will receive an email when your account has been processed.
                </h4>
                <a href="index.php">Back to Galleri</a>
            <?php } else { ?>
                <?php foreach ($registration_errors as $registration_error) { ?>
                    <h4 class="bg-danger"><?php echo $registration_error; ?></h4>
                <?php } ?>
                <form method="post" action="register.php">
                    <div class="form-group">
                        <input type="text" class="form-control"
                        name="user_username" placeholder="Username"
                        value="<?php echo htmlentities($user_values['user_username']); ?>">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control"
                        name="user_password" placeholder="Password"
                        value="<?php echo htmlentities($user_values['user_password']); ?>">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control"
                        name="user_firstname" placeholder="First Name"
                        value="<?php echo htmlentities($user_values['user_firstname']); ?>">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control"
                        name="user_lastname" placeholder="Last Name"
                        value="<?php echo htmlentities($user_values['user_lastname']); ?>">
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control"
                        name="user_email" placeholder="Email"
                        value="<?php echo htmlentities($user_values['user_email']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="user_image">User Photo</label>
                        <input type="file" class="form-control"
                        name="user_image">
                    </div>
                    <div class="form-group">
                        <input type="submit" name="register" class="btn btn-primary" value="Register">
                    </div>
                </form>
                <p><a href="login.php">Already have an account? Log in here.</a></p>
            <?php } ?>
        </div>
        <div class="col-md-4"></div>
    </div>
</div>

<?php require_once("includes/footer.php"); ?>