<?php
define('HEADER_IMAGE_WIDTH', '707');
define('HEADER_IMAGE', get_template_directory_uri().'/img/heading.jpg');
define('HEADER_IMAGE_HEIGHT', '200');
define('HEADER_TEXTCOLOR', 'ffffff');

define('BACKGROUND_COLOR', '000');

$content_width = "650";
add_theme_support('automatic-feed-links');
add_theme_support('post-thumbnails');

add_custom_background('cloudy_custom_background');

register_nav_menu('primary', 'Header Menu');

function cloudy_custom_background() {
    /* Get the background image. */
    $image = get_background_image();
    /* If there's an image, just call the normal WordPress callback. We won't do anything here. */
    if ( !empty( $image ) ) {
            _custom_background_cb();
            return;
    }
    /* Get the background color. */
    $color = get_background_color();
    /* If no background color, return. */
    if ( empty( $color ) )
            return;
    /* Use 'background' instead of 'background-color'. */
    $style = "background: #{$color};";
?>
<style type="text/css">body { <?php echo trim( $style ); ?> }</style>
<?php

}

$cloudy_themename = "Cloudy";  
$cloudy_shortname = "cld";  
$cloudy_options = array(
    array(
        "name" => "Message",
        "desc" => "Text to display as welcome message.",
        "id" => $cloudy_shortname."_welcome_message",
        "type" => "textarea"
    ),
);


add_action( 'widgets_init', 'cloudy_widgets_init' );
function cloudy_widgets_init() {
        register_sidebar( array(
        'name'  => 'Sidebar',
        'id'    => 'sidebar',
        'description'   => 'Left Sidebar',
        'before_title'=>'<h3>',
        'after_title'=>'</h3>',
        'before_widget'=>'<div class="box">',
        'after_widget'=>'</div>'
        ) );
}

function cloudy_header_style() {
    ?><style type="text/css">
        #header {
            background: url(<?php header_image() ?>) 0 52px no-repeat;
        }
        #heading a,
        #heading .description {
            color: #<?php header_textcolor();?>;
        }
    </style><?php
}

function cloudy_admin_header_style() {
    ?><style type="text/css">
        #header {
            width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
            height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
        }
    </style><?php
}
add_custom_image_header('cloudy_header_style', 'cloudy_admin_header_style');


# get recent comments
function cloudy_recent_comments($src_count=7, $src_length=60, $pre_HTML='<li><h2>Recent Comments</h2>', $post_HTML='</li>') {
    $comments = get_comments(array(
        'orderby'   => 'comment_date_gmt',
        'order'     => 'DESC',
        'number'    => $src_count,
        'status'    => 'approve',
    ));

    $output = $pre_HTML;
    $output .= "\n<ul>";
    foreach ($comments as $comment) {
        $content = substr(strip_tags($comment->comment_content), 0, $src_length);
        if (strlen($content) == $src_length) {
            $content .= '...';
        }
        $output .= "\n\t<li><div class=\"author\"><a href=\"" . get_permalink($comment->comment_post_ID) . "#comment-" . $comment->comment_ID  . "\">" . $comment->comment_author . "</a></div><div class=\"comment\">" . $content . "</div></li>";
    }
    $output .= "\n</ul>";
    $output .= $post_HTML;

    echo $output;
}

// Use a unique name for identify a stylesheet
wp_register_style('CloudyPrintSheets', get_template_directory_uri().'/print.css', '', 'false', 'print');
wp_register_style('CloudyIESheet', get_template_directory_uri().'/ie.css');

// Prepare the styles for display
function cloudy_styles(){
	wp_enqueue_style( 'CloudyPrintSheets');
        wp_enqueue_style('CloudyIESheet');
}
// Use the appropriate hook to display the stylesheets in the header.
add_action( 'wp_print_styles', 'cloudy_styles' );







add_action( 'admin_init', 'cloudy_options_init' );
add_action( 'admin_menu', 'cloudy_options_add_page' );

function cloudy_options_init(){
    register_setting( 'cloudy_theme_options', 'cloudy_theme_options', 'cloudy_options_validate' );
}

function cloudy_options_add_page() {
    add_theme_page('Cloudy Options', 'Cloudy Options', 'edit_theme_options', 'theme_options', 'cloudy_theme_options_do_page' );
}

function cloudy_theme_options_do_page() {
	if ( ! isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false;
	?>
	<div class="wrap">
		<?php screen_icon(); echo "<h2>" . get_current_theme() . " Cloudy Options</h2>"; ?>
		<?php if ( false !== $_REQUEST['updated'] ) : ?>
                    <div class="updated fade"><p><strong>Options saved</strong></p></div>
		<?php endif; ?>
		<form method="post" action="options.php">
			<?php settings_fields( 'cloudy_theme_options' ); ?>
			<?php $options = get_option( 'cloudy_theme_options' ); ?>
			<table class="form-table">
                            <tr valign="top"><th scope="row">Welcome title</th>
                                <td>
                                    <input id="cloudy_theme_options[welcome_title]" class="regular-text" type="text" name="cloudy_theme_options[welcome_title]" value="<?php esc_attr_e( $options['welcome_title'] ); ?>" />
                                </td>
                            </tr>
                            <tr valign="top"><th scope="row">Welcome Message</th>
                                <td>
                                    <textarea id="cloudy_theme_options[welcome_message]" name="cloudy_theme_options[welcome_message]" rows="5" cols="50"><?php esc_attr_e( $options['welcome_message'] ); ?></textarea>
                                </td>
                            </tr>
                            <tr valign="top"><th scope="row">Welcome Message Author</th>
                                <td>
                                    <input id="cloudy_theme_options[welcome_author]" class="regular-text" type="text" name="cloudy_theme_options[welcome_author]" value="<?php esc_attr_e( $options['welcome_author'] ); ?>" />
                                </td>
                            </tr>
			</table>
			<p class="submit"><input type="submit" class="button-primary" value="Save Options' ); ?>" /></p>
		</form>
	</div>
	<?php
}
function cloudy_options_validate( $input ) {
	$input['welcome_title'] = wp_filter_nohtml_kses( $input['welcome_title'] );
        $input['welcome_message'] = wp_filter_nohtml_kses( $input['welcome_message'] );
	$input['welcome_author'] = wp_filter_nohtml_kses( $input['welcome_author'] );

	return $input;
}

$cloudyThemeOptions = get_option('cloudy_theme_options');
function cloudy_theme_option($option) {
	global $cloudyThemeOptions;
	return $cloudyThemeOptions[$option];
}