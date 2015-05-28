<?php
/**
* @package   Revista Nueva
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
if (($background = $this['config']->get('background')) && $this['path']->path("css:/background/$background.css")) { $this['asset']->addFile('css', "css:/background/$background.css"); }
$this['asset']->addFile('css', 'css:mobile.css');
$this['asset']->addFile('css', 'css:style.css');

// add js
$this['asset']->addFile('js', 'js:warp.js');

// set body css classes
$body_classes  = $this['system']->isBlog() ? 'isblog ' : 'noblog ';
$body_classes .= $this['config']->get('page_class');
$this['config']->set('body_classes', $body_classes);