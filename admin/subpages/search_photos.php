<div class="modal-body">
    <div class="form-group col-md-9 col-xs-12">
        <input type="text" class="form-control"
        name="photo_author" placeholder="Posted by (username)"
        value="<?php if (isset($_POST['photo_author'])) echo $_POST['photo_author']; ?>">
    </div>
    <div class="form-group col-md-3 col-xs-12">
        <input type="number" class="form-control"
        name="photo_id" placeholder="Photo ID"
        value="<?php if (isset($_POST['photo_id'])) echo $_POST['photo_id']; ?>">
    </div>
    <div class="form-group col-md-6 col-xs-12">
        <input type="text" class="form-control"
        name="photo_title" placeholder="Title"
        value="<?php if (isset($_POST['photo_title'])) echo $_POST['photo_title']; ?>">
    </div>
    <div class="form-group col-md-6 col-xs-12">
        <input type="text" class="form-control"
        name="photo_subtitle" placeholder="Subtitle"
        value="<?php if (isset($_POST['photo_subtitle'])) echo $_POST['photo_subtitle']; ?>">
    </div>
    <div class="form-group col-md-12 col-xs-12">
        <input type="text" class="form-control"
        name="photo_text" placeholder="Content Contains"
        value="<?php if (isset($_POST['photo_text'])) echo $_POST['photo_text']; ?>">
    </div>
    <div class="form-group col-md-4 col-xs-12">
        <input type="date" class="form-control"
        name="photo_date_from" placeholder="From dd/mm/yyyy"
        value="<?php if (isset($_POST['photo_date_from'])) echo $_POST['photo_date_from']; ?>">
    </div>
    <div class="form-group col-md-4 col-xs-12">
        <input type="date" class="form-control"
        name="photo_date_to" placeholder="To dd/mm/yyyy"
        value="<?php if (isset($_POST['photo_date_to'])) echo $_POST['photo_date_to']; ?>">
    </div>
    <div class="form-group col-md-4 col-xs-12">
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