<?php
/**
 * @package WordPress
 * @copyright Copyright (C) 2010 pixelthemestudio.ca - All Rights Reserved.
 * @license GPL/GNU
 * @subpackage glowingamber
 
 Static Home Page and Blog: You will need to change the exclude= ID number 
 to the new home page ID so you don't end up with two home page menu items
 
 */
?>

<ul class="menu">
				<?php wp_list_pages('sort_column=menu_order&title_li=&exclude='); // change the exclude= ID number to the new home page ID ?>

				  
	</li>
</ul>