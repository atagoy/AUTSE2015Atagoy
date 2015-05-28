<div id="nm-wp-login">
	
<form name="loginform" id="loginform" action="<?php echo wp_login_url(); ?>" method="post">
	<p>
		<label for="user_login"><?php _e('Username', 'nm-wpregisration') ?><br>
		<input type="text" name="log" id="user_login" class="input" value="" size="20"></label>
	</p>
	<p>
		<label for="user_pass"><?php _e('Password', 'nm-wpregisration') ?><br>
		<input type="password" name="pwd" id="user_pass" class="input" value="" size="20"></label>
	</p>
		<p class="forgetmenot"><label for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever"><?php _e('Remember Me', 'nm-wpregisration') ?> </label></p>
	<p class="submit">
		<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php _e('Log In', 'nm-wpregisration') ?>">
		<input type="hidden" name="testcookie" value="1">
	</p>
</form>

<p id="nav">
<a rel="nofollow" href="<?php echo wp_registration_url(); ?> "><?php _e('Register', 'nm-wpregisration') ?></a> | 	<a href="javascript:lost_password();" title="<?php _e('Password Lost and Found', 'nm-wpregisration') ?>"><?php _e('Lost your password?', 'nm-wpregisration') ?></a>
</p>
<div id="div-reset-password" style="display:none">
<label for="txt-reset-password"><?php _e('Email:', 'nm-wpregisration') ?><br>
<input type="email" id="txt-reset-password" placeholder="<?php _e('Enter your email address...', 'nm-wpregisration') ?>"></label>
<p>
<input type="button" value="<?php _e('Reset', 'nm-wpregisration') ?>" onclick="nm_wp_reset_password()">
</p>
<span id="nm-doing-reset"></span>
	<?php wp_nonce_field('doing_login','nm_wpregistration_nonce');?>
</div>

	</div>