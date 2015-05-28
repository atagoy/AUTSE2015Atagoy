<?php
if ( function_exists('register_sidebar') )
register_sidebar(array('name'=>'sidebar1',
));
register_sidebar(array('name'=>'sidebar2',
));



function slider_menuparse($input) {
$output =  preg_replace("'page-item-\d+'", 'page-item-11', $input, -1, $count);
$number = 0;

if ($count < 4) 
echo '<!-- THESE AR ETHE LINKS YOU GO TO WHEN YOU CLICK ON A SLIDING DOOR IMAGE-->
				<!-- change the href to look like this: <a href="yourlink.com">     -->
				<li class="bk1"><a href="http://mac-host.com/install.html">About</a></li>
				<li class="bk2"><a href="http://mac-host.com/install.html">Weddings</a></li>
				<li class="bk3"><a href="http://mac-host.com/support">Places</a></li>
				<li class="bk4"><a href="http://mac-host.com/install.html">Food</a></li>
				<li class="bk5"><a href="http://dubbo.org">People</a></li>
				<li class="bk6"><a href="http://macintoshhowto.com">Nature</a></li>
				<li class="bk7"><a href="http://7.barracks.cl">Architecture</a></li>
			';
else {
for ( $counter = $count+1; $counter <= 7; $counter += 1) {
	$output = $output.'<li class="page_item bk'.($counter).'"><a href="" title=""></a></li>';
}
for ( $counter = 1; $counter <= $count; $counter += 1) {
	$output = preg_replace("'page-item-11'", 'bk'.sprintf($counter,u), $output, 1, $count2);
}
echo $output;
}


}


// Let's add the options page.
add_action ('admin_menu', 'sdthememenu');

$sdloc = '../themes/' . basename(dirname($file)); 

function sdthememenu() {
	add_submenu_page('themes.php', 'Picture Perfect', 'Picture Perfect', 5, $sdloc . 'functions.php', 'menu');
}

function menu() {
	load_plugin_textdomain('sdoptions');
	//this begins the admin page
?>


<div class="wrap">

	<h2><?php _e('Picture Perfect Note'); ?></h2>
	
<h2><?php
	$plugins = get_option('active_plugins');
$required_plugin = 'page-links-to/page-links-to.php';
if ( !in_array( $required_plugin , $plugins ) )  _e('Warning: The page-link-to plugin is NOT active.');

?> </h2>

</div>

<div class="wrap">
	<p style="text-align: center;">The Picture Perfect theme works best with the page-links-to plugin installed. You can install and activate it from the plugin's page. Find out more about this plugin from <a href="http://wordpress.org/extend/plugins/page-links-to/">this page</a>.</p>
</div>

<?php } // this ends the admin page ?>

