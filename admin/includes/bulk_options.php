<?php

$page = $_GET['action'];

// Apply bulk option to every user checked (move to db_objects)
if (isset($_POST['bulk_option_checkboxes']) && isset($_POST['apply_bulk_option']) && isset($_POST['bulk_option'])) {
    
    $bulk_option = $_POST['bulk_option'];

    foreach($_POST['bulk_option_checkboxes'] as $id) $ids[] = $id;

    switch ($bulk_option) {
        case "delete":
            if ($page === "moderate_photos") Photo::purge($ids);
            if ($page === "moderate_comments") Comment::purge($ids);
            if ($page === "moderate_users") User::purge($ids);
            break;
        case "reset_likes":
            if ($page === "moderate_photos") Photo::purge_likes($ids);
            if ($page === "moderate_comments") Comment::purge_comment_likes($ids);
            break;
        case "purge_comments":
            if ($page === "moderate_photos") Photo::purge_comments($ids);
            break;
        case "approve":
            if ($page === "moderate_comments") Comment::approve($ids);
            break;
        case "unapprove":
            if ($page === "moderate_comments") Comment::unapprove($ids);
            break;
        case "ban":
            if ($page === "moderate_users") User::ban($ids);
            break;
        case "unban":
            if ($page === "moderate_users") User::unban($ids);
            break;
        case "admin":
        case "photographer":
        case "user":
            if ($page === "moderate_users") User::change_user_role($ids, $bulk_option);
            break;
        case "":
        default:
            break;
    }

    header("Location: " . basename($_SERVER['PHP_SELF']) . "?action=" . $page);
}

?>

<div class="row">
    <div class="col-md-12">
        <div class="input-group" id="bulk_options">
            <select class='form-control' name='bulk_option'>
                <option value=''><?php echo BULK_OPTIONS_SELECT_OPTION; ?></option>
                <?php foreach ($bulk_options as $bulk_option) {
                    $bulk_option_value = $bulk_option['value'];
                    $bulk_option_name  = $bulk_option['name'];
                    echo "<option value='{$bulk_option_value}'>{$bulk_option_name}</option>";
                } ?>
            </select>
            <span class='input-group-btn'>
                <input type='submit' class='btn btn-success' name="apply_bulk_option" value='Apply'>
            </span>
        </div>
    </div>
</div>

<?php ?>