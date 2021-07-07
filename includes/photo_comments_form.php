<div class="comments row">
    <div class="col-md-12">
        <?php if (!$session->is_signed_in()) { ?>
            <h4><a href="login.php"><?php echo PHOTO_COMMENT_LOGIN; ?></a></h4>
        <?php } else { ?>
            <?php if ($comment_successful) { ?>
                <h4 class="text-success"><?php echo PHOTO_COMMENT_SUCCESS; ?></h4>
            <?php } else { ?>
                <?php foreach ($comment_errors as $comment_error) { ?>
                    <h4 class="text-danger"><?php echo $comment_error; ?></h4>
                <?php } ?>
                <form action="" method="post" role="form">
                    <div class="form-group">
                        <textarea class="form-control
                        <?php if (isset($comment_errors['content']) && !empty($comment_errors['content'])) echo "is-invalid"; ?>"
                        rows="3"name="comment_content"
                        maxlength="<?php echo LIMIT_PHOTO_COMMENT; ?>"
                        placeholder="<?php echo PHOTO_COMMENT_PLACEHOLDER . "*"; ?>"><?php if (isset($comment_content)) echo $comment_content; ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit"><?php echo PHOTO_COMMENT_BUTTON; ?></button>
                </form>
            <?php } ?>
        <?php } ?>
    </div>
</div>