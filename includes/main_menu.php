<section class="overlay-menu">
    <div class="container">
        <div class="row">
            <div class="main-menu">
                <ul>
                    <li><a href="index.php"><?php echo MENU_HOME; ?></a></li>
                    <li><a href="photographers.php"><?php echo MENU_PHOTOGRAPHERS; ?></a></li>
                    <?php if (!$session->is_signed_in()) { ?>
                        <li><a href="login.php"><?php echo MENU_LOGIN; ?></a></li>
                        <li><a href="register.php"><?php echo MENU_REGISTER; ?></a></li>
                    <?php } else { ?>
                        <?php if ($session->admin_access()) { ?>
                            <li><a href="admin/index.php"><?php echo MENU_ADMIN; ?></a></li>
                        <?php } ?>
                        <li><a href="liked.php"><?php echo MENU_LIKED; ?></a></li>
                        <li><a href="logout.php"><?php echo MENU_LOGOUT; ?> <?php echo $session->user_username; ?></a></li>
                    <?php } ?>
                </ul>
                <p><?php echo MENU_BOTTOM; ?></p>
            </div>
        </div>
    </div>
</section>