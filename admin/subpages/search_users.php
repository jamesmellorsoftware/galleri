<div class="modal-body">
    <div class="form-group col-md-4 col-xs-12">
        <input type="text" class="form-control"
        name="user_username" placeholder="Username"
        value="<?php if (isset($_POST['user_username'])) echo $_POST['user_username']; ?>">
    </div>
    <div class="form-group col-md-4 col-xs-12">
        <input type="text" class="form-control"
        name="user_firstname" placeholder="First Name"
        value="<?php if (isset($_POST['user_firstname'])) echo $_POST['user_firstname']; ?>">
    </div>
    <div class="form-group col-md-4 col-xs-12">
        <input type="text" class="form-control"
        name="user_lastname" placeholder="Last Name"
        value="<?php if (isset($_POST['user_lastname'])) echo $_POST['user_lastname']; ?>">
    </div>
    <div class="form-group col-md-9 col-xs-12">
        <input type="email" class="form-control"
        name="user_email" placeholder="Email"
        value="<?php if (isset($_POST['user_email'])) echo $_POST['user_email']; ?>">
    </div>
    <div class="form-group col-md-3 col-xs-12">
        <input type="number" class="form-control"
        name="user_id" placeholder="User ID"
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
    <div class="form-group col-md-6 col-xs-12">
        <select class="form-control" name="results_per_page">
            <option value="5" <?php if (isset($_POST['results_per_page']) && $_POST['results_per_page'] == 5) ?>>
                <?php echo RESULTS_PER_PAGE_DEFAULT; ?>
            </option>
            <option value="5">5</option>
            <option value="10" <?php if (isset($_POST['results_per_page']) && $_POST['results_per_page'] == 10) ?>>
                10
            </option>
            <option value="20" <?php if (isset($_POST['results_per_page']) && $_POST['results_per_page'] == 20) ?>>
                20
            </option>
            <option value="50" <?php if (isset($_POST['results_per_page']) && $_POST['results_per_page'] == 50) ?>>
                50
            </option>
            <option value="100" <?php if (isset($_POST['results_per_page']) && $_POST['results_per_page'] == 100) ?>>
                100
            </option>
            <option value="500" <?php if (isset($_POST['results_per_page']) && $_POST['results_per_page'] == 5) ?>>
                500
            </option>
        </select>
    </div>
</div>