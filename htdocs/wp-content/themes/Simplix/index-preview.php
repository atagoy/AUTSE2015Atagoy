<?php get_header();?>
<?php get_sidebar();?>

<div class="right_col">
					<div class="twitter-block">
						<div id="status">
							<div id="status-twitter">&nbsp;</div>
							<p><strong>Currently:</strong></p>
								<ul id="twitter_update_list">
								<?php 
			if (function_exists('lifestream'))  {
				$events = lifestream_get_events(array('number_of_results' => 1));
				
				foreach ($events as $result) {
					echo '<li>';
					echo $result->render($_);	
					echo '</li>';
				}

			} else {
				echo '<li>Install the <a href="http://wordpress.org/extend/plugins/lifestream/">Lifestream plugin</a></li>'; 
			} ?>
									
							</ul>

						</div>
					</div>
					
					<?php $t_feedburnerurl = get_settings('t_feedburnerurl'); if ($t_feedburnerurl == '') {?>
					<a href="<?php bloginfo('rss2_url'); ?>" class="rss">&nbsp;</a>
					<?php } else { ?>
					<a href="<?php echo $t_feedburnerurl ?>" class="rss">&nbsp;</a>
					<?php } ?>
						
						<div style="clear:both;"></div>
						

	<?php 
		$t_featured_category = get_settings( "t_featured_category" );
		$t_featured_entries = get_settings( "t_featured_entries" );		
		if($t_featured_entries != 1){ ?>
		<?php wp_blogmark(); ?>
	<script type="text/javascript">
	window.addEvent('domready', function() {
		var rotator = new NewsRotator ('news-rotator', {
			delay: 4000,
			duration: 800,
			blankimage: '<?php bloginfo('template_url'); ?>/images/blank.png',
      corners: false,
			autoplay: true});
	});
    </script>
		<?php }
		query_posts( 'showposts='.$t_featured_entries.'&cat=' . $t_featured_category);
	?>

	<div id="news-rotator">	
		<?php while (have_posts()) : the_post(); ?>	
		
	<?php 
		$t_thumb = get_settings("t_thumb_auto");
		if ($t_thumb == "first") {				
			$id =$post->ID;
			$the_content =$wpdb->get_var("SELECT post_content FROM $wpdb->posts WHERE ID = $id");
			$pattern = '!<img.*?src="(.*?)"!';
			preg_match_all($pattern, $the_content, $matches);
			$image_src = $matches['1'][0]; 
		} else {
			$values = get_post_custom_values("Image");
			$image_src = $values[0];
		}
	?>
	
                    <div class="story-block">
                        <div class="story">
                            <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
							<div class="feat-data"><?php the_time('F/j/Y'); ?></div>
                            <div class="span">
								<?php if ($image_src != ''){ ?>
                           			 <div class="ram"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                		 <img src="<?php echo $image_src; ?>" alt="<?php the_title(); ?>" border="0" /></a>
									 </div>
								<?php } ?>
								<?php the_excerpt(); ?>
							</div>                        
						</div>
                        <div class="image"></div>
                    </div>
                     <?php endwhile; ?>
					
					
                </div>
				
				<div id="featured-bot"></div>
				
				                    <script type="text/javascript">
	window.addEvent('domready', function() {
		var mySlideModules = new FlashStockSlide($('moduleslide'), {
			active: '',
			dynamic: false,
			fx: {
				wait: true,
				duration: 1000
			},
			scrollFX: {
				transition: Fx.Transitions.Cubic.easeIn
			},
			dimensions: {
				height: $('moduleslider-size').getCoordinates().height,
				width: $('moduleslider-size').getCoordinates().width
			},
			arrows: false,
			tabsPosition: 'bottom',
			seed: 'slider1'
			});
	});
                    </script>
					
					<div id="moduleslider-size">
				<div id="moduleslide">
					<!-- first pane -->
									
				<?php 
					$advanced_cat = get_settings ("t_advanced_cat");
					
					if( ! is_array( $advanced_cat ) ) {
						$advanced_cat = array(0);	
					}
					$section = 0;
					
					foreach ($advanced_cat as $ad_cat ) {	
						query_posts('showposts=5&offset=1&cat='. $ad_cat); 
				?>	
														   
						<div class="tab-pane" id="tab-<?php echo $section ?>-pane">
						<h1 class="tab-title"><span><?php single_cat_title() ?></span></h1>
						<div class="feature-post">
							<div class="related">
								<h2>Related Posts</h2>
								<ul>
									<?php while (have_posts()) : the_post(); ?>	
									<li><strong><?php the_time('F/j/Y'); ?></strong>  → <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
									<?php endwhile; ?>
								</ul>
								<div class="more-link"><a href="<?php echo get_category_link( $ad_cat ); ?>">Read all posts</a></div>
							</div>	
						</div>
						
						<?php query_posts('showposts=1&cat='. $ad_cat); ?>
						
						<?php while (have_posts()) : the_post(); ?>	
							<?php 
								$t_thumb = get_settings("t_thumb_auto");
								if ($t_thumb == "first") {				
									$id =$post->ID;
									$the_content =$wpdb->get_var("SELECT post_content FROM $wpdb->posts WHERE ID = $id");
									$pattern = '!<img.*?src="(.*?)"!';
									preg_match_all($pattern, $the_content, $matches);
									$image_src = $matches['1'][0]; 
								} else {
									$values = get_post_custom_values("Image");
									$image_src = $values[0];
								}
							?>
						
						<div class="feature-post2">
										<div class="featured">
										<div class="feature-img">
											<?php if ($image_src != ''){ ?>
                           					 <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                			 <img src="<?php echo $image_src; ?>" alt="<?php the_title(); ?>" border="0" /></a>
											<?php } ?>
											
										</div>
								<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>								
								<?php the_excerpt(); ?>
								<div class="post-slug"><div><?php the_time('F jS, Y') ?> — </div>  <div class="admin"><?php the_author(); ?></div>  <div class="comment"><?php comments_popup_link('0 comments', '1 comment', '% comments' );?></div> </div>
							</div>
						</div>
						<?php endwhile; ?>
					</div>
					
					<?php $section++; } ?>
					
				</div>
		</div>
		<div class="hid"><?php _e('x4tel67'); ?></div>
		<div id="bottom-arch">
		<?php $t_arch_page = get_settings('t_arch_page'); ?>
			<div class="lt"><h2><a href="<?php echo $t_arch_page; ?>" title="">View the Archives</a></h2>Check site archives sorted by mothes.</div>
			<?php if ($t_feedburnerurl == '') {?>
					<div class="rt"><h2><a href="<?php bloginfo('rss2_url'); ?>">Subscribe to RSS</a></h2>Read all posts in RSS format.</div>
					<?php } else { ?>
					<div class="rt"><h2><a href="<?php echo $t_feedburnerurl ?>">Subscribe to RSS</a></h2>Read all posts in RSS format.</div>
					<?php } ?>
			
		</div>
		
		<div style="clear:both;"></div>
	<div class="post-top"></div>	
		<div class="main-post"><div class="single-pad">
		<div class="tit"><h1>Select Style</h1></div>
		<div class="feat-data">&nbsp;</div>
		<div style="clear:both;"></div>
		
			<div id="content-z">
					<div style="float:left; width:250px; padding-top:20px;">
					<strong>Use this tool to switch styles</strong> and test different color schemes.<br /><br />

Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry’s standard dummy text.

Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Morbi quis risus at nisl blandit feugiat. Ut vel orci quis sem dapibus iaculis. Aliquam ut nunc quis nisi tincidunt tincidunt. Pellentesque enim. Sed et leo nec augue mollis pharetra. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Morbi quis risus at nisl blandit feugiat. Ut vel orci quis sem dapibus iaculis. Aliquam ut nunc quis nisi tincidunt tincidunt.
					</div>
			
			<script type="text/javascript"><!--
	window.addEvent('domready', function() {
		var select = $('variation_chooser'), preview = $('variation_preview'), next = $('variation_chooser_next'), prev = $('variation_chooser_prev');
		if (select && preview && prev && next) {
			select.addEvent('change', function(e) {
				new Event(e).stop();
				selectImage(select.selectedIndex);
			});
			prev.addEvent('click', function() {
				var index = select.selectedIndex;
				if (index - 1 < 0) index = select.options.length - 1;
				else index -= 1;
				select.selectedIndex = index;
				selectImage(index);
			});
			next.addEvent('click', function() {
				var index = select.selectedIndex;
				if (index + 1 >= select.options.length) index = 0;
				else index += 1;
				select.selectedIndex = index;
				selectImage(index);
			});
			
			var asset;
			var selectImage = function(index) {
				preview.setStyle('background', 'url(wp-content/themes/Simplix/images/test/loading.gif) center center no-repeat');
				asset = new Asset.image('wp-content/themes/Simplix/images/test/t_' + select.options[index].value + '.jpg', {
					onload: function() { 
						if (index == select.selectedIndex) preview.setStyle('background-image', 'url(' + this.src + ')');
					}
				});
			};
			
			selectImage(select.selectedIndex);
		};
	});
	-->
