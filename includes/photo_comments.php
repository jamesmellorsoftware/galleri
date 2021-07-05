<div class="comments row">
    <div class="col-md-12 text-content">
        <?php foreach ($comments as $comment) { ?>
            <?php if ($comment->comment_approved == 1) { ?>
                <p class="comment">
                    <?php echo User::get_name_from_id($comment->comment_author_id); ?>
                    -
                    <?php echo date('jS M Y', strtotime($comment->comment_date)); ?>
                    <br />
                    <?php echo $comment->comment_content; ?>
                    <br />
                    <i style="width: 15px;"
                    rel="<?php echo $comment->comment_id; ?>"
                    class="comment_like_thumb glyphicon glyphicon-thumbs-up
                    <?php if ($user_liked) echo "liked text-success"; ?>"></i>
                    <i class="comment_likes <?php if ($user_liked) echo "text-success"; ?>">
                        <?php echo Comment_Like::count($comment->comment_id); ?>
                    </i>
                </p>
            <?php } ?>
        <?php } ?>
    </div>
</div>