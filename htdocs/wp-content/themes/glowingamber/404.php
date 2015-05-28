<?php
/**
 * @package WordPress
 * @copyright Copyright (C) 2010 pixelthemestudio.ca - All Rights Reserved.
 * @license GPL/GNU
 * @subpackage glowingamber
 */

get_header(); ?>

	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="columns">
  <tr>
    <td id="leftcol"><?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Left Column')) : ?><?php endif; ?></td>
    <td id="content">

		<h1 class="center">Error 404 - Not Found</h1>

	<td id="rightcol"><?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Right Column')) : ?><?php endif; ?></td>
  </tr>
</table>

<?php get_footer(); ?>