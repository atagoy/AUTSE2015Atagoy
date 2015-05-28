<?php
/**
* @package   Warp Theme Framework
* @file      error.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

$filters = array('CSSImportResolver', 'CSSRewriteURL', 'CSSCompressor');

// prepare assets
$assets['base.css'] = $this['asset']->cache('base.css', $this['asset']->createFile('css:base.css'), $filters);
$assets['error.css'] = $this['asset']->cache('error.css', $this['asset']->createFile('css:error.css'), $filters);
$assets['error-ie6.css'] = $this['asset']->cache('error-ie6.css', $this['asset']->createFile('css:error-ie6.css'), $filters);

?>

<!DOCTYPE HTML>
<html lang="<?php echo $this['config']->get('language'); ?>" dir="<?php echo $this['config']->get('direction'); ?>">

<head>
	<title><?php echo $error; ?> - <?php echo $title; ?></title>
	<link rel="stylesheet" href="<?php echo $assets['base.css']->getUrl(); ?>" />
	<link rel="stylesheet" href="<?php echo $assets['error.css']->getUrl(); ?>" />
	<!--[if IE 6]><link rel="stylesheet" href="<?php echo $assets['error-ie6.css']->getUrl(); ?>" /><![endif]-->
</head>

<body id="page" class="page">

	<div class="center error-<?php echo strtolower($error); ?>">

		<h1 class="error">
			<span>
				<?php if (strtolower($error) == 'browser') { ?>
					<a class="chrome" href="http://www.google.com/chrome" title="Download Chrome"></a>
					<a class="firefox" href="http://www.mozilla.com" title="Download Firefox"></a>
					<a class="opera" href="http://www.opera.com" title="Download Opera"></a>
					<a class="safari" href="http://www.apple.com/safari" title="Download Safari"></a>
					<a class="ie" href="http://www.microsoft.com/downloads" title="Download Internet Explorer 9"></a>
				<?php } else { echo $error; } ?>
			</span>
		</h1>
		<h2 class="title"><?php echo $title; ?></h2>
		<p class="message"><?php echo $message; ?></p>

	</div>
	
</body>
</html>