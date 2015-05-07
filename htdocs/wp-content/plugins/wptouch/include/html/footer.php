<p class="powered-by-msg">
	<?php
		global $footer_settings;
		$bnc_settings = wptouch_get_settings( 'bncid' );
	?>

	<?php if ( $footer_settings->add_referral_code && $bnc_settings->referral_user_id ) { ?>
		<?php echo sprintf( __( 'Powered by %s%s%s', 'wptouch-pro' ) , '<a href="http://www.wptouch.com/?wptouch_affiliate_id=' . $bnc_settings->referral_user_id . '" target="_blank">', 'WPtouch Mobile Suite for WordPress', '</a>'); ?>
	<?php } else { ?>
		<?php echo sprintf( __( 'Powered by<br/>%s%s%s', 'wptouch-pro' ) , '<a href="http://www.wptouch.com/?utm_campaign=wptouch-powered-by&utm_medium=web" target="_blank">', 'WPtouch Mobile Suite for WordPress ', '</a>'); ?>
	<?php } ?><br />
</p>
