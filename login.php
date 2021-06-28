<?php 
require_once("includes/header.php");
require_once("includes/nav_top.php");

if ($session->is_signed_in()) header("Location: index.php");

$login_errors = [];

$login_values = [
    "user_username"   => "",
    "user_password"   => ""
];

if (isset($_POST['login'])) {
    // Sanitise inputs and assign POST values to login_values array
    $login_values['user_username'] = trim($_POST['user_username']);
    $login_values['user_password'] = trim($_POST['user_password']);

    // Check if form inputs are empty, if user exists
    $login_errors = User::verify_login($login_values);

    // If no errors, check password
    if (!User::errors_in_form($login_errors)) $session->login(User::retrieve($login_values['user_username']));
}

?>

<div class="noHeaderVideo grid-portfolio">
    <div class="container text-center">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <?php if ($session->is_signed_in()) { ?>
                <h4 class="bg-success">
                    Login successful.<br />
                    Logged in as <?php echo $login_values['user_username']; ?>.<br />
                    <a href="index.php">Go to Galleri</a><br />
                </h4>
            <?php } else { ?>
                <?php foreach ($login_errors as $login_error) { ?>
                    <h4 class="bg-danger"><?php echo $login_error; ?></h4>
                <?php } ?>
                <form method="post" action="login.php">
                    <div class="form-group">
                        <input type="text"
                        class="form-control <?php if (!empty($login_errors['username'])) echo "is-invalid "; ?>"
                        name="user_username" placeholder="Username"
                        value="<?php echo htmlentities($login_values['user_username']); ?>">
                    </div>
                    <div class="form-group">
                        <input type="password"
                        class="form-control <?php if (!empty($login_errors['username'])) echo "is-invalid "; ?>"
                        name="user_password" placeholder="Password"
                        value="<?php echo htmlentities($login_values['user_password']); ?>">
                    </div>
                    <div class="form-group">
                        <input type="submit" name="login" class="btn btn-primary" value="Log in">
                    </div>
                </form>
                <p><a href="register.php">Don't have an account? Register here.</a></p>
            <?php } ?>
        </div>
        <div class="col-md-4"></div>
    </div>
</div>

<?php require_once("includes/footer.php"); ?>