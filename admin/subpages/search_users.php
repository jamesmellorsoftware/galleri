<div class="modal-body">
    <div class="form-group col-md-4 col-xs-12">
        <input type="text" class="form-control"
        name="user_username" placeholder="<?php echo SEARCH_USERS_PLACEHOLDER_USERNAME; ?>"
        maxlength="<?php echo LIMIT_USERNAME; ?>"
        value="<?php if (isset($_POST['user_username'])) echo $_POST['user_username']; ?>">
    </div>
    <div class="form-group col-md-4 col-xs-12">
        <input type="text" class="form-control"
        name="user_firstname" placeholder="<?php echo SEARCH_USERS_PLACEHOLDER_FIRSTNAME; ?>"
        maxlength="<?php echo LIMIT_FIRSTNAME; ?>"
        value="<?php if (isset($_POST['user_firstname'])) echo $_POST['user_firstname']; ?>">
    </div>
    <div class="form-group col-md-4 col-xs-12">
        <input type="text" class="form-control"
        name="user_lastname" placeholder="<?php echo SEARCH_USERS_PLACEHOLDER_LASTNAME; ?>"
        maxlength="<?php echo LIMIT_LASTNAME; ?>"
        value="<?php if (isset($_POST['user_lastname'])) echo $_POST['user_lastname']; ?>">
    </div>
    <div class="form-group col-md-9 col-xs-12">
        <input type="email" class="form-control"
        name="user_email" placeholder="<?php echo SEARCH_USERS_PLACEHOLDER_EMAIL; ?>"
        maxlength="<?php echo LIMIT_EMAIL; ?>"
        value="<?php if (isset($_POST['user_email'])) echo $_POST['user_email']; ?>">
    </div>
    <div class="form-group col-md-3 col-xs-12">
        <input type="number" class="form-control"
        name="user_id" placeholder="<?php echo SEARCH_USERS_PLACEHOLDER_ID; ?>"
        maxlength="<?php echo LIMIT_USER_ID; ?>"
        value="<?php if (isset($_POST['user_id'])) echo $_POST['user_id']; ?>">
    </div>
    <div class="form-group col-md-6 col-xs-12">
        <input type="checkbox" name="user_role[admin]"
        <?php if (isset($_POST['user_role']['admin'])) echo "checked"; ?>>
        <label><?php echo SEARCH_USERS_ADMIN; ?></label>
        <input type="checkbox" name="user_role[photographer]"
        <?php if (isset($_POST['user_role']['photographer'])) echo "checked"; ?>>
        <label><?php echo SEARCH_USERS_PHOTOGRAPHER; ?></label>
        <input type="checkbox" name="user_role[user]"
        <?php if (isset($_POST['user_role']['user'])) echo "checked"; ?>>
        <label><?php echo SEARCH_USERS_USER; ?></label>
    </div>
    <div class="form-group col-md-6 col-xs-12">
        <input type="checkbox" name="user_banned"
        <?php if (isset($_POST['user_banned'])) echo "checked"; ?>>
        <label for="user_banned"><?php echo SEARCH_USERS_BANNED; ?></label>
        <input type="checkbox" name="user_hasphoto"
        <?php if (isset($_POST['user_banned'])) echo "checked"; ?>>
        <label for="user_hasphoto"><?php echo SEARCH_USERS_HAS_PHOTO; ?></label>
    </div>
    <div class="form-group col-md-12 col-xs-12">
        <?php require_once("search_results_per_page.php"); ?>
    </div>
</div>