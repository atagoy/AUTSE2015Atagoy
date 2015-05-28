<?php
/**
 * @package WordPress
 * @subpackage Greyzed
 */

/*
Template Name: Links
*/
?>

<?php get_header(); ?>

	<div id="container">	
<?php get_sidebar(); ?>	
	<div id="content" role="main">

	
	<div class="column">


<h2 class="pagetitle">Links:</h2>

<div class="entry">
<ul>
<?php wp_list_bookmarks('title_li=&categorize=0'); ?>
</ul>
</div>

</div>

	</div>
	
<?php get_footer(); ?>
