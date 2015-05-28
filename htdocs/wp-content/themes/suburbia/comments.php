<?php
/**
 * @package WordPress
 * @subpackage Suburbia
 */

// Do not delete these lines
	if (isset($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');
	
	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e('Эта запись защищена паролем. Введите пароль для просмотра комментариев.'); ?></p> 
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<?php if ( have_comments() ) : ?>
    <a name="comments"></a>
	<h3><?php comments_number(__('Комментариев нет'), __('Один комментарий'), __('Комментариев - %'));?></h3>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>

	<ol class="commentlist">



<?php wp_list_comments(array('avatar_size' => '55', 'callback' => 'suburbia_comments'  )); ?>


	</ol>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments"><?php _e('Комментарии закрыты.'); ?></p>

	<?php endif; ?>
<?php endif; ?>


<?php if ( comments_open() ) : ?>

<div id="respond">

<h3 class="clear"><span><?php comment_form_title(__('Ответить'), __('Ответить пользователю %s' )); ?></span>
<?php if ( is_user_logged_in() ) : ?>
    <i class="login"><?php printf(__('Вы вошли как <a href="%1$s">%2$s</a>.'), get_option('siteurl') . '/wp-admin/profile.php', $user_identity); ?> <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e('Выйти из аккаунта'); ?>"><?php _e('Выйти &raquo;'); ?></a></i>
<?php endif; ?>
</h3>

<div id="cancel-comment-reply">
	<small><?php cancel_comment_reply_link() ?></small>
</div>

<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
<p><?php printf(__('Вы должны <a href="%s">войти</a>, чтобы оставлять комментарии.'), wp_login_url( get_permalink() )); ?></p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
<fieldset>
<?php if ( is_user_logged_in() ) : ?>

<textarea name="comment" id="comment" cols="100%" rows="7" tabindex="1" onfocus="if(this.innerHTML=='Comment') this.innerHTML='';">Текст комментария</textarea>

<?php else : ?>

<p><input type="text" name="author" id="author"  onfocus="if(this.value=='Name *') this.value='';" onblur="if(this.value=='') this.value='Name *';" value="Имя *" tabindex="1" <?php // if ($req) echo "aria-required='true'"; ?> /></p>

<p><textarea name="comment" id="comment" cols="100%" rows="7" tabindex="2" onfocus="if(this.innerHTML=='Comment') this.innerHTML='';">Текст комментария</textarea></p>

<p><input type="text" name="email" id="email"  onfocus="if(this.value=='E-Mail *') this.value='';" onblur="if(this.value=='') this.value='E-Mail *';" value="E-Mail *" tabindex="3" <?php // if ($req) echo "aria-required='true'"; ?> /></p>

<p><input type="text" name="url" id="url"  onfocus="if(this.value=='Website') this.value='';" onblur="if(this.value=='') this.value='Website';" value="Сайт" tabindex="4" /></p>

<?php endif; ?>

<!--<p><small><?php printf(__('<strong>XHTML:</strong> Вы можете использовать теги: <code>%s</code>'), allowed_tags()); ?></small></p>-->


<div class="submit"><input name="submit" type="submit" id="submit" tabindex="5" value="Отправить" />
<?php comment_id_fields(); ?>
</div>
<?php do_action('comment_form', $post->ID); ?>
</fieldset>
</form>

<?php endif; // If registration required and not logged in ?>
</div>

<?php endif; // if you delete this the sky will fall on your head ?>
