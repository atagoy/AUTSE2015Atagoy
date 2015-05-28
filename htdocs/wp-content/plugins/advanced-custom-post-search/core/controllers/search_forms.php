<?php
//Search forms aka edit.php?post_type=acps
class acps_search_forms
{
	//Construct function
	function __construct()
	{	
		//Admin scripts	
		add_action('admin_menu', array($this,'admin_menu'));
		add_action('admin_footer', array($this,'admin_footer'));
	}
	
	//Admin menu
	function admin_menu()
	{
		//Check we are on the right page
		if( ! $this->validate_page() )
		{
			//Otherwise stop
			return;
		}
		
		//Declare settings using filter
		$this->settings = apply_filters('acps/get_settings', 'all');
		
		//Add custom styles
		wp_enqueue_style(array(
			'acps-admin-styles'
		));
		
	}
	
	//Validate post page
	function validate_page()
	{
		//Wordpress global variable 'pagenow'
		global $pagenow;
		
		//Check if page is post new page
		if( in_array( $pagenow, array('edit.php') ) )
		{
			//Check post type is acps
			if( isset($_GET['post_type']) && $_GET['post_type'] == 'acps' )
			{
				//Make sure we aren't on a settings page
				if( isset($_GET['page']) && $_GET['page'] == 'acps_settings_page.php' )
				{
					//If we are, returns false
					$return = false;
				}
				else
				{
					//Page is correct, return true
					$return = true;
				}
			}
			else
			{
				//Page incorrect, returns false
				$return = false;
			}
			
			//Return validation value
			return $return;
		}
		
	}
	
	//Check if user has any post types
	function has_post_types()
	{
		//Get all [non-standard] post types
		$not_default = array(
		'public'   => true,
		'_builtin' => false
		);
		
		//Set up post types array
		$posttypes = get_post_types($not_default);
		
		//Check if the array is empty
		if( empty($posttypes) )
		{
			//Return false if empty
			return false;	
		}
		else
		{
			//Return true if not empty
			return true;
		}
	}
	
	function admin_footer()
	{
		//Only put this one the search forms page using validate page
		if( $this->validate_page() )
		{
		?>
        
        <script type="text/html" id="acps-right-col">
			<div id="acps-right-col">
				<div class="creare-box">
					<div class="creare-box-header">
						<div class="creare-box-inner">
							<h3 class="h2">Advanced Custom Post Search</h3>
							<p>Version <?php echo $this->settings['version']; ?></p>
							<img src="<?php echo $this->settings['dir']; ?>images/spud2.png" alt="ACPS Spud" class="acps-spud" />
							<img src="<?php echo $this->settings['dir']; ?>images/creare-logo.png" alt="Crearegroup" class="creare-box-logo" />
							
						</div>
					</div>
					<div class="creare-box-footer">
						<div class="creare-box-inner">
							<p>By <a href="http://uk.linkedin.com/pub/shane-welland/51/676/6b8" target="_blank">Shane Welland</a> on behalf of UK Design & SEO Agency - <a href="http://www.creare.co.uk/" target="_blank">Creare</a></p>
						</div>
					</div>
				</div>
				<div class="creare_footer">
	<a href="https://twitter.com/shanewelland" class="twitter-follow-button" data-show-count="false" data-dnt="true">Follow @shanewelland</a>
	<div class="follow_space"></div> 
	<a href="https://twitter.com/crearegroup" class="twitter-follow-button" data-show-count="false" data-dnt="true">Follow @crearegroup</a>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
</div>
			</div>
        </script>
        <script type="text/javascript">
			(function($){
				
				// wrap
				$('#wpbody .wrap').attr('id', 'acps-search_forms');
				$('#acps-search_forms').wrapInner('<div id="acps-left-col" />');
				$('#acps-search_forms').wrapInner('<div id="acps-columns" />');
				
				//banner
				$('#acps-columns').prepend( $('#acps-right-col').html() );
				<?php if( ! $this->has_post_types() ) { ?>
				$('#acps-right-col').addClass('no_post_types');
				$('#acps-left-col > h2 > a').hide();
				<?php } ?>
				
				//move titles
				$('#acps-left-col > .icon32').insertBefore('#acps-columns');
				$('#acps-left-col > h2').insertBefore('#acps-columns');
				
			})(jQuery);
		</script>
        <?php
		}
		
		//If you have no post types setup, display warning
		if( $this->validate_page() && ! $this->has_post_types() )
		{
		?>
			<script type="text/html" id="acps-custom-post-type-warning">
				<div class="acps_error"><p><strong>Insufficient setup</strong> - You have no custom post types created</p></div>
            </script>
            
            <script type="text/javascript">
				(function($){
					
					$('#acps-left-col').prepend( $('#acps-custom-post-type-warning').html() );
					
				})(jQuery);
			</script>
		<?php
		}
	}
	
}
$acps_search_forms = new acps_search_forms();