<?php
/**
 * @version   1.0 October 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('GANTRY_VERSION') or die();

gantry_import('core.gantrygizmo');

/**
 * @package     gantry
 * @subpackage  features
 */
class GantryGizmoStyleDeclaration extends GantryGizmo {

    var $_name = 'styledeclaration';
    
    function isEnabled(){
        return true;
    }

    function query_parsed_init() {
    
    	global $gantry, $_templateCSSRules;

		$variations = array(
			'main-background' => '#rt-variation .bg3 .module-content, #rt-variation .title3 .module-title',
			'main-text' => '#rt-variation .bg3, #rt-variation .bg3 .title, #rt-variation .title3 .title, #rt-variation .bg3 ul.menu li > a:hover, #rt-variation .bg3 ul.menu li.active > a',
			'main-link' => '#rt-variation .bg3 a, #rt-variation .bg3 .title span, #rt-variation .bg3 .button, #rt-variation .title3 .title span',
			'header-background' => '#rt-variation .bg1 .module-content, #rt-variation .title1 .module-title',
			'header-text' => '#rt-variation .bg1, #rt-variation .bg1 .title, #rt-variation .title1 .title, #rt-variation .bg1 ul.menu li > a:hover, #rt-variation .bg1 ul.menu li.active > a, #rt-navigation li.root .item',
			'header-link' => '#rt-variation .bg1 a, #rt-variation .bg1 .title span, #rt-variation .bg1 .button, #rt-variation .title1 .title span',
			'feature-background' => '#rt-variation .bg2 .module-content, #rt-variation .title2 .module-title',
			'feature-text' => '#rt-variation .bg2, #rt-variation .bg2 .title, #rt-variation .title2 .title, #rt-variation .bg2 ul.menu li > a:hover, #rt-variation .bg2 ul.menu li.active > a',
			'feature-link' => '#rt-variation .bg2 a, #rt-variation .bg2 .title span, #rt-variation .bg2 .button, #rt-variation .title2 .title span',
			'body-background' => '#rt-variation .bg4 .module-content, #rt-variation .title4 .module-title',
			'body-text' => '#rt-variation .bg4, #rt-variation .bg4 .title, #rt-variation .title4 .title, #rt-variation .bg4 ul.menu li > a:hover, #rt-variation .bg4 ul.menu li.active > a',
			'body-link' => '#rt-variation .bg4 a, #rt-variation .bg4 .title span, #rt-variation .bg4 .button, #rt-variation .title4 .title span',
			'bottom-background' => '#rt-variation .bg5 .module-content, #rt-variation .title5 .module-title',
			'bottom-text' => '#rt-variation .bg5, #rt-variation .bg5 .title, #rt-variation .title5 .title, #rt-variation .bg5 ul.menu li > a:hover, #rt-variation .bg5 ul.menu li.active > a',
			'bottom-link' => '#rt-variation .bg5 a, #rt-variation .bg5 .title span, #rt-variation .bg5 .button, #rt-variation .title5 .title span'
		);
		
		$_templateCSSVariations = $variations;
		$_templateCSSRules = array(
			'main-background' => '#rt-main-surround, ' . $variations['main-background'],
			
			'main-text' => '' . $variations['main-text'],
			
			'main-link' => '' . $variations['main-link'],
			
			'header-background' => '#rt-main-header, .menutop ul, .menutop .drop-top, ' . $variations['header-background'],
			
			'header-text' => '#rt-main-header, #rt-main-header .title, #rt-header, #rt-main-header .menutop li > .item, .menutop ul li .item, ' . $variations['header-text'],
			
			'header-link' => '#rt-main-header a, #rt-main-header .title span, ' . $variations['header-link'],
			
			'feature-background' => '#rt-feature, #rt-utility, #roksearch_results, #roksearch_results .rokajaxsearch-overlay, ' . $variations['feature-background'],
			
			'feature-text' => '#rt-feature, #rt-feature .title, #rt-utility, #rt-utility .title, #roksearch_results, #roksearch_results span, ' . $variations['feature-text'],
			
			'feature-link' => '#rt-feature a, #rt-utility a, #rt-feature .title span, #rt-utility .title span, #roksearch_results a, #roksearch_results h3, ' . $variations['feature-link'],
			
			'body-background' => '#rt-mainbody-bg, ' . $variations['body-background'],
			
			'body-text' => '#rt-mainbody-bg, #rt-mainbody-bg .title, #rt-mainbody-bg .rt-article-title, #rt-mainbody-bg ul.menu li > a:hover, #rt-mainbody-bg ul.menu li.active > a, ' . $variations['body-text'],
			
			'body-link' => '#rt-mainbody-bg a, #rt-mainbody-bg .title span, #rt-mainbody-bg .rt-article-title span, ' . $variations['body-link'],
			
			'bottom-background' => '#rt-bottom, #rt-main-footer, ' . $variations['bottom-background'],
			
			'bottom-text' => '#rt-bottom, #rt-bottom .title, #rt-footer, #rt-footer .title, #rt-copyright, #rt-copyright .title, #rt-debug, #rt-debug .title, ' . $variations['bottom-text'],
			
			'bottom-link' => '#rt-bottom a, #rt-bottom .title span, #rt-footer a, #rt-footer .title span, #rt-copyright a, #rt-copyright .title span, #rt-debug a, #rt-debug .title span, ' . $variations['bottom-link']
		);
		
		//various paths
		$gantry_css_path	= $gantry->templatePath.DS.'css'.DS;
		$overlay_hash_path	= $gantry_css_path.'overlay-hash';
		$overlay_css_path	= $gantry_css_path.'overlay.css';
		
		$overlay_css		= '';
		
		if ($gantry->get('styledeclaration-overlaycheck') or $gantry->get('colorchooser-enabled')) {
			//load and init overlays
			foreach ($this->_loadOverlays() as $type => $overlays) {
				foreach($overlays as $key => $overlay) {					
					// headers
					if ($type == 'headers') {
						$overlay_css .= ".header-graphic-" . $overlay['name'] . " {background: url(".$gantry->templateUrl."/images/overlays/".$type."/" . $overlay['file'] . ") 50% 0 no-repeat;}\n";
					} else if ($type == 'patterns') {
					// patterns
						$name = $overlay['name'];
						$overlay_css .= ".header-overlay-" . $name . ", .main-overlay-" . $name . ", .feature-overlay-" . $name . ", .body-overlay-" . $name . ", .bottom-overlay-" . $name . " {background: url(".$gantry->templateUrl."/images/overlays/".$type."/" . $overlay['file'] . ") 50% 0;}\n";
					}
				}
			}
		}
		
		//compare and rewrite overlay file if check enabled
		if ($gantry->get('styledeclaration-overlaycheck') || !file_exists($overlay_css_path)) {
			//hashes
			$overlay_hash = md5($overlay_css);
			
			//read stored hash	
			if (file_exists($overlay_hash_path)) {
				$stored_overlay_hash = fopen($overlay_hash_path, 'r');
			} else {
				$stored_overlay_hash = null;
			}
			
			// if no overlay file exists or hash is different, generate and store new overlay css
			if ($stored_overlay_hash != $overlay_hash or !file_exists($overlay_css_path)) {
				if (is_writable($gantry_css_path)) {
					$overlay_hash_file = fopen($overlay_hash_path, 'w');
					fwrite($overlay_hash_file, $overlay_hash);
					fclose($overlay_hash_file);
					
					$overlay_css_file = fopen($overlay_css_path, 'w');
					fwrite($overlay_css_file,$overlay_css);
					fclose($overlay_css_file);
				} else {
					_re('Unable to write "overlay.css" in the "/css" folder.');
				}
			}
		}
		
		//add overlay css style
		if ((file_exists($overlay_css_path))) {
        	$gantry->addStyle('overlay.css');
		} else {
			$gantry->addInlineStyle($overlay_css);
		}
		
		// IE6 lameness fix
		if ($gantry->browser->name == 'ie' && $gantry->browser->shortversion == '6') {
			foreach($_templateCSSRules as $key => $value) {
				$_templateCSSRules[$key] = str_replace("> ", "", $value);
			}
		}
		
		//process inline css	
		$css = $_templateCSSRules['main-background'] . ' {background:'.$gantry->get('main-background').';}'."\n";
		$css .= $_templateCSSRules['main-text'] . ' {color:'.$gantry->get('main-text').';}'."\n";
		$css .= $_templateCSSRules['main-link'] . ' {color:'.$gantry->get('main-link').';}'."\n";
		
		
		$css .= $_templateCSSRules['header-background'] . ' {background:'.$gantry->get('header-background').';}'."\n";
		$css .= $_templateCSSRules['header-text'] . ' {color:'.$gantry->get('header-text').';}'."\n";
		$css .= $_templateCSSRules['header-link'] . ' {color:'.$gantry->get('header-link').';}'."\n";
		
		$css .= $_templateCSSRules['feature-background'] . ' {background:'.$gantry->get('feature-background').';}'."\n";
		$css .= $_templateCSSRules['feature-text'] . ' {color:'.$gantry->get('feature-text').';}'."\n";
		$css .= $_templateCSSRules['feature-link'] . ' {color:'.$gantry->get('feature-link').';}'."\n";
		
		$css .= $_templateCSSRules['body-background'] . ' {background:'.$gantry->get('body-background').';}'."\n";
		$css .= $_templateCSSRules['body-text'] . ' {color:'.$gantry->get('body-text').';}'."\n";
		$css .= $_templateCSSRules['body-link'] . ' {color:'.$gantry->get('body-link').';}'."\n";
		
		$css .= $_templateCSSRules['bottom-background'] . ' {background:'.$gantry->get('bottom-background').';}'."\n";
		$css .= $_templateCSSRules['bottom-text'] . ' {color:'.$gantry->get('bottom-text').';}'."\n";
		$css .= $_templateCSSRules['bottom-link'] . ' {color:'.$gantry->get('bottom-link').';}'."\n";
			
		//add the custom css style
		$gantry->addInlineStyle($css);
		$this->_disableRokBoxForiPhone();

        $gantry->addTemp('overlays','templatecssvariations', $_templateCSSVariations);
        $gantry->addTemp('overlays','templatecss', $_templateCSSRules);

	}
	
