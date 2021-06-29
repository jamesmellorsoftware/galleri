<nav class="navbar navbar-default navbar-fixed-top">
    <div class="brand">
        <a href="index.php ">Galleri: Admin</a>
    </div>
    <div class="container-fluid">
        <div class="navbar-btn">
            <button type="button" class="btn-toggle-fullwidth"><i class="lnr lnr-arrow-left-circle"></i></button>
        </div>
        <div id="navbar-menu">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span><?php echo "username"; ?></span>
                        <i class="icon-submenu lnr lnr-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="../index.php"><i class="lnr lnr-home"></i> <span>Back to Blog</span></a></li>
                        <li><a href='edit.php?action=edit_user&id=<?php echo $session->user_id; ?>'><i class="lnr lnr-user"></i> <span>Edit Profile</span></a></li>
                        <li><a href="../logout.php"><i class="lnr lnr-exit"></i> <span>Logout</span></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>