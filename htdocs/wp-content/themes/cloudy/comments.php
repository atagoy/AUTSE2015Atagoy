<?php // Do not delete these lines
	if (post_password_required()) {            
            echo '<p class="nocomments">This post is password protected. Enter the password to view comments.</p>';
            return;
        }
	$oddcomment = "graybox";
?>

<!-- You can start editing here. -->

<?php if ($comments) : ?>
    <h3><?php comments_number('No Comments', '1 Comment', '% Comments'); ?> to <em><?php the_title(); ?></em></h3>
    <ul class="comments-list"><?php wp_list_comments() ?></ul>
    <?php paginate_comments_links() ?>

<?php else:?>
    <?php if (comments_open()) : ?>
        <!-- If comments are open, but there are no comments. -->
    <?php elseif (!is_page()) : // comments are closed ?>
        <!-- If comments are closed. -->
        <h4>Comments are closed.</h4>
     <?php endif; ?>
<?php endif; ?>

<?php if (comments_open()) : ?>
    <?php comment_form() ?>
<?php endif; ?>