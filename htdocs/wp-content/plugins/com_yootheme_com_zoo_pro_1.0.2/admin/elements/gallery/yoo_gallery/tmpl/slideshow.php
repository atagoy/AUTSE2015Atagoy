<?php
/**
* @package   Zoo Component
* @version   1.0.2 2009-03-21 18:54:57
* @author    jSharing http://jsharing.com
* @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// add javascript and css
if ($this->includeOnce('YOO_GALLERY_SLIDESHOW')) {
	$document =& JFactory::getDocument();
	$document->addScript($this->uri.'lib/slideshow/slideshow_packed.js');
	$document->addStyleSheet($this->uri.'lib/slideshow/slideshow.css');
}

// add spotlight javascript
if ($spotlight && $this->includeOnce('YOO_GALLERY_JS')) $document->addScript($this->uri.'gallery.js');

// init vars
$container_id = $gallery_id.'-con';
$thumb_class  = $gallery_id.'-thumb';
$a_attribs    = 'class="'.$thumb_class.'"';
list($width, $height) = @getimagesize($thumbs[0]['img_file']);

?>
<div class="<?php echo $style.' '.$thumb_style; ?>">
	<div id="<?php echo $gallery_id; ?>" class="yoo-gallery <?php echo $style; ?>">
	
		<div id="<?php echo $container_id; ?>" class="slideshow-bg" style="width:<?php echo $width; ?>px;height:<?php echo $height; ?>px;"></div>
		<div class="thumbnails">
		<?php 
			for ($j=0; $j < count($thumbs); $j++) :
				$thumb = $thumbs[$j];
				include($tmpl_thumb);
 			endfor;
		?>
		</div>
		
	</div>
</div>
<script type="text/javascript">
  	window.addEvent('domready', function(){
		<?php if ($spotlight) echo "var fx = new YOOgalleryfx('$gallery_id');"; ?>
		var show = new SlideShow('<?php echo $container_id; ?>', '<?php echo $thumb_class; ?>', { wait: 5000, effect: '<?php echo $effect; ?>', duration: 1000, loop: true, thumbnails: true });
		show.play();
	});
</script>