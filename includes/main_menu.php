<section class="overlay-menu">
    <div class="container">
        <div class="row">
            <div class="main-menu">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="photographers.php">Photographers</a></li>
                    <?php if (!$session->is_signed_in()) { ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php } else { ?>
                        <li><a href="admin/index.php">Admin</a></li>
                        <li><a href="liked.php">My Liked Photos</a></li>
                        <li><a href="logout.php">Logout <?php echo $session->user_username; ?></a></li>
                    <?php } ?>
                </ul>
                <p>Select your option.</p>
            </div>
        </div>
    </div>
</section>