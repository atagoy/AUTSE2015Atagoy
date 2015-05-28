<?php
/**
 * @package WordPress
 * @copyright Copyright (C) 2010 pixelthemestudio.ca - All Rights Reserved.
 * @license GPL/GNU
 * @subpackage glowingamber
 */
?>

<?php if ( is_front_page() ) { ?>

<?php } else { ?>
<div id="breadcrumbs"><?php the_breadcrumb(); ?></div>

<?php } ?>

<?php // To add breadcrumbs to frontpage, delete the above php and uncomment the first two slashes below
// <?php the_breadcrumb(); ?>
