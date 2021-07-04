<select class="form-control" name="results_per_page">
    <option value="5"
    <?php if (isset($_POST['results_per_page']) && $_POST['results_per_page'] == 5) echo "checked"; ?>>
        <?php echo RESULTS_PER_PAGE_DEFAULT; ?>
    </option>
    <option value="5">5</option>
    <option value="10"
    <?php if (isset($_POST['results_per_page']) && $_POST['results_per_page'] == 10) echo "checked"; ?>>
        10
    </option>
    <option value="20"
    <?php if (isset($_POST['results_per_page']) && $_POST['results_per_page'] == 20) echo "checked"; ?>>
        20
    </option>
    <option value="50"
    <?php if (isset($_POST['results_per_page']) && $_POST['results_per_page'] == 50) echo "checked"; ?>>
        50
    </option>
    <option value="100"
    <?php if (isset($_POST['results_per_page']) && $_POST['results_per_page'] == 100) echo "checked"; ?>>
        100
    </option>
    <option value="500"
    <?php if (isset($_POST['results_per_page']) && $_POST['results_per_page'] == 5) echo "checked"; ?>>
        500
    </option>
</select>