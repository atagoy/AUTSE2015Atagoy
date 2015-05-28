<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
			?>

			<p class="nocomments">This post is password protected. Enter the password to view comments.</p>

			<?php
			return;
		}
	}

	/* This variable is for alternating comment background */
	$oddcomment = 'alt';
?>

<!-- You can start editing here. -->

<?php if ($comments) : ?>
	<h1 class="heading"><?php comments_number('No Comments', '1 Comment', '% Comments' );?></h1>

			<ul style="margin:0px; padding:0px; list-style:none;"><?php wp_list_comments('style=ul&type=comment&callback=mytheme_comment'); ?></ul>


	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments">Comments are closed.</p>

	<?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>

<!-- comment-form -->
<div id="comment-form">
				
	<h1 class="heading"><?php comment_form_title( 'Leave a Reply', 'Leave a Reply to %s' ); ?></h1>

		<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
		<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">logged in</a> to post a comment.</p>

</div><!-- #commentform-->

<?php else : ?>

		<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
		
			<div>

			<?php if ( $user_ID ) : ?>

				<p><?php _e('Logged in as'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e('Log out of this account'); ?>"><?php _e('Logout'); ?> &raquo;</a></p>

			<?php else : ?>

				<p>
					<label for="author">Your Name: <span class="required">Required</span></label>
				<input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
				</p>
				<p>
					<label for="email">Email: <span class="required">Required, Hidden</span></label>
					<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
				</p>
				<p>
					<label for="url">Website:</label>
					<input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
				</p>
		
<?php endif; ?>

				<p>
					<label for="message">Message:</label>
					<textarea name="comment" id="comment" cols="40" rows="10" tabindex="4"></textarea>
				</p>

				<p style="background:transparent;">
					<input name="submit" type="submit" id="submit" tabindex="5" value="Publish My Comment" />
					<?php comment_id_fields(); ?>
				</p>
				
				<?php do_action('comment_form', $post->ID); ?>

			</div>
		
		</form>

</div><!-- #commentform -->

<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>
