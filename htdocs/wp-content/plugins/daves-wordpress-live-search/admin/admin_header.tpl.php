<?php if ( !defined( 'ABSPATH' ) ) die( "Cannot access files directly." ); ?>

<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'dwls_admin.css' ?>" />
<?php
global $wp_version;
$color_picker_supported = ( floatval($wp_version) >= 3.5 );
$tabs = array(
	// query string param => label
	'settings' => 'Settings',
	'appearance' => 'Appearance',
	'advanced' => 'Advanced',
);
$current_tab = isset( $_REQUEST['tab'] ) ? $_REQUEST['tab'] : 'settings';
?>

<div class="wrap">
<h2><?php _e( "Dave's WordPress Live Search Options", 'dwls' ); ?></h2>
<ul class="subsubsub">
<?php
$tabs_keys = array_keys($tabs);
$last_tab_param = array_pop($tabs_keys);
?>
<?php foreach( $tabs as $tab_param => $tab_label ) : ?>
<li class="<?php echo $tab_param; ?>">
	<a href="<?php echo add_query_arg( 'tab', $tab_param, $_SERVER['REQUEST_URI'] ); ?>" class=" <?php if( $current_tab === $tab_param ) : ?>current<?php endif; ?>"><?php _e( $tab_label, 'dwls' ); ?></a><?php if( $tab_param !== $last_tab_param ) : ?> |<?php endif; ?>
</li>
<?php endforeach; ?>
</ul>
<form method="post" action="">
<input type="hidden" name="tab" value="<?php if(isset($_REQUEST['tab'])) { echo $_REQUEST['tab']; } ?>" />

<?php
if ( function_exists( 'wp_nonce_field' ) )
	wp_nonce_field( 'daves-wordpress-live-search-config' );
?>

<table class="form-table"><tbody>

