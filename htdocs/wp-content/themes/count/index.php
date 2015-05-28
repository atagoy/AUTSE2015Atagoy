<?php 
	get_header(); 
?>

	<div class="contents clear"> 
		<?php 
            require_once 'includes/left-side.php';			
        ?>
        <div class="middle-content">                   
            <?php 
                if (have_posts()) : 
				?>
					<h2>Блог</h2>
				<?php
				
				while (have_posts()) : the_post(); 			
                	include (TEMPLATEPATH . "/post.php"); 
            	endwhile; 
			
				if(function_exists('wp_pagenavi')) { 
                    wp_pagenavi(); 
                } 
            ?>  			            
			<?php else : ?>
        		<h2>Не найдено</h2>
        		<p class="sorry">К сожалению, по вашему запросу ничего не найдено.</p>
	    	<?php endif; ?> 
        </div>
    	
    
		<?php 
            get_sidebar();
        ?>    	
    </div>
</div>
    
<?php 
	get_footer(); 
?>