	function _loadOverlays() {
		global $gantry;
		
		$headers_path = $gantry->templatePath . DS . "images" . DS . "overlays" . DS . "headers" . DS;
		$patterns_path = $gantry->templatePath . DS . "images" . DS . "overlays" . DS . "patterns" . DS;
		
		$overlays = array();
		
		$limit = $gantry->get('overlays_list_limit');
		
		$counter = 0;
		if (is_dir($headers_path)) {
		    if ($dh = opendir($headers_path)) {
				$overlays['headers'] = array();
		        while (($file = readdir($dh)) !== false) {
					if (filetype($headers_path . $file) == 'file' && $this->_isImage($file)) {
						if ($counter >= $limit) continue;
						
						$ext = substr($file, strrpos($file, '.') + 1);
						$name = substr($file, 0, strrpos($file, '.'));
						
						$title = str_replace("-", " ", $name);
						$title = ucwords($title);
						
						$overlays['headers'][$title] = array('name' => $name, 'ext' => $ext, 'file' => $name.".".$ext);
						
						$counter++;
					}
				}
		        closedir($dh);
		    }
		}
		
		ksort($overlays['headers']);

		$counter = 0;		
		if (is_dir($patterns_path)) {
		    if ($dh = opendir($patterns_path)) {
				$overlays['patterns'] = array();
		        while (($file = readdir($dh)) !== false) {
					if (filetype($patterns_path . $file) == 'file' && $this->_isImage($file)) {
						if ($counter >= $limit) continue;
						
		            	$ext = substr($file, strrpos($file, '.') + 1);
						$name = substr($file, 0, strrpos($file, '.'));
						
						$title = str_replace("-", " ", $name);
						$title = ucwords($title);
						
						$overlays['patterns'][$title] = array('name' => $name, 'ext' => $ext, 'file' => $name.".".$ext);
						
						$counter++;
		        	}
				}
		        closedir($dh);
		    }
		}	
        
        ksort($overlays['patterns']);
        
        $gantry->addTemp('overlays','overlays',$overlays);		
		return $overlays;
	}
	
	function _isImage($file) {
		$extension = strtolower(substr($file, -4));
		
		return ($extension == '.jpg' || $extension == '.bmp' || $extension == '.gif' || $extension == '.png');
	}
	
	function _disableRokBoxForiPhone() {
		global $gantry;
		
		if ($gantry->browser->platform == 'iphone') {
			$gantry->addInlineScript("window.addEvent('domready', function() {\$\$('a[rel^=rokbox]').removeEvents('click');});");
		}
	}
	
}