<?php

class acps_settings_page
{
	
	//Setup blank options variable
    private $options;
	
	//Settings variable
	var $settings;

    //Add menus and init
    public function __construct()
    {
		//Actions
		add_action('admin_enqueue_scripts', array($this,'admin_enqueue_scripts'));
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    //Add plugin page
    public function add_plugin_page()
    {
		//Submenu function ( adds to acps custom post type )
		add_submenu_page('edit.php?post_type=acps', 'Settings Admin', 'Settings', 'edit_posts', basename(__FILE__), array( $this, 'create_admin_page' ));
    }
	
	//Admin enqueue scripts function (includes settings)
	public function admin_enqueue_scripts()
	{
		//Check page
		if( ! $this->validate_page() )
		{
			return;
		}
		
		//Add custom styles
		wp_enqueue_style(array(
			'acps-admin-styles',
			'acps-chzn-styles'
		));
		
		//Add custom scripts
		wp_enqueue_script(array(
			'acps-chzn-scripts',
		));
		
		//Declare settings using filter
		$this->settings = apply_filters('acps/get_settings', 'all');
	
	}

   //Options page callback function
    public function create_admin_page()
    {
        //Set class property
        $this->options = get_option( 'acps_options' );
        ?>
        <div class="wrap acps_settings">
            <div class="icon32 icon32_acps_settings" id="icon_acps"><br></div>
            <h2>Advanced Custom Post Search Settings</h2>           
            <form method="post" class="acps_settings_form" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'acps_settings_group' );   
                do_settings_sections( 'acps-settings-admin' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    //Register and add settings
    public function page_init()
    {       
		
		//Register all settings 
        register_setting(
            'acps_settings_group', // Option group
            'acps_options' // Option name
        );
		
		//Add section settings
        add_settings_section(
            'general_setting_section', 
            '',
            array( $this, 'print_section_info' ),
            'acps-settings-admin'
        );  
		
		//Add Base Results page setting
		add_settings_field(
				'results_page_setting', 
				'Results Base Page',
				array( $this, 'results_page_setting_callback' ),
				'acps-settings-admin',
				'general_setting_section'
		);
		
		//Add form style setting
		add_settings_field(
				'form_styles_setting', 
				'Custom Form Styles',
				array( $this, 'form_styles_setting_callback' ),
				'acps-settings-admin',
				'general_setting_section'
		);

	}

	function validate_page()
	{
		
		global $pagenow;
		
		//Check if page is post new page
		if( in_array( $pagenow, array('edit.php') ) )
		{
			
			//Check post type is acps
			if( isset($_GET['post_type']) && $_GET['post_type'] == 'acps' )
			{
				if( isset($_GET['page']) && $_GET['page'] == 'acps_settings_page.php' )
				{
					$return = true;
				}
				else
				{
					$return = false;
				}
			}
			else
			{
				$return = false;
			}
			
			//Return bool
			return $return;
		}
		
	}
   
    //Print section info
    public function print_section_info()
    {
		//Ensure settings messages are shown
		settings_errors();
		
		print '<p>General settings effect <strong>all</strong> of your search forms.</p>';
		echo '<h3 class="hndle"><span>General Settings</span></h3>';
		
    }
	
	//Results page setting callback function
	public function results_page_setting_callback()
    {
			$acps_all_pages = get_pages();
			$acps_pages = false;
			
			foreach( $acps_all_pages as $acps_page ):
				$acps_pages[$acps_page->ID] = $acps_page->post_title;
			
			endforeach;
			
			echo '<select name="acps_options[acps_results_page]" class="acps_results_page chosen_simple_select" >';
			
			foreach( $acps_pages as $k => $v ):
			
				if( $k == $this->options['acps_results_page'] ):
					echo '<option selected="selected" value="'.$k.'">'.$v.'</option>';
				else:
					echo '<option value="'.$k.'">'.$v.'</option>';
				endif;
			endforeach;
			
			echo '</select>';
			
    }
	
	//Form style callback function
	public function form_styles_setting_callback()
    {
		if(isset( $this->options['acps_form_styles'] ))
		{
			printf(
				'<textarea class="acps_text_input" rows="20" name="acps_options[acps_form_styles]">%s</textarea>',
				isset( $this->options['acps_form_styles'] ) ? esc_attr( $this->options['acps_form_styles']) : ''
			);
		}
		else
		{
			printf(
				'<textarea class="acps_text_input" rows="20" name="acps_options[acps_form_styles]"></textarea>',
				isset( $this->options['acps_form_styles'] ) ? esc_attr( $this->options['acps_form_styles']) : ''
			);
		}
		
	}
	
}

if( is_admin() )
    $acps_settings_page = new acps_settings_page();