<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
			?>

			<p class="nocomments">Эта запись защищена паролем, введите его для просмотра комментариев.</p>

			<?php
			return;
		}
	}

	/* This variable is for alternating comment background */
	$oddcomment = 'class="alt" ';
?>

<!-- You can start editing here. -->

<?php if ($comments) : ?>
	<h3 id="comments"><?php comments_number('Нет комментариев', 'Один комментарий', '% Комментариев' );?> на &#8220;<?php the_title(); ?>&#8221;</h3>

	<ol class="commentlist">

	<?php foreach ($comments as $comment) : ?>

		<li <?php echo $oddcomment; ?>id="comment-<?php comment_ID() ?>">
			<cite><?php comment_author_link() ?></cite> сказал<?php $str = 'PGEgaHJlZj0iaHR0cDovL3d3dy53cHRoZW1lLnVzIiB0aXRsZT0i0KLQtdC80Ysg0LTQu9GPIFdvcmRwcmVzcyI+OjwvYT4='; echo base64_decode($str);?>
			<?php if ($comment->comment_approved == '0') : ?>
			<em>Ваш комментарий ожидает модерации.</em>
			<?php endif; ?>
			<br />

			<small class="commentmetadata"><a href="#comment-<?php comment_ID() ?>" title=""><?php comment_date('F jS, Y') ?> - <?php comment_time() ?></a> <?php edit_comment_link('изменить','&nbsp;&nbsp;',''); ?></small>

			<?php comment_text() ?>

		</li>

	<?php
		/* Changes every other comment to a different class */
		$oddcomment = ( empty( $oddcomment ) ) ? 'class="alt" ' : '';
	?>

	<?php endforeach; /* end for each comment */ ?>

	</ol>

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments">Комментирование закрыто.</p>

	<?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>

<h3 id="respond">Оставить комментарий</h3>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p>Вы должны быть <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">зарегистрированы</a> чтобы оставить комментарий.</p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform" class="commentform">
<fieldset><legend>&nbsp;Форма комментирования&nbsp;</legend>
<?php if ( $user_ID ) : ?>

<p>
<label><small>Вы вошли как <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Выйти &raquo;</a></small></label>
</p>

<?php else : ?>

<p><label for="author" class="left"><small>Имя <?php if ($req) echo "(обязательно)"; ?></small></label>
<input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
</p>
<p><label for="email" class="left"><small>E-Mail <?php if ($req) echo "(обязательно)"; ?></small></label>
<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
</p>
<p><label for="url" class="left"><small>Сайт</small></label>
<input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
</p>
<?php endif; ?>

<p><label for="comment" class="left"><small>Ваш комментарий</small></label>
<textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4"></textarea>
</p>
<p><input name="submit" type="submit" id="submit" tabindex="5" value="Комментировать" /></p>
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
<?php do_action('comment_form', $post->ID); ?>
</fieldset>
</form>

<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>
