<div class="row" id="pagination">
    <div class="col-md-12">
        <div class="load-more-button"><a href="#"><?php echo PAGINATION_LOAD_MORE; ?></a></div>
        <input type="hidden" id="load_amount" value="0">
    </div>
</div>

<script>
    $(".load-more-button").on("click", function(){

        var load_amount = $("#load_amount").val();
        load_amount++;
        var pagination_limit = <?php echo $pagination_limit; ?>;
        var offset = load_amount * pagination_limit;
        var source_page = "<?php echo basename($_SERVER['PHP_SELF']); ?>";
        var id = "<?php echo (isset($_GET['id'])) ? $_GET['id'] : ""; ?>";
        var action = "<?php echo (isset($_GET['action'])) ? $_GET['action'] : ""; ?>";

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
                "action": action
            },
            success: function(response){
                // Need to change this based off where it's being called from

                var new_set = response;

                // If photos waiting to be loaded, pop final result & keep displaying pagination
                if (new_set.length > pagination_limit) new_set.pop();
                else $("#pagination").remove();

                // Clone last element to get the exact same structure without having to write variables
                let last_block = $(".pagination-block").last();
                let new_block = last_block.clone(true);
                let new_blocks = "";

                switch (source_page) {
                    case "index.php":
                    case "liked.php":
                    case "photographergallery.php":
                        for (let i=0; i<new_set.length; i++) {
                            // Change new cloned block's properties to i properties
                            new_block.find(".photo_link").attr("href", "photo.php?id=" + new_set[i].photo_id);
                            new_block.find(".photo_title").html(new_set[i].photo_title);
                            new_block.find(".photo_subtitle").html(new_set[i].photo_subtitle);
                            new_block.find(".photo_author").html(new_set[i].photo_author);
                            new_block.find(".photo_like_count").html(new_set[i].photo_like_count);
                            new_block.find(".photo_comment_count").html(new_set[i].photo_comment_count);
                            new_block.find(".photo_image").attr("src", new_set[i].filepath);
                            
                            // Add new cloned and altered block to new_blocks collective element
                            new_blocks += new_block.html();
                        }
                        break;
                    case "photographers.php":
                        for (let i=0; i<new_set.length; i++) {
                            // Change new cloned block's properties to i properties
                            new_block.find(".photographer_link").attr("href", "photographergallery.php?id=" + new_set[i].user_id);
                            new_block.find(".photographer_name").html(new_set[i].user_firstname + " <em>" + new_set[i].user_lastname + "</em>");
                            new_block.find(".photographer_image").attr("src", new_set[i].photographer_image);
                            
                            // Add new cloned and altered block to new_blocks collective element
                            new_blocks += new_block.html();
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