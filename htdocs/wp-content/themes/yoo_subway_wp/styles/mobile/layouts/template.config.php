<?php
/**
* @package   Subway
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// add css
$this['asset']->addFile('css', 'css:base.css');
$this['asset']->addFile('css', 'css:layout.css');
$this['asset']->addFile('css', 'css:modules.css');
$this['asset']->addFile('css', 'css:tools.css');
$this['asset']->addFile('css', 'css:system.css');
$this['asset']->addFile('css', 'css:extensions.css');
$this['asset']->addFile('css', 'css:custom.css');
if (($color = $this['config']->get('color1')) && $this['path']->path("css:/color1/$color.css")) { $this['asset']->addFile('css', "css:/color1/$color.css"); }
if (($color = $this['config']->get('color2')) && $this['path']->path("css:/color2/$color.css")) { $this['asset']->addFile('css', "css:/color2/$color.css"); }
$this['asset']->addFile('css', 'css:style.css');
$this['asset']->addFile('css', 'css:mobile.css');

// add js
$this['asset']->addFile('js', 'js:warp.js');

// set body css classes
$body_classes  = $this['system']->isBlog() ? 'isblog ' : 'noblog ';
$body_classes .= $this['config']->get('page_class');
$this['config']->set('body_classes', $body_classes);