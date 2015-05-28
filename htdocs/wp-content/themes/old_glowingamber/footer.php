<?php
/**
 * @package WordPress
 * @copyright Copyright (C) 2010 pixelthemestudio.ca - All Rights Reserved.
 * @license GPL/GNU
 * @subpackage glowingamber
 */
?>

<!-- Group with 3 columns -->
<?php if ( function_exists('dynamic_sidebar') ) { ?>
          <?php if ( is_active_sidebar('Bottom3 One') || is_active_sidebar('Bottom3 Two') || is_active_sidebar('Bottom3 Three') ) { // checks to see if there is a widget ?>
          
			<div class="bottomcolumnwrapper">
        
              <?php if ( function_exists('dynamic_sidebar') && is_active_sidebar('Bottom3 One') ) { 
                  echo '<div id="bottom3-one">';
                  dynamic_sidebar('Bottom3 One');
                  echo '</div>';
              } ?>                

              <?php if ( function_exists('dynamic_sidebar') && is_active_sidebar('Bottom3 Two') ) { 
                  echo '<div id="bottom3-two">';
                  dynamic_sidebar('Bottom3 Two');
                  echo '</div>';
              } ?>      

              <?php if ( function_exists('dynamic_sidebar') && is_active_sidebar('Bottom3 Three') ) { 
                  echo '<div id="bottom3-three">';
                  dynamic_sidebar('Bottom3 Three');
                  echo '</div>';
              } ?>
			          
          </div>
          <?php } ?>
      <?php } ?>
<div class="clearfix"></div>

<!-- Group with 4 columns -->
<?php if ( function_exists('dynamic_sidebar') ) { ?>
          <?php if ( is_active_sidebar('Bottom4 One') || is_active_sidebar('Bottom4 Two') || is_active_sidebar('Bottom4 Three') || is_active_sidebar('Bottom4 Four') ) { ?>
          
			<div class="bottomcolumnwrapper">
        
              <?php if ( function_exists('dynamic_sidebar') && is_active_sidebar('Bottom4 One') ) { 
                  echo '<div id="bottom4-one">';
                  dynamic_sidebar('Bottom4 One');
                  echo '</div>';
              } ?>                

              <?php if ( function_exists('dynamic_sidebar') && is_active_sidebar('Bottom4 Two') ) { 
                  echo '<div id="bottom4-two">';
                  dynamic_sidebar('Bottom4 Two');
                  echo '</div>';
              } ?>      

              <?php if ( function_exists('dynamic_sidebar') && is_active_sidebar('Bottom4 Three') ) {
                  echo '<div id="bottom4-three">';
                  dynamic_sidebar('Bottom4 Three');
                  echo '</div>';
              } ?>
			  
			  <?php if ( function_exists('dynamic_sidebar') && is_active_sidebar('Bottom4 Four') ) {
                  echo '<div id="bottom4-four">';
                  dynamic_sidebar('Bottom4 Four');
                  echo '</div>';
              } ?>
			          
          </div>
          <?php } ?>
      <?php } ?>
<div class="clearfix"></div>
<!-- Banner for the bottom of the page -->
<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Bottom Banner')) : ?><?php endif; ?>
<br />
	</div><!-- end ctop2 -->
</div><!-- end ctop -->

<div id="cbottom"></div>
    <div id="footerwrapper">
	<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Footermenu')) : ?><?php endif; ?>
	<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer')) : ?><?php endif; ?>
	</div><div style="display:none"><a href="http://uniq-themes.ru/">wordpress themes</a></div>
<?php wp_footer(); ?>
</body>
</html>