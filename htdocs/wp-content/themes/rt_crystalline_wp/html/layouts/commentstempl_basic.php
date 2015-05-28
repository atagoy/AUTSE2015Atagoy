<?php
/**
 * @version   1.0 October 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('GANTRY_VERSION') or die();

gantry_import('core.gantrylayout');

/**
 *
 * @package gantry
 * @subpackage html.layouts
 */
class GantryLayoutCommentsTempl_Basic extends GantryLayout {
    var $render_params = array(
        'commentLayout' => 'basic'
    );

    function render($params = array()) {
        global $wp_query, $withcomments, $post, $wpdb, $id, $comment, $user_login, $user_ID, $user_identity, $overridden_cpage;
        global $gantry;

        $fparams = $this->_getParams($params);
        $comment_layout_name = 'comment_'.$fparams->commentLayout;
        $layout = $gantry->_getLayout($comment_layout_name);
        $className = 'GantryLayout'.ucfirst($comment_layout_name);


        // Do not delete these lines

        ob_start();
 
        if (post_password_required()) { ?>
        
            <div class="alert">
            	<div class="typo-icon">
            		<?php _re('This post is password protected. Enter the password to view comments.') ?>
            	</div>
            </div>
        
            <?php return ob_get_clean();
        }
                
        ?>
        
        <!-- You can start editing here. -->
        
        <div id="jc">
        
        <?php if (have_comments()) : ?>
        
        <div id="comments">
        
            <h2 class="title comments-title"><?php _re('Comments'); ?></h2>
            
            <div class="comments-list">
            
				<?php wp_list_comments(array('style'=>'div','callback'=>array($className, 'render_comment'),'reply_text'=>_r('Reply'))); ?>
           
            </div>
            
            <?php if(($post->comment_count > get_option('comments_per_page')) && (int)get_option('page_comments') === 1) : ?>
            
            <div class="rt-pagination nav">
                <div class="alignleft"><?php next_comments_link('&laquo; ' . _r('Older Comments')); ?></div>
                <div class="alignright"><?php previous_comments_link(_r('Newer Comments') . ' &raquo;') ?></div>
                <div class="clear"></div>
            </div>
            
            <?php endif; ?>
            
		</div>
            
        <?php else : // this is displayed if there are no comments so far     ?>
        
            <?php if (comments_open()) : ?>
            
                <!-- If comments are open, but there are no comments. -->
                
            <?php else : // comments are closed ?>
            
                <!-- If comments are closed. -->
                
                <div class="attention">
                    <div class="typo-icon"><?php _re('Comments are closed.'); ?></div>
                </div>
                
            <?php endif; ?>
            
        <?php endif; ?>
        
        <!-- RESPOND -->
        
        <?php if (comments_open()) : ?>
        
        <div id="respond">

            <h2 class="title comments-title"><?php comment_form_title(_r('Leave a Reply'), _r('Leave a Reply to %s')); ?></h2>

            <div class="cancel-comment-reply">
                <small><?php cancel_comment_reply_link(); ?></small>
            </div>
            
            <?php if (get_option('comment_registration') && !is_user_logged_in()) : ?>
            
                <div class="attention">
                    <div class="typo-icon">
                    	<?php _re('You must be'); ?> <a href="<?php echo wp_login_url(get_permalink()); ?>"><?php _re('logged in'); ?></a> <?php _re('to post a comment.'); ?>
                    </div>
                </div>
                
            <?php else : ?>
            
                <!-- Begin Form -->
                
                <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="comments-form" onsubmit="if(url.value=='<?php _re('Website'); ?>') url.value='';">
                
                    <?php if (is_user_logged_in()) : ?>
                    
                        <p>
                        	<?php _re('Logged in as'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>.
                            <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _re('Log out of this account'); ?>"><?php _re('Log out'); ?> &raquo;</a>
                        </p>
                               
                    <?php else : ?>
                       
                        <p>
                            <input type="text" name="author" id="author" onblur="if(this.value=='') this.value='<?php _re('Name (Required)'); ?>';"
                                   onfocus="if(this.value=='<?php _re('Name (Required)'); ?>') this.value='';"
                                   value="<?php _re('Name (Required)'); ?>" size="22"
                                   tabindex="1" <?php if (isset($req)) echo "aria-required='true'"; ?> />
                        </p>
                        
                        <p>
                            <input type="text" name="email" id="email"
                                   onblur="if(this.value=='') this.value='<?php _re('E-mail (Required)'); ?>';"
                                   onfocus="if(this.value=='<?php _re('E-mail (Required)'); ?>') this.value='';"
                                   value="<?php _re('E-mail (Required)'); ?>" size="22"
                                   tabindex="2" <?php if (isset($req)) echo "aria-required='true'"; ?> />
                        </p>
                        
                        <p>
                            <input type="text" name="url" id="url" onblur="if(this.value=='') this.value='<?php _re('Website'); ?>';" onfocus="if(this.value=='<?php _re('Website'); ?>') this.value='';" value="<?php _re('Website'); ?>" size="22" tabindex="3"/>
                        </p>
                        
                    <?php endif; ?>
                    
                    <!--<p><small><strong>XHTML:</strong> You can use these tags: <code><?php echo allowed_tags(); ?></code></small></p>-->
                    
                    <p style="margin: 0;">
                        <textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4"></textarea>
                    </p><br/>
                    
                    <div id="comments-form-buttons">
						<div id="comments-form-send" class="readon">
							<button class="button" type="submit" name="submit" tabindex="5" id="submit"><?php _re('Send'); ?></button>
						</div>
						<div style="clear:both;"></div>
					</div>
    
                   

                    <div class="clear"></div>
              
                    <?php comment_id_fields(); ?>
                    <?php do_action('comment_form', $post->ID); ?>
              
                </form>
                
            <!-- End Form -->
            
            <?php endif; // If registration required and not logged in ?>
            
            </div>
            
        <?php endif; // if you delete this the sky will fall on your head ?>
        
        </div>
        
        <?php return ob_get_clean();
    }
}