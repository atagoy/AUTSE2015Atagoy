<?php
if ( ! class_exists( 'WP_Customize_Control' ) )
    return NULL;

/**
 * Class to create a custom layout control
 */
class Layout_Picker_Custom_Control extends WP_Customize_Control
{
      /**
       * Render the content on the theme customizer page
       */
      public function render_content()
       {
            $imageDirectory = '/inc/admin/img/';

            $finalImageDirectory = '';

            if(is_dir(get_stylesheet_directory().$imageDirectory))
            {
                $finalImageDirectory = get_stylesheet_directory_uri().$imageDirectory;
            }

            ?>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<label>
	            <img src="<?php echo $finalImageDirectory; ?>1col.png" alt="Full Width" />
		        <input type="radio" value="full-width" name="<?php echo esc_attr( $this->id ); ?>" <?php $this->link(); checked( $this->value(), "full-width"); ?> />
		        <br/>
	        </label>

        	<label>
	           	<img src="<?php echo $finalImageDirectory; ?>2cl.png" alt="Left Sidebar" />
	        	<input type="radio" value="sidebar-content" name="<?php echo esc_attr( $this->id ); ?>" <?php $this->link(); checked( $this->value(), "sidebar-content"); ?> />
	        	<br/>
	        </label>
			
        	<label>
	        	<img src="<?php echo $finalImageDirectory; ?>2cr.png" alt="Right Sidebar" />
	        	<input type="radio" value="content-sidebar" name="<?php echo esc_attr( $this->id ); ?>" <?php $this->link(); checked( $this->value(), "content-sidebar"); ?> />
	        	<br/>
        	</label>
			
			<label>
	            <img src="<?php echo $finalImageDirectory; ?>3cl.png" alt="3 Column - Sidebar Sidebar Content" />
		        <input type="radio" value="sidebar-sidebar-content" name="<?php echo esc_attr( $this->id ); ?>" <?php $this->link(); checked( $this->value(), "sidebar-sidebar-content"); ?> />
		        <br/>
	        </label>
			
			<label>
	            <img src="<?php echo $finalImageDirectory; ?>3cm.png" alt="3 Column - Sidebar Content Sidebar" />
		        <input type="radio" value="sidebar-content-sidebar" name="<?php echo esc_attr( $this->id ); ?>" <?php $this->link(); checked( $this->value(), "sidebar-content-sidebar"); ?> />
		        <br/>
	        </label>
			
			<label>
	            <img src="<?php echo $finalImageDirectory; ?>3cr.png" alt="3 Column - Content Sidebar Sidebar" />
		        <input type="radio" value="content-sidebar-sidebar" name="<?php echo esc_attr( $this->id ); ?>" <?php $this->link(); checked( $this->value(), "content-sidebar-sidebar"); ?> />
		        <br/>
	        </label>
			
            <?php
       }
}
?>