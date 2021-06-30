<?php
// Get page name so active page can be selected
$pagename = basename($_SERVER['PHP_SELF']);
?>

<div id="sidebar-nav" class="sidebar">
    <div class="sidebar-scroll">
        <nav>
            <ul class="nav">
                <?php if ($session->user_is_admin() || $session->user_is_photographer()) { ?>
                    <li>
                        <a href="#submenu__moderate" data-toggle="collapse"
                        class="<?php echo ($pagename == "moderate.php") ? "active" : "collapsed"; ?>">
                            <span>Moderate</span>
                            <i class="icon-submenu lnr lnr-chevron-left"></i>
                        </a>
                        <div id="submenu__moderate" class="<?php echo ($pagename == "moderate.php") ? "active" : "collapse"; ?>">
                            <ul class="nav">
                                <li>
                                    <a href="moderate.php?action=moderate_photos"
                                    class="<?php if ($pagename === "moderate.php" && isset($_GET['action']) && $_GET['action'] == "moderate_photos") echo "active"; ?>">
                                        Photos
                                    </a>
                                </li>
                                <?php if ($session->user_is_admin()) { ?>
                                    <li>
                                        <a href="moderate.php?action=moderate_comments"
                                        class="<?php if ($pagename === "moderate.php" && isset($_GET['action']) && $_GET['action'] == "moderate_comments") echo "active"; ?>">
                                            Comments
                                        </a>
                                    </li>
                                    <li>
                                        <a href="moderate.php?action=moderate_users"
                                        class="<?php if ($pagename === "moderate.php" && isset($_GET['action']) && $_GET['action'] == "moderate_users") echo "active"; ?>">
                                            Users
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </li>
                <?php } ?>
                <li>
                    <a href="#submenu__add" data-toggle="collapse"
                    class="collapsed <?php echo ($pagename == "add.php" || $pagename == "upload.php") ? "active" : "collapsed"; ?>">
                        <span>Add</span>
                        <i class="icon-submenu lnr lnr-chevron-left"></i>
                    </a>
                    <div id="submenu__add" class="<?php echo ($pagename == "add.php" || $pagename == "upload.php") ? "active" : "collapse"; ?>">
                        <ul class="nav">
                            <?php if ($session->user_is_admin()) { ?>
                                <li>
                                    <a href="add.php?action=add_user"
                                    class="<?php if ($pagename === "add.php" && isset($_GET['action']) && $_GET['action'] == "add_user") echo "active"; ?>">
                                        User
                                    </a>
                                </li>
                            <?php } ?>
                            <li>
                                <a href="upload.php" 
                                class="<?php if ($pagename === "upload.php") echo "active"; ?>">
                                    <span>Photo</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>