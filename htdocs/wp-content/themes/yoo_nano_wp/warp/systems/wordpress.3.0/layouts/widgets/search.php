<?php 
/**
* @package   Warp Theme Framework
* @file      search.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// default search
if ($module->position != 'search') {
	get_search_form();
}

// ajax search
if ($module->position == 'search') : ?>
		
<form id="searchbox" action="<?php echo home_url( '/' ); ?>" method="get" role="search">
	<input type="text" value="" name="s" placeholder="<?php _e('search...', 'warp'); ?>" />
	<button type="reset" value="Reset"></button>
</form>

<script type="text/javascript" src="<?php echo $this['path']->url('js:search.js'); ?>"></script>
<script type="text/javascript">
jQuery(function($) {
	$('#searchbox input[name=s]').search({'url': '<?php echo site_url('wp-admin'); ?>/admin-ajax.php?action=warp_search', 'param': 's', 'msgResultsHeader': '<?php _e("Search Results", "warp"); ?>', 'msgMoreResults': '<?php _e("More Results", "warp"); ?>', 'msgNoResults': '<?php _e("No results found", "warp"); ?>'}).placeholder();
});
</script>
<?php endif; ?>