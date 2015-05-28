<?php
/*
 * Get Custom Field Values plugin shortcode code.
 *
 * Copyright (c) 2004-2015 by Scott Reilly (aka coffee2code)
 *
 */

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'c2c_GetCustomFieldValuesShortcode' ) && class_exists( 'c2c_GetCustomWidget' ) ) :

class c2c_GetCustomFieldValuesShortcode {
	public $name           = 'shortcode_get_custom_field_values';
	public $shortcode      = 'custom_field';
	public $title          = '';
	public $widget_handler = '';
	public $widget_base    = '';

	/**
	 * Constructor.
	 */
	public function __construct( $widget_handler ) {
		$this->title          = __( 'Get Custom Field Values - Shortcode' );
		$this->widget_handler = $widget_handler;
		$this->widget_base    = 'widget-' . $this->widget_handler->id_base;
		$this->shortcode      = apply_filters( 'c2c_get_custom_field_values_shortcode', $this->shortcode );

		add_shortcode( $this->shortcode,         array( $this, 'shortcode' ) );
		add_action( 'load-post.php',             array( $this, 'register_post_page_hooks' ) );
		add_action( 'load-post-new.php',         array( $this, 'register_post_page_hooks' ) );
		add_filter( 'default_hidden_meta_boxes', array( $this, 'default_hidden_meta_boxes' ), 10, 2 );
	}

	/**
	 * Filters/actions to hook on the admin post.php page.
	 *
	 * @since 3.4
	 */
	public function register_post_page_hooks() {
		add_action( 'admin_print_footer_scripts', array( $this, 'admin_js' ) );
		add_action( 'do_meta_boxes',              array( $this, 'do_meta_box' ), 10, 3 );
	}

	/**
	 * Register meta box
	 *
	 * By default, the shortcode builder is present for all post types. Filter
	 * 'c2c_get_custom_field_values_post_types' to limit its use.
	 *
	 * @since 3.4
	 *
	 * @param string  $post_type The post type
	 * @param string  $type      The mode for the meta box (normal, advanced, or side)
	 * @param WP_Post $post      The post
	 */
	public function do_meta_box( $post_type, $type, $post ) {
		$post_types = apply_filters( 'c2c_get_custom_field_values_post_types', get_post_types() );
		if ( ! in_array( $post_type, $post_types ) ) {
			return;
		}

		add_meta_box( $this->name, $this->title, array( $this, 'form' ), $post_type, 'side', 'core' );
	}

	/**
	 * Hides the shortcode wizard by default.
	 *
	 * @since 3.5
	 *
	 * @param array     $hidden List of all hidden metaboxes
	 * @param WP_Screen $screen Screen object.
	 */
	public function default_hidden_meta_boxes( $hidden, $screen ) {
		if ( ! in_array( $this->name, $hidden ) ) {
			$hidden[] = $this->name;
		}

		return $hidden;
	}

	/**
	 * Shortcode handler.
	 *
	 * @param array  $atts    The shortcode attributes
	 * @param string $content The text wrapped by the shortcode's opening and closing tags.
	 * @return string
	 */
	public function shortcode( $atts, $content = null ) {
		$defaults = array();

		foreach ( $this->widget_handler->config as $opt => $values ) {
			$defaults[ $opt ] = isset( $values['default'] ) ? $values['default'] : '';
		}

		$atts2 = shortcode_atts( $defaults, $atts );

		foreach ( array_keys( $this->widget_handler->config ) as $key ) {
			$$key = $atts2[ $key ];
		}

		$ret = '';

		if ( $post_id && ! $this_post ) {
			if ( 'current' == $post_id ) {
				$ret = c2c_get_current_custom( $field, $before, $after, $none, $between, $before_last );
			} elseif ( $random ) {
				$ret = c2c_get_random_post_custom( $post_id, $field, $limit, $before, $after, $none, $between, $before_last );
			} else {
				$ret = c2c_get_post_custom( $post_id, $field, $before, $after, $none, $between, $before_last );
			}
		} else {
			if ( $this_post ) {
				$ret = c2c_get_custom( $field, $before, $after, $none, $between, $before_last );
			} elseif ( $random ) {
				$ret = c2c_get_random_custom( $field, $before, $after, $none );
			} else {
				$ret = c2c_get_recent_custom( $field, $before, $after, $none, $between, $before_last, $limit );
			}
		}

		// If either 'id' or 'class' attribute was defined, then wrap output in span
		if ( ! empty( $ret ) && ! ( empty( $id ) && empty( $class ) ) ) {
			$tag = '<span';
			if ( ! empty( $id ) ) {
				$tag .= ' id="' . esc_attr( $id ) . '"';
			}
			if ( ! empty( $class ) ) {
				$tag .= ' class="' . esc_attr( $class ) . '"';
			}
			$tag .= ">$ret</span>";
			$ret = $tag;
		}

		return $ret;
	}

	/**
	 * Outputs the JS for the shortcode wizard.
	 */
	public function admin_js() {
		echo <<<JS
		<script type="text/javascript">
			jQuery.noConflict();
			var {$this->name} = function () {}

			{$this->name}.prototype = {
				options           : {},
				generateShortCode : function() {
					var content = this['options']['content'];
					delete this['options']['content'];

					var attrs = '';
					jQuery.each(this['options'], function(name, value){
						if (value != '') {
							attrs += ' ' + name + '="' + value + '"';
						}
					});
					return '[{$this->shortcode}' + attrs + ' /]';
				},
				sendToEditor      : function(f) {
					var collection = jQuery(f).find("input[id^={$this->widget_base}]:not(input:checkbox), \
										input[id^={$this->widget_base}]:checkbox:checked, \
										textarea[id^={$this->widget_base}]");
					var \$this = this;
					collection.each(function () {
						var name = this.name.substring(this.name.lastIndexOf('[')+1, this.name.length-1);
						if (\$this['options'][name] == undefined)
							\$this['options'][name] = this.value;
						else
							\$this['options'][name] += ', '+this.value;
					});

					// Delete between and before_last values if a limit of 1 was specified (since there will be at most 1)
					if ( \$this['options']['limit'] == '1' ) {
						delete \$this['options']['between'];
						delete \$this['options']['before_last'];
					}

					send_to_editor(this.generateShortCode());
					/* Delete data after generating shortcode so that the form can be used to generate another shortcode */
					collection.each(function () {
						var name = this.name.substring(this.name.lastIndexOf('[')+1, this.name.length-1);
						delete \$this['options'][name];
					});
					return false;
				}
			}

			var admin_{$this->name} = new {$this->name}();
		</script>

JS;
	}

	/**
	 * Outputs shortcode wizard form.
	 */
	public function form() {
		$this->widget_handler->form( array(), array( 'title' ) );
		echo '<p class="submit">';
		echo '<input type="button" class="button-primary" onclick="return admin_' . $this->name . '.sendToEditor(this.form);" value="' . __( 'Send shortcode to editor' ) . '" />';
		echo '</p>';
	}

} // end class c2c_GetCustomFieldValuesShortcode

function register_c2c_GetCustomFieldValuesShortcode() {
	new c2c_GetCustomFieldValuesShortcode( $GLOBALS['wp_widget_factory']->widgets['c2c_GetCustomWidget'] );
}

add_action( 'init', 'register_c2c_GetCustomFieldValuesShortcode', 11 );

endif; // end if !class_exists()
