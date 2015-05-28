<?php
/**
 * @package WordPress
 * @subpackage Greyzed
 */
?>
	<div id="sidebar" role="complementary">
		
		<!-- begin widgetized sidebar 1 -->	
		<ul>
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar 1') ) : ?>		
		</ul>
		
		<ul>
			<li><h2>Latest Posts</h2>
				<ul>
 					<?php
 					global $post;
 					$myposts = get_posts('numberposts=10');
 					foreach($myposts as $post) :
   					setup_postdata($post);?>
   				
    				<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
 					<?php endforeach; ?>
 				</ul> 

			</li>
			
			<li><h2>Categories</h2>
				<ul>
				<?php wp_list_categories('show_count=1&title_li='); ?>
				</ul>
			</li>
			
		</ul>
		<ul>
			<?php endif; ?>
		</ul>
		<!-- end widgetized sidebar 1 -->	
		
		<!-- begin search -->
		<div class="search-box">
			<form method="get" action="<?php bloginfo('url'); ?>/">
			<input type="text" size="15" class="search-field" name="s" id="s" value="search this site" onfocus="if(this.value == 'search this site') {this.value = '';}" onblur="if (this.value == '') {this.value = 'search this site';}"/><input type="submit"  value="" class="search-go" />
			</form>
		</div>
		<!-- end search -->
		
		<!-- begin widgetized sidebar 2 -->
		<ul>
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar 2') ) : ?>
		</ul>
		
		<ul>
			
			<li><h2>Popular Tags</h2>
				<ul>
				<?php wp_tag_cloud('smallest=8&largest=22&number=30&orderby=count'); ?>
				</ul>
			</li>							
			
			<li><h2>Post Archives</h2>
				<ul>
				<?php wp_get_archives('type=monthly'); ?>
				</ul>
			</li>

		</ul>
		<ul>
			<?php endif; ?>
		</ul>

		<!-- end widgetized sidebar 2 -->
			
	</div>
	

