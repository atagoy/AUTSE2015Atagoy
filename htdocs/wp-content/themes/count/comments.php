<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments">Пожалуйста, введите пароль для просмотра комментариев.</p>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<div class="discussion-board">
	<?php if ( have_comments() ) : ?>
    <h2 id="comments"><?php comments_number('Отзывов нет', 'Один отзыв', 'Отзывов (%)' );?> на &laquo;<?php the_title(); ?>&raquo;</h2>
    <div class="commentlist clear">
    	<ul>
        <li>
		<?php wp_list_comments('callback=theme_comment'); ?>
        </ul>
    </div>

     <?php else : // this is displayed if there are no comments so far ?>
    
        <?php if ('open' == $post->comment_status) : ?>
            <!-- If comments are open, but there are no comments. -->
    		<span style="color:#FF0000;"><strong>Отзывов нет</strong></span>
         <?php else : // comments are closed ?>
            <!-- If comments are closed. -->
            <p class="nocomments">Комментарии закрыты.</p>
    
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php if ( comments_open() ) : ?>
                     
<div class="fillupform clear" id="respond">
	<h2><?php comment_form_title( 'Ваш отзыв', 'Ваш отзыв на %s' ); ?></h2>
    
    <div class="cancel-comment-reply">
        <?php cancel_comment_reply_link(); ?>
    </div>

    <p class="note-msg">
        Ваш e-mail никогда не будет опубликован. Обязательные поля отмечены * <br />
        Комментарии модерируются, и это может вызвать задержку их публикации.
    </p>
    
    <?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
    
    		<p>Вы должны <a href="<?php echo wp_login_url( get_permalink() ); ?>">войти</a>, чтобы оставлять комментарии.</p>
    
	<?php else : ?>
        
    <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
    
    <?php if ( is_user_logged_in() ) : ?>    
    <p>
    	Вы вошли как <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>.
    	<a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Выйти с этого аккаунта">Выйти &raquo;</a>
    </p>
    <ul>
    <?php else : ?>    
		
            <li>
                <label for="author">Имя <?php if ($req) echo "*"; ?></label>
                <input type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" tabindex="1" <?php if ($req) echo "area-required='true'"; ?> />                
            </li>
            <li>
                <label for="email">E-mail <?php if ($req) echo "*"; ?></label>
                <input type="text" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" tabindex="2" <?php if ($req) echo "area-required='true'"; ?> />	
            </li>
            <li>
                <label for="url">Сайт</label>
                <input type="text" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" tabindex="3" />	
            </li>
            
            <?php endif; ?>
        
            <!--<p><small><strong>XHTML:</strong> Вы можете использовать следующие теги: <code><?php echo allowed_tags(); ?></code></small></p>-->
            <li>
                <label for="comment" style="vertical-align:top;">Сообщение</label>	
                <textarea name="comment" id="comment" cols="50%" rows="10" tabindex="4"></textarea>
            </li>            
            <li>
                <label style="width:70px;">&nbsp;</label>	
                <input name="submit" type="submit" id="submit" tabindex="5" value="Отправить" class="btnpost" />                    
                <?php comment_id_fields(); ?>
            </li>
            <li><?php do_action('comment_form', $post->ID); ?></li>
            
            
            <?php endif; // If registration required and not logged in ?>
            
            <?php endif; // if you delete this the sky will fall on your head ?>  
</ul>
    </form>  
</div>



