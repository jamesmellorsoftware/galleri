<div class="row" id="pagination">
    <div class="col-md-12">
        <div class="load-more-button btn btn-primary"><a href="#"><?php echo ADMIN_PAGINATION_LOAD_MORE; ?></a></div>
        <input type="hidden" id="load_amount" value="0">
    </div>
</div>

<script>
    function get_search_filters() {
        let active_search_filters = {};

        <?php
            switch ($_GET['action']) {
                case 'moderate_comments':
                    $search_filters = Comment::get_search_filters();
                    break;
                case 'moderate_photos':
                    $search_filters = Photo::get_search_filters();
                    break;
                case 'moderate_users':
                    $search_filters = User::get_search_filters();
                    break;
            }

            foreach ($search_filters as $search_filter) {
                if (isset($_POST[$search_filter]) && !empty($_POST[$search_filter])) { ?>
                    active_search_filters['<?php echo $search_filter ?>'] = <?php echo "'".$_POST[$search_filter]."';";
                }
            }
        ?>

        return (Object.keys(active_search_filters).length === 0) ? "" : JSON.stringify(active_search_filters);
    }

    $(".load-more-button").on("click", function(){

        var source_page = "<?php echo basename($_SERVER['PHP_SELF']); ?>";
        var id = "<?php echo (isset($_GET['id'])) ? $_GET['id'] : ""; ?>";
        var action = "<?php echo (isset($_GET['action'])) ? $_GET['action'] : ""; ?>";

        var search_filters = get_search_filters();

        var load_amount = $("#load_amount").val();
        load_amount++;
        var pagination_limit = <?php echo isset($_POST['results_per_page']) ? $_POST['results_per_page'] : $pagination_limit; ?>;
        var offset = load_amount * pagination_limit;

        $.ajax({
            type: 'post',
            url: 'includes/pagination_retrieve.php',
            dataType: 'json',
            data: {
                "load_more": true,
                "offset": offset,
                "pagination_limit": pagination_limit,
                "source_page": source_page,
                "id": id,
                "action": action,
                "search_filters": search_filters
            },
            success: function(response){

                var new_set = response;

                // If photos waiting to be loaded, pop final result & keep displaying pagination
                if (new_set.length > pagination_limit) new_set.pop();
                else $("#pagination").remove();

                // Clone last element to get the exact same structure without having to write variables
                let last_block = $(".pagination-block").last();
                let new_block = last_block.clone(true);
                let new_blocks = "";

                switch (source_page) {
                    case "moderate.php":
                        switch (action) {
                            case "moderate_photos":
                                for (let i=0; i<new_set.length; i++) {
                                    // Change new cloned block's properties to i properties
                                    new_block.find(".photo_view_link").attr("href", "../photo.php?id=" + new_set[i].photo_id);
                                    new_block.find(".photo_edit_link").attr("href", "edit.php?action=edit_photo&id=" + new_set[i].photo_id);
                                    new_block.find(".photo_id").html(new_set[i].photo_id);
                                    new_block.find(".photo_author").html(new_set[i].photo_author);
                                    new_block.find(".photo_date").html(new_set[i].photo_date);
                                    new_block.find(".photo_title").html(new_set[i].photo_title);
                                    new_block.find(".photo_subtitle").html(new_set[i].photo_subtitle);
                                    new_block.find(".photo_text").html(new_set[i].photo_text);
                                    new_block.find(".photo_like_count").html(new_set[i].photo_like_count);
                                    new_block.find(".photo_comment_count").html(new_set[i].photo_comment_count);
                                    new_block.find(".photo_image").attr("src", "../" + new_set[i].photo_filepath);
                                    new_block.find(".photo_filename").html(new_set[i].photo_filename);
                                    new_block.find(".selectCheckbox").val(new_set[i].photo_id);
                                    new_block.find(".link_td").attr("href", "../photo.php?id="+new_set[i].photo_id);
                                    
                                    // Add new cloned and altered block to new_blocks collective element
                                    new_blocks += "<tr class='pagination-block'>" + new_block.html() + "</tr>";
                                }
                                break;
                            case "moderate_comments":
                                for (let i=0; i<new_set.length; i++) {
                                    // Change new cloned block's properties to i properties
                                    new_block.find(".comment_id").html(new_set[i].comment_id);
                                    new_block.find(".comment_photo_id").html(new_set[i].comment_photo_id);
                                    new_block.find(".comment_author_id").html(new_set[i].comment_author);
                                    new_block.find(".comment_date").html(new_set[i].comment_date);
                                    let comment_approved = (new_set[i].comment_approved == 1) ? "Approved" : "Unapproved";
                                    new_block.find(".comment_approved").html(comment_approved);
                                    new_block.find(".comment_content").html(new_set[i].comment_content);
                                    new_block.find(".comment_like_count").html(new_set[i].comment_like_count);
                                    new_block.find(".selectCheckbox").val(new_set[i].comment_id);
                                    
                                    // Add new cloned and altered block to new_blocks collective element
                                    new_blocks += "<tr class='pagination-block'>" + new_block.html() + "</tr>";
                                }
                                break;
                            case "moderate_users":
                                for (let i=0; i<new_set.length; i++) {
                                    // Change new cloned block's properties to i properties
                                    new_block.find(".edit_user_link").attr("href", "edit.php?action=edit_user&id=" + new_set[i].user_id);
                                    new_block.find(".user_id").html(new_set[i].user_id);
                                    new_block.find(".user_username").html(new_set[i].user_username);
                                    new_block.find(".user_firstname").html(new_set[i].user_firstname);
                                    new_block.find(".user_lastname").html(new_set[i].user_lastname);
                                    new_block.find(".user_email").html(new_set[i].user_email);
                                    new_block.find(".user_role").html(new_set[i].user_role);
                                    let user_banned = (new_set[i].user_banned == 1) ? "Banned" : "";
                                    new_block.find(".user_banned").html(user_banned);
                                    new_block.find(".user_image").attr("src", "../" + new_set[i].user_profile_image);
                                    new_block.find(".selectCheckbox").val(new_set[i].user_id);
                                    
                                    // Add new cloned and altered block to new_blocks collective element
                                    new_blocks += "<tr class='pagination-block'>" + new_block.html() + "</tr>";
                                }
                                break;
                        }
                        break;
                }

                last_block.after(new_blocks);

                $("#load_amount").val(load_amount);
            },
            error: function(response) {
                console.log('AJAX Error:');
                console.log(response);
            }
        });

    });
    
</script>