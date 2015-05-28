<!DOCTYPE html>
<html <?php language_attributes( 'html' ); ?>>

<head>
<?php wp_head(); // Hook required for scripts, styles, and other <head> items. ?>
</head>

<body <?php hybrid_attr( 'body' ); ?>>

	<div id="container">

		<div class="skip-link">
			<a href="#content" class="screen-reader-text"><?php _e( 'Skip to content', 'inception' ); ?></a>
		</div><!-- .skip-link -->

		<?php
		$phone_info 	= get_theme_mod( 'inception_phone_info' );
		$phone 			= sanitize_text_field($phone_info);
		$email_info 	= get_theme_mod( 'inception_email_info' );
		$email			= sanitize_email($email_info); // Added layer of security from e-mail harvesters
		$location_info  = get_theme_mod( 'inception_location_info' );
		$location 		= sanitize_text_field($location_info);
		
		?>

		<header <?php hybrid_attr( 'header' ); ?>>
				
			<div class="header-info-bar">
			
				<div class="container">
				
					<div class="pull-left info-icons">
					
						<ul>
						<?php if(!empty($phone)):?>
						<li><a href="tel:<?php echo $phone_info;?>"><?php echo $phone;?></a></li>
						<?php endif;?>
						
						<?php if(!empty($email_info)):?>
						<li><a href="mailto:<?php echo antispambot($email,1); ?>"><?php echo antispambot($email); ?></a></li>
						<?php endif;?>
						
						<?php if(!empty($location)):?>
						<li><i class="fa fa-location-arrow"></i> <?php echo $location;?></li>
						<?php endif;?>
						</ul>
						
					</div>
					
					<div class="pull-right social-icons">
						<?php hybrid_get_menu( 'social-header' ); // Loads the menu/social-header.php template. ?>
					</div>
					
				</div>
				
			</div>
				
			<div id="main-header" class="container">
			
				<div <?php hybrid_attr( 'branding' ); ?> class="pull-left header-left-section">
					
					<?php if ( get_theme_mod( 'inception_logo' ) != "" ) : // If there's a logo?>
					
					<div class="header-logo">
						
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php echo get_theme_mod( 'inception_logo' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"></a>
						
					</div>
					
					<?php endif; // End check for logo?>
					
					
					<div class="header-text">
					<?php 
					if ( get_theme_mod( 'inception_site_title', '1' ) == '1') {
						hybrid_site_title(); 
					}
					if ( get_theme_mod( 'inception_site_description', '0' ) == '1') {
						hybrid_site_description();
					}
					?>
					</div>
				</div><!-- #branding -->
				
				<div id="main-menu" class="pull-right header-right-section">
					
					<?php hybrid_get_menu( 'primary' ); // Loads the menu/primary.php template. ?>
					
				</div>
			</div>	
		</header><!-- #header -->

		<?php if ( get_header_image() ) : // If there's a header image. ?>
		
		<section id="intro" data-speed="6" data-type="background">
		</section>

		<?php endif; // End check for header image. ?>

		<div id="#site-content" class="site-content clearfix">
		
			<div class="container">
				<?php hybrid_get_menu( 'breadcrumbs' ); // Loads the menu/breadcrumbs.php template. ?>
					<div class="row">