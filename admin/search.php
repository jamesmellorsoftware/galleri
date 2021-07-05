<!-- Modal -->
<div id="searchModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <?php
                    switch($action) {
                        case "moderate_comments":
                            require_once("subpages/search_comments.php");
                            break;
                        case "moderate_photos":
                            require_once("subpages/search_photos.php");
                            break;
                        case "moderate_users":
                            require_once("subpages/search_users.php");
                            break;
                    }
                ?>
                <div class="modal-footer">
                    <div class="col-xs-6">
                        <a class="btn btn-danger btn-fullwidth" href="javascript:location=window.location"><?php echo CLEAR_SEARCH; ?></a>
                    </div>
                    <div class="col-xs-6">
                        <input type="submit" name="search" class="btn btn-primary btn-fullwidth" value="<?php echo SEARCH_SEARCH; ?>">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>