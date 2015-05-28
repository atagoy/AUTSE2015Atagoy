<div class="left_col">	
										
						<div class="left_menu">
							<ul id="categories">	
								<?php t_show_catwithdesc(); ?>	
							</ul>
						</div>
						
						
										                    <script type="text/javascript">
	window.addEvent('domready', function() {
		var mySlideModules = new FlashStockSlide($('moduleslide2'), {
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
				height: $('moduleslider2-size').getCoordinates().height,
				width: 328
			},
			arrows: true,
			tabsPosition: 'bottom',
			seed: 'slider2'
			});
	});
                    </script>
					
						<div class="sidebar-wg" id="popular-post">
							<div id="pop-title"><h2>Popular post</h2></div>
							<div id="moduleslider2-size">
                                <div id="moduleslide2">
									
									<?php t_show_popular(); ?>

                                </div>
                            </div>
							<div id="popular-bot"></div>
						</div>
						
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>
								<div class="sidebar-wg" id="widget_tag_cloud">
										<?php include( TEMPLATEPATH . '/searchform.php'); ?>
								</div>
							
								<?php if ( function_exists('wp_tag_cloud') ) : ?>
							<div class="sidebar-wg" id="widget_tag_cloud">
								<div class="sidebar-head"><h2>Tag Cloud</h2></div>
									<?php wp_tag_cloud(''); ?>
							</div>
							<?php endif; ?>	
						<?php endif; ?>
						
					</div>
