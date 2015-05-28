<div id="about">
	<div id="about-post">
		<?php if ('open' == $post->comment_status) : ?>

  <h2 id="respond">Leave a Reply</h2>

  <?php if ( get_option('comment_registration') && !$user_ID ) : ?>

  <p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">logged

      in</a> to post a comment.</p>

  <?php else : ?>

  <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

    <?php if ( $user_ID ) : ?>

    <p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Logout &raquo;</a></p>

    <?php else : ?>

    <p>

      <input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />

      <label for="author"><small>Name

      <?php if ($req) echo "(required)"; ?>

      </small></label>

    </p>

    <p>

      <input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />

      <label for="email"><small>Mail (not published)

      <?php if ($req) echo "(required)"; ?>

      </small></label>

    </p>

    <p>

      <input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />

      <label for="url"><small>Website</small></label>

    </p>

    <?php endif; ?>

    <!--<p><small><strong>Clean XHTML:</strong>Use standards ready code tags in your comments. Any other html besides those listed are permitted: <?php echo allowed_tags(); ?></small></p>-->

    <p>
	

      <textarea name="comment" id="comment" cols="25" rows="7" tabindex="4"><?php if (function_exists('quoter_comment_server')) { quoter_comment_server(); } ?>

</textarea>

    </p>

    <p>

      <input name="submit" type="submit" id="submit" tabindex="5" value="Submit Comment" />

      <input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />

    </p>

    <script type="text/javascript">

  var blogTool               = "WordPress";

  var blogURL                = "<?php echo get_option('siteurl'); ?>";

  var blogTitle              = "<?php bloginfo('name'); ?>";

  var postURL                = "<?php the_permalink() ?>";

  var postTitle              = "<?php the_title(); ?>";

  <?php if ( $user_ID ) : ?>

      var commentAuthor          = "<?php echo $user_identity; ?>";

  <?php else : ?>

      var commentAuthorFieldName = "author";

  <?php endif; ?>

  var commentAuthorLoggedIn  = <?php if ( !$user_ID ) { echo "false"; }

                                     else { echo "true"; } ?>;

  var commentFormID          = "commentform";

  var commentTextFieldName   = "comment";

  var commentButtonName      = "submit";

  var cocomment_force        = false;

  </script>

    <?php do_action('comment_form', $post->ID); ?>

  </form>

  <?php endif; // If registration required and not logged in ?>

  <?php endif; // if you delete this the sky will fall on your head ?>
	</div>
</div>