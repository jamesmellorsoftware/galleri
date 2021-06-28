<div class="comments row">
    <div class="col-md-12 text-content">
        <?php foreach ($comments as $comment) { ?>
            <p class="comment">
                <?php echo User::get_name_from_id($comment->comment_author_id); ?>
                -
                <?php echo date('jS M Y', strtotime($comment->comment_date)); ?>
                <br />
                <?php echo $comment->comment_content; ?>
                <br />
                <span style="width: 15px;"
                rel="<?php echo $comment->comment_id; ?>"
                class="comment_like_thumb glyphicon glyphicon-thumbs-up <?php if ($user_liked) echo "liked"; ?>"></span>
                <span class="comment_likes"><?php echo Comment_Like::count($comment->comment_id); ?></span>
            </p>
        <?php } ?>
    </div>
</div>