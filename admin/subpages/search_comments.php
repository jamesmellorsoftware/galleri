<div class="modal-body">
    <div class="form-group col-md-3 col-xs-12">
        <input type="number" class="form-control"
        name="comment_id" placeholder="ID"
        value="<?php if (isset($_POST['comment_id'])) echo $_POST['comment_id']; ?>">
    </div>
    <div class="form-group col-md-3 col-xs-12">
        <input type="number" class="form-control"
        name="comment_photo_id" placeholder="Photo ID"
        value="<?php if (isset($_POST['comment_photo_id'])) echo $_POST['comment_photo_id']; ?>">
    </div>
    <div class="form-group col-md-6 col-xs-12">
        <input type="text" class="form-control"
        name="comment_author" placeholder="Author"
        value="<?php if (isset($_POST['comment_author'])) echo $_POST['comment_author']; ?>">
    </div>
    <div class="form-group col-md-6 col-xs-12">
        <input type="date" class="form-control"
        name="comment_date_from" placeholder="From dd/mm/yyyy"
        value="<?php if (isset($_POST['comment_date_from'])) echo $_POST['comment_date_from']; ?>">
    </div>
    <div class="form-group col-md-6 col-xs-12">
        <input type="date" class="form-control"
        name="comment_date_to" placeholder="To dd/mm/yyyy"
        value="<?php if (isset($_POST['comment_date_to'])) echo $_POST['comment_date_to']; ?>">
    </div>
    <div class="form-group col-md-12 col-xs-12">
        <input type="text" class="form-control"
        name="comment_content" placeholder="Content Contains"
        value="<?php if (isset($_POST['comment_content'])) echo $_POST['comment_content']; ?>">
    </div>
    <div class="form-group col-md-6 col-xs-12">
        <input type="checkbox" name="comment_approved[1]"
        <?php if (isset($_POST['comment_approved'])) echo "checked"; ?>>
        <label>Approved</label>
        <input type="checkbox" name="comment_approved[0]"
        <?php if (isset($_POST['comment_unapproved'])) echo "checked"; ?>>
        <label>Unapproved</label>
    </div>
    <div class="form-group col-md-6 col-xs-12">
        <select class="form-control" name="results_per_page">
            <option value="5" <?php if (isset($_POST['results_per_page']) && $_POST['results_per_page'] == 5) ?>>
                Results per Page
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