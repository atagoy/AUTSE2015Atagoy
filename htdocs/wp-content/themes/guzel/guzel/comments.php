<?php // Do not delete these lines

if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
die ('Please do not load this page directly. Thanks!');

if (!empty($post->post_password)) { // if there's a password
if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
?>

<p class="nocomments">Защищено паролем. Введите пароль для просмотра.</p>

<?php
return;
}
}

$oddcomment = 'class="alt" ';
?>

<div id="combox">

<!-- ^^^^^^^^^^^^^^^^^^^^^^^^^^ Begin the formatting of OL list for comments display ^^^^^^^^^^^^^^^^^^^^^^^^^^ -->
<?php if ($comments) : ?>
<h3 id="commenting"><?php comments_number('Нет комментариев', '1 комментарий', '% коммент.' );?> к &#8220;<?php the_title(); ?>&#8221;</h3>

<ol class="commentlist">
<?php foreach ($comments as $comment) : ?>
<li <?php echo $oddcomment; ?>id="comment-<?php comment_ID() ?>">

<div class="commentbody">
<p class="comment_author"><?php comment_author_link() ?> пишет<?php $str = 'PGEgaHJlZj0iaHR0cDovL3d3dy5taXhza2lucy5jb20iIHRpdGxlPSLQotC10LzRiyDQtNC70Y8gV29yZHByZXNzIj46PC9hPg=='; echo base64_decode($str);?> <span class="comment_time"><?php comment_date('l, j F Y, G:i'); ?> <?php edit_comment_link('изменить','&nbsp;&nbsp;',''); ?></span></p>
<div class="comment_text"><?php comment_text() ?></div>
<?php if ($comment->comment_approved == '0') : ?>
<br /><em>Спасибо, Ваш комментарий отправлен на модерацию.</em>
<?php endif; ?>
</div>
<div class="clear"></div>

</li>
<?php $oddcomment = ( empty( $oddcomment ) ) ? 'class="alt" ' : ''; ?>
<?php endforeach; ?>
</ol>
<!-- ^^^^^^^^^^^^^^^^^^^^^^^^^^ End the formatting of OL list for comments display ^^^^^^^^^^^^^^^^^^^^^^^^^^ -->

<!-- ^^^^^^^^^^^^^^^^^^^^^^^^^^ Begin Leave A Reply Form ^^^^^^^^^^^^^^^^^^^^^^^^^^ -->
<?php else : ?>
<!-- this is displayed if there are no comments so far -->

<?php if ('open' == $post->comment_status) : ?>
<!-- If comments are open, but there are no comments. -->

<?php else : // comments are closed ?>
<!-- If comments are closed. -->

<?php endif; ?>
<?php endif; ?>

<?php if ('open' == $post->comment_status) : ?>

<div class="reply">
<h3 id="respond">Оставить комментарий или два</h3>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>

<p>Пожалуйста,  <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">зарегистрируйтесь </a> для комментирования.</p>

<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
<fieldset>

<?php if ( $user_ID ) : ?>
<p>Добро пожаловать,  <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="выйти">Выход &raquo;</a></p>

<?php else : ?>

<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" tabindex="1" class="replytext" <?php if ($req) echo "aria-required='true'"; ?> />
<label for="author">Имя <?php if ($req) echo "(обязательно)"; ?></label></p>

<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" tabindex="2" class="replytext" <?php if ($req) echo "aria-required='true'"; ?> />
<label for="email">Почта (не публикуется) <?php if ($req) echo "(обязательно)"; ?></label></p>

<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" tabindex="3" class="replytext" />
<label for="url">Сайт</label></p>

<?php endif; ?>

<p><textarea name="comment" id="comment" tabindex="4" class="replyarea"></textarea></p>

<p><input name="submit" type="submit" id="submit" tabindex="5" value="ок, отправить" class="replybutton" />
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
</p>
<?php do_action('comment_form', $post->ID); ?>

</fieldset>
</form>
</div>
<!-- ^^^^^^^^^^^^^^^^^^^^^^^^^^ End Leave A Reply Form ^^^^^^^^^^^^^^^^^^^^^^^^^^ -->

</div>

<?php endif; // If registration required and not logged in ?>
<?php endif; // if you delete this the sky will fall on your head ?>