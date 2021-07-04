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
                            <span><?php echo SIDEBAR_MODERATE; ?></span>
                            <i class="icon-submenu lnr lnr-chevron-left"></i>
                        </a>
                        <div id="submenu__moderate" class="<?php echo ($pagename == "moderate.php") ? "active" : "collapse"; ?>">
                            <ul class="nav">
                                <li>
                                    <a href="moderate.php?action=moderate_photos"
                                    class="<?php if ($pagename === "moderate.php" && isset($_GET['action']) && $_GET['action'] == "moderate_photos") echo "active"; ?>">
                                        <?php echo SIDEBAR_MODERATE_PHOTOS; ?>
                                    </a>
                                </li>
                                <?php if ($session->user_is_admin()) { ?>
                                    <li>
                                        <a href="moderate.php?action=moderate_comments"
                                        class="<?php if ($pagename === "moderate.php" && isset($_GET['action']) && $_GET['action'] == "moderate_comments") echo "active"; ?>">
                                        <?php echo SIDEBAR_MODERATE_COMMENTS; ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="moderate.php?action=moderate_users"
                                        class="<?php if ($pagename === "moderate.php" && isset($_GET['action']) && $_GET['action'] == "moderate_users") echo "active"; ?>">
                                        <?php echo SIDEBAR_MODERATE_USERS; ?>
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
                        <span><?php echo SIDEBAR_ADD; ?></span>
                        <i class="icon-submenu lnr lnr-chevron-left"></i>
                    </a>
                    <div id="submenu__add" class="<?php echo ($pagename == "add.php" || $pagename == "upload.php") ? "active" : "collapse"; ?>">
                        <ul class="nav">
                            <?php if ($session->user_is_admin()) { ?>
                                <li>
                                    <a href="add.php?action=add_user"
                                    class="<?php if ($pagename === "add.php" && isset($_GET['action']) && $_GET['action'] == "add_user") echo "active"; ?>">
                                    <?php echo SIDEBAR_ADD_USER; ?>
                                    </a>
                                </li>
                            <?php } ?>
                            <li>
                                <a href="upload.php" 
                                class="<?php if ($pagename === "upload.php") echo "active"; ?>">
                                    <span><?php echo SIDEBAR_ADD_PHOTO; ?></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>