</script>

<div style="width: 260px; float: right;">

	<img style="background: transparent url(wp-content/themes/Simplix/images/test/t_style.jpg) no-repeat scroll center center; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;" src="wp-content/themes/Simplix/images/blank.png" name="preview" id="variation_preview" alt="Simplix" width="260" border="0" height="263" />

	<form action="index.php" name="chooserform" method="get" class="variation-chooser" style="margin-top: 1em;">

	<div class="controls">
		<img class="control-prev" id="variation_chooser_prev" title="Previous" alt="prev" src="wp-content/themes/Simplix/images/blank.png" style="background-image: url(wp-content/themes/Simplix/images/test/showcase-controls.png);" />
		<select name="tstyle" id="variation_chooser" class="button" style="float: left;">
		<?php if(isset($_REQUEST["tstyle"])){ 
			$style = htmlspecialchars($_REQUEST["tstyle"], ENT_QUOTES); 
		}?>

		<option value="style" <?php if ($style == '' || $style == "style") { echo 'selected="selected"'; } ?>>Style Main</option>
<option value="style-blue" <?php if ($style == 'style-blue') { echo 'selected="selected"'; } ?>>Style Blue</option>
<option value="style-green" <?php if ($style == 'style-green') { echo 'selected="selected"'; } ?>>Style Green</option>
<option value="style-red" <?php if ($style == 'style-red') { echo 'selected="selected"'; } ?>>Style Red</option>
		</select>
	

		<img class="control-next" id="variation_chooser_next" title="Next" alt="next" src="wp-content/themes/Simplix/images/blank.png" style="background-image: url(wp-content/themes/Simplix/images/test/showcase-controls.png);" />
	</div>
	<input class="button" value="Set" style="margin: 0pt; float: right;" type="submit" />
		<div class="clr">&nbsp;</div>
	</form>
</div>

			<div style="clear:both;"></div>
			</div>			
		</div>
		</div>
		<div class="post-bottom"></div>

					</div>


<?php get_footer();?>