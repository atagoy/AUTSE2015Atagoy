<?php
/**
* @package   Warp Theme Framework
* @file      footer.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

wp_footer();

// output tracking code
echo $this['config']->get('tracking_code');