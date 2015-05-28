<?php 
/*
Template Name: Login Page
*/
?>
<?php the_post(); ?>

<?php 
	$et_ptemplate_settings = array();
	$et_ptemplate_settings = maybe_unserialize( get_post_meta($post->ID,'et_ptemplate_settings',true) );
	
	$fullwidth = isset( $et_ptemplate_settings['et_fullwidthpage'] ) ? (bool) $et_ptemplate_settings['et_fullwidthpage'] : (bool) $et_ptemplate_settings['et_fullwidthpage'];
?>

<?php get_header(); ?>

<div id="main-content" <?php if($fullwidth) echo ('class="fullwidth"');?>>
	<div id="main-content-wrap" class="clearfix">
		<div id="left-area">
			<?php include(TEMPLATEPATH . '/includes/breadcrumbs.php'); ?>
			<div class="entry post clearfix">							
				<?php if (get_option('inreview_page_thumbnails') == 'on') { ?>
					<?php 
						$thumb = '';
						$width = 222;
						$height = 231;
						$classtext = 'post-thumb';
						$titletext = get_the_title();
						$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext,false,'Single');
						$thumb = $thumbnail["thumb"];
					?>
					
					<?php if($thumb <> '') { ?>
						<div class="post-thumbnail">
							<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext); ?>
							<span class="post-overlay"></span>
						</div> 	<!-- end .post-thumbnail -->
					<?php } ?>
				<?php } ?>
				
				<h1 class="title"><?php the_title(); ?></h1>
				<?php the_content(); ?>
				<?php wp_link_pages(array('before' => '<p><strong>'.__('Pages','InReview').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				
				<div id="et-login">
					<div class='et-protected'>
						<div class='et-protected-form'>
							<form action='<?php echo get_option('home'); ?>/wp-login.php' method='post'>
								<p><label><?php _e('Username','InReview'); ?>: <input type='text' name='log' id='log' value='<?php echo wp_specialchars(stripslashes($user_login), 1) ?>' size='20' /></label></p>
								<p><label><?php _e('Password','InReview'); ?>: <input type='password' name='pwd' id='pwd' size='20' /></label></p>
								<input type='submit' name='submit' value='Login' class='etlogin-button' />
							</form> 
						</div> <!-- .et-protected-form -->
						<p class='et-registration'><?php _e('Not a member?','InReview'); ?> <a href='<?php echo site_url('wp-login.php?action=register', 'login_post'); ?>'><?php _e('Register today!','InReview'); ?></a></p>
					</div> <!-- .et-protected -->
				</div> <!-- end #et-login -->
				
				<div class="clear"></div>
				
				<?php edit_post_link(__('Edit this page','InReview')); ?>			
			</div> <!-- end .entry -->
						
		</div> 	<!-- end #left-area -->

		<?php if (!$fullwidth) get_sidebar(); ?>
	</div> <!-- end #main-content-wrap -->
</div> <!-- end #main-content -->
<?php get_footer(); ?>