<?php 
require_once("includes/header.php");
require_once("includes/nav_top.php");

if ($session->is_signed_in()) header("Location: index.php");

$login_errors = [];

if (isset($_POST['login'])) {
    // Sanitise inputs and assign POST values to login_values array
    $user = new User;
    $user->user_username = trim($_POST['user_username']);
    $user->user_password = trim($_POST['user_password']);


    $login_values['user_username'] = trim($_POST['user_username']);
    $login_values['user_password'] = trim($_POST['user_password']);

    // Check if form inputs are empty, if user exists
    $login_errors = $user->verify_login();

    // If no errors, check password
    if (!User::errors_in_form($login_errors)) $session->login(User::retrieve($user->user_username));
}

?>
<div class="wrapper">
    <div class="noHeaderVideo grid-portfolio">
        <div class="container text-center">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <?php if ($session->is_signed_in()) { ?>
                    <h4 class="text-success">
                        <?php echo LOGIN_SUCCESS_1; ?>
                        <?php echo LOGIN_SUCCESS_2; if (isset($user->user_username)) echo $user->user_username; ?>.<br />
                        <?php echo LOGIN_SUCCESS_3; ?>
                    </h4>
                <?php } else { ?>
                    <?php foreach ($login_errors as $login_error) { ?>
                        <h4 class="text-danger"><?php echo $login_error; ?></h4>
                    <?php } ?>
                    <form method="post" action="login.php">
                        <div class="form-group">
                            <input type="text"
                            class="form-control <?php if (!empty($login_errors['username'])) echo "is-invalid "; ?>"
                            name="user_username" placeholder="<?php echo LOGIN_PLACEHOLDER_USERNAME . "*"; ?>"
                            value="<?php if (isset($user->user_username)) echo $user->user_username; ?>">
                        </div>
                        <div class="form-group">
                            <input type="password"
                            class="form-control <?php if (!empty($login_errors['username'])) echo "is-invalid "; ?>"
                            name="user_password" placeholder="<?php echo LOGIN_PLACEHOLDER_PASSWORD . "*"; ?>"
                            value="<?php if (isset($user->user_password)) echo $user->user_password; ?>">
                        </div>
                        <div class="form-group">
                            <input type="submit" name="login" class="btn btn-primary" value="<?php echo LOGIN_SUBMIT_BUTTON; ?>">
                        </div>
                    </form>
                    <p><a href="register.php"><?php echo LOGIN_ALREADY_HAVE_ACCOUNT; ?></a></p>
                <?php } ?>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div>
</div>
<?php require_once("includes/footer.php"); ?>