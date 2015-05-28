<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die (__('Please do not load this page directly. Thanks!','InReview'));

	if ( post_password_required() ) { ?>

<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.','InReview') ?></p>
<?php
		return;
	}
?>
<!-- You can start editing here. -->

<div id="comment-wrap">

	<?php if ( have_comments() ) : ?>
		
			<h3 id="comments"><?php comments_number(__('No Comments','InReview'), __('One Comment','InReview'), '% '.__('Comments','InReview') );?></h3>
			
		<?php if ( ! empty($comments_by_type['comment']) ) : ?>
			<ol class="commentlist clearfix">
				<?php wp_list_comments(array('type'=>'comment','callback'=>'mytheme_comment','avatar_size'=>50, 'reply_text'=>'Reply')); ?>
			</ol>
		<?php endif; ?>
		
			<div class="navigation">
				<div class="alignleft">
					<?php previous_comments_link() ?>
				</div>
				<div class="alignright">
					<?php next_comments_link() ?>
				</div>
			</div>
			
		<?php if ( ! empty($comments_by_type['pings']) ) : ?>
		<div id="trackbacks">
			<h3 id="comments"><?php _e('Trackbacks/Pingbacks','InReview') ?></h3>
			<ol class="pinglist">
				<?php wp_list_comments('type=pings&callback=list_pings'); ?>
			</ol>
		</div>
		<?php endif; ?>	
	<?php else : // this is displayed if there are no comments so far ?>
	   <div id="comment-section" class="nocomments">
		  <?php if ('open' == $post->comment_status) : ?>
			 <!-- If comments are open, but there are no comments. -->
			 
		  <?php else : // comments are closed ?>
			 <!-- If comments are closed. -->
				<div id="respond">
				   
				</div> <!-- end respond div -->
		  <?php endif; ?>
	   </div>
	<?php endif; ?>
	<?php if ('open' == $post->comment_status) : ?>

		<div id="respond" class="clearfix">
			<h3 id="comments">
				<?php comment_form_title( __('Leave a Comment','InReview'), __('Leave a Comment to %s','InReview' )); ?>
			</h3>
			<div class="cancel-comment-reply">
				<small><?php cancel_comment_reply_link(); ?></small>
			</div> <!-- end cancel-comment-reply div -->
			<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
				<p><?php _e('You must be','InReview')?> <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>"><?php _e('logged in','InReview') ?></a> <?php _e('to post a comment.','InReview') ?></p>
			<?php else : ?>
				<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
				<?php if ( $user_ID ) : ?>
					<p><?php _e('Logged in as','InReview') ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account"><?php _e('Log out &raquo;','InReview') ?></a></p>
				<?php else : ?>
					<p>
						<input type="text" name="author" id="author" value="<?php if ($comment_author <> '') echo $comment_author; else _e('Name','InReview'); ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
						<label for="author" class="hidden"><?php _e('Name','InReview'); ?></label>
					</p>
					<p>
						<input type="text" name="email" id="email" value="<?php if ( $comment_author_email <> '' ) echo $comment_author_email; else _e('Mail (will not be published)','InReview'); ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
						<label for="email" class="hidden"><?php _e('Mail (will not be published)','InReview'); ?></label>
					</p>
					<p>
						<input type="text" name="url" id="url" value="<?php if ($comment_author_url <> '') echo $comment_author_url; else _e('Website','InReview') ?>" size="22" tabindex="3" />
						<label for="url" class="hidden"><?php _e('Website','InReview'); ?></label>
					</p>
				<?php endif; ?>
				<!--<p><small><strong>XHTML:</strong> You can use these tags: <code><?php echo allowed_tags(); ?></code></small></p>-->
				<?php do_action( 'et_comment_form' ); ?>
				
				<p>
					<textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4"><?php _e('Your message...','InReview'); ?></textarea>
					<label for="comment" class="hidden"><?php _e('Your message...','InReview'); ?></label>
				</p>
				<p id="submit-container">
					<input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Submit','InReview')?>" />
					<?php comment_id_fields(); ?>
				</p>
				<?php do_action('comment_form', $post->ID); ?>
				</form>
			<?php endif; // If registration required and not logged in ?>
		</div> <!-- end respond div -->
	<?php else: ?>

	<?php endif; // if you delete this the sky will fall on your head ?>
	
</div>