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
class GantryLayoutComment_Basic extends GantryLayout {
    var $render_params = array(
        'comment' => null,
        'depth' => 0,
        'args' => array()
    );

    function render($params = array()) {
        global $gantry;
        $fparams = $this->_getParams($params);
    }

    function render_comment($comment, $args, $depth){
        ob_start();
        $GLOBALS['comment'] = $comment;
        
        $commentBoxIndentStyle = ' avatar-indent';
        
        ?>
        
        <div <?php comment_class(); ?> id="comment-item-<?php comment_ID() ?>">
            <div id="comment-<?php comment_ID(); ?>" class="rok-comment-entry">
            
            	<div class="comment-info">
				
					<div class="comment-avatar"><?php echo get_avatar($comment, $size = 32); ?></div>
					<div class="clear"></div>

					<h5><?php _re('Posted On'); ?></h5>
					<span class="comment-date"><?php echo get_comment_date('M d, Y'); ?></span>		
				
					<h5><?php _re('Posted By'); ?></h5>
					<?php echo get_comment_author_link(); ?>
				
				</div>
            
            	<div class="comment-box<?php echo $commentBoxIndentStyle; ?>">
					<div class="comment-body" id="comment-body-<?php comment_ID() ?>">
					
						<div class="comment-body-top">
							<div class="cbt-1"></div>
							<div class="cbt-2"></div>
							<div class="cbt-3"></div>
						</div>
						
						<div class="comment-body-middle">
							
							<?php if ($comment->comment_approved == '0') : ?>
               
               					<div class="attention">
               						<div class="typo-icon">
	               						<?php _ge('Your comment is awaiting moderation.') ?>
               						</div>
               					</div>
            
            				<?php endif; ?>
            
          					<?php comment_text(); ?>

							<div class="comments-buttons">
								<span class="cbutton">
									<span class="cbutton-end"></span>
									<?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
								</span>
							</div>
							
						</div>
						
						<div class="comment-body-bottom">
							<div class="cbt-1"></div>
							<div class="cbt-2"></div>
						</div>
						
					</div>
				</div>
				<div class="clear"></div>

            </div>
            
        <?php
        echo ob_get_clean();
        return;
    }
}