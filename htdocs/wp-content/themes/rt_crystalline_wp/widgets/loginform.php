<?php
/**
 * @package   gantry
 * @subpackage widgets
 * @version   1.0 October 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */ 
 
defined('GANTRY_VERSION') or die();

gantry_import('core.gantrywidget');

add_action('widgets_init', array("GantryWidgetLoginForm","init"));

class GantryWidgetLoginForm extends GantryWidget {
    var $short_name = 'loginform';
    var $wp_name = 'gantry_loginform';
    var $long_name = 'Gantry Login Form';
    var $description = 'Gantry Login Form Widget';
    var $css_classname = 'widget_gantry_loginform';
    var $width = 200;
    var $height = 400;

    function init() {
        register_widget("GantryWidgetLoginForm");
    }
    
    function render_title($args, $instance) {
    	global $gantry;
    	if($instance['title'] != '') :
    		echo $instance['title'];
    	endif;
    }

    function render($args, $instance){
        global $gantry, $current_user;
	    ob_start();
	    ?>
    	
    	<?php if(!is_user_logged_in()) : ?>
		
			<form id="form-login" action="<?php echo wp_login_url(get_bloginfo('url')); ?>" method="post">
				<fieldset class="input">
					<p id="form-login-username">
						<label for="modlgn_username"><?php _re('Username'); ?></label><br />
						<input id="modlgn_username" type="text" name="log" class="inputbox" alt="username" size="18" />
					</p>
					<p id="form-login-password">
						<label for="modlgn_passwd"><?php _re('Password'); ?></label><br />
						<input id="modlgn_passwd" type="password" name="pwd" class="inputbox" size="18" alt="password" />
					</p>
					<p id="form-login-remember">
						<input type="checkbox" name="rememberme" class="checkbox" alt="<?php _re('Remember Me'); ?>" />
						<label class="rememberme"><?php _re('Remember Me'); ?></label>
					</p>
					<div class="readon"><input type="submit" value="<?php _re('Login'); ?>" class="button" name="submit" /></div>
				</fieldset>				
				<ul>
					<li>
						<a href="<?php echo wp_lostpassword_url(); ?>"><?php _re('Forgot your password?'); ?></a>
					</li>
					<?php if(get_option('users_can_register')) : ?>
					<li>
						<a href="<?php bloginfo('wpurl'); ?>/wp-register.php"><?php _re('Register'); ?></a>
					</li>
					<?php endif; ?>
				</ul>				
			</form>
			
		<?php else : ?>
		
			<form id="form-login" action="<?php echo wp_logout_url(get_bloginfo('url')); ?>" method="post">
				<fieldset class="input">
					
					<div class="user-greeting">
						<b><?php echo $instance['user_greeting']; ?> <?php echo $current_user->display_name; ?></b><br />
					</div>
					<div class="readon"><input type="submit" name="Submit" class="button" value="Log out" /></div>
		
				</fieldset>
			</form>
		
		<?php endif; ?>
    	
	    <?php 
	    
	    echo ob_get_clean();
	
	}
}