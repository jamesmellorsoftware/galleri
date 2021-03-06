<?php

if (!$session->user_is_admin()) header("Location: ../index.php");

$pagination_limit = (isset($_POST['results_per_page'])) ? $_POST['results_per_page'] : PAGINATION_LIMIT_MODERATE_USERS;
$show_pagination = false;

$users = (isset($_POST['search'])) ? User::search($_POST) : User::find_all($pagination_limit+1);
if (count($users) > $pagination_limit) {
    array_pop($users);
    $show_pagination = true;
}

$bulk_options = User::get_bulk_options();

?>

<h1 class="page-title">
    <?php echo MODERATE_USERS_HEADER; ?>
    <i aria-hidden="true" id="" data-toggle="modal" data-target="#searchModal"
    class="fa fa-search pull-right <?php if (isset($_POST['search'])) echo "text-success"; ?>"></i>
</h1>

<?php if (count($users) == 0 || empty($users)) { ?>
    <h3><?php echo MODERATE_USERS_NO_RESULTS; ?></h3>
<?php } else { ?>
    <form method="post" action="">
        <?php require_once("includes/bulk_options.php"); ?>
        <table id="moderate_users_table" class="table table-bordered table-hover">
        <thead></thead>
            <tbody>
                <tr><td class="clickable_td select_all_checkboxes select"><?php echo SELECT_DESELECT_ALL; ?></td></tr>
                <?php foreach ($users as $user) { ?>
                    <tr class="pagination-block">
                        <td class="clickable_td">
                            <div class="col-md-1 col-xs-12">
                                <input class='selectCheckbox align-middle' type='checkbox'
                                name='bulk_option_checkboxes[]' value='<?php echo $user->user_id; ?>'>
                            </div>
                            <div class="col-md-5 col-xs-12">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <span class="user_id"><?php echo $user->user_id; ?></span>
                                        -
                                        <span class="user_username"><?php echo $user->user_username; ?></span>
                                    </div>
                                    <div class="col-xs-12">
                                        <span class="user_firstname"><?php echo $user->user_firstname; ?></span>
                                        <span class="user_lastname"><?php echo $user->user_lastname; ?></span>
                                    </div>
                                    <div class="col-xs-12">
                                        <span class="user_email"><?php echo $user->user_email; ?></span>
                                    </div>
                                    <div class="col-xs-12">
                                        <span class="user_role"><?php echo $user->user_role; ?></span>
                                    </div>
                                    <div class="col-xs-12">
                                        <span class="user_banned"><?php if ($user->user_banned) echo MODERATE_USERS_BANNED; ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 col-xs-12 text-right pull-right">
                                <img class="user_image" width="200" height="auto"
                                src="<?php echo "../" . $user->get_user_image(); ?>">
                                <p>
                                    <a class="edit_user_link"
                                    href="edit.php?action=edit_user&id=<?php echo $user->user_id ?>">
                                        <?php echo MODERATE_USERS_EDIT; ?>
                                    </a>
                                </p>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </form>

    <?php if ($show_pagination) require_once("includes/pagination.php"); ?>
<?php } ?>