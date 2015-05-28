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

// register parent class
JLoader::register('ElementFile', ZOO_ADMIN_PATH.'/elements/file/file.php');

/*
	Class: ElementVideo
		The video element class
*/
class ElementVideo extends ElementFile {

	/** @var string */
	var $url = null;

	/*
	   Function: Constructor
	*/
	function ElementVideo() {

		// call parent constructor
		parent::ElementFile();

		// init vars
		$this->type = 'video';
		$this->_table_columns = array('_file' => 'TEXT', '_url' => 'TEXT', '_params' => 'TEXT');

		// set defaults
		$params =& JComponentHelper::getParams('com_media');
		$this->directory = $params->get('image_path');
	}

	/*
		Function: hasValue
			Checks if the element's value is set.

		Returns:
			Boolean - true, on success
	*/
	function hasValue() {
		return !empty($this->file) || !empty($this->url);
	}

	/*
		Function: render
			Renders the element.

	   Parameters:
            $view - current view

		Returns:
			String - html
	*/
	function render($view = ZOO_VIEW_ITEM) {

		// init vars
		$params   =& $this->getParams();
		$width    = $params->get('width', 400);
		$height   = $params->get('height', 300);
		$format   = $params->get('format', '');
		$autoplay = $params->get('autoplay', false);
		$video    = $this->getVideoHTML($format, $width, $height, $autoplay);

		// render layout
		if ($layout = $this->getLayout()) {
			return Element::renderLayout($layout, array('video' => $video));
		}

		return null;
	}

	/*
		Function: loadAssets
			Load elements css/js assets.

		Returns:
			Void
	*/
	function loadAssets() {
		JHTML::script('video.js', 'administrator/components/com_zoo/elements/video/assets/js/');
	}

	/*
	   Function: edit
	       Renders the edit form field.

	   Returns:
	       String - html
	*/
	function edit() {

		// init vars
		$params 	=& $this->getParams();
		$width  	= $params->get('width', 400);
		$height 	= $params->get('height', 300);
		$format 	= $params->get('format', '');
		$autoplay 	= $params->get('autoplay', false);
		$extensions = 'avi|divx|flv|mov|mpg|mp4|wmv|swf';
		$dir    	= trim($this->directory, '/').'/';
		$formats    = $this->getVideoFormats();

		// get fileformat options
		$options = array(JHTML::_('select.option',  '', '- '.JText::_('Select Video Format').' -'));

		foreach ($formats as $vformat => $data) {		
			$options[] = JHTML::_('select.option', $vformat, $data['name']);
		}
		
		// check format js
		$javascript = "var vid = new ElementVideo('element-".$this->name."'); vid.attachEvents();";
		$javascript = "<script type=\"text/javascript\">\n// <!--\n$javascript\n// -->\n</script>\n";

		// create html
		$html  = '<table id="element-'.$this->name.'">';
		$html .= JHTML::_('element.editrow', JText::_('File'), JHTML::_('control.selectfile', JPATH_ROOT.DS.$this->directory, '/^.*('.$extensions.')$/i', $this->name.'_file', $this->file, 'class="file"'));
		$html .= JHTML::_('element.editrow', JText::_('URL'), JHTML::_('control.text', $this->name.'_url', $this->url, 'class="url" size="50" maxlength="255"'));
		$html .= JHTML::_('element.editrow', JText::_('Type'), JHTML::_('select.genericlist', $options, $this->name.'_params[format]', 'class="type"', 'value', 'text', $format).'<span class="notice"><span>');
		$html .= JHTML::_('element.editrow', JText::_('Width'), JHTML::_('control.text', $this->name.'_params[width]', $width, 'maxlength="4"'));
		$html .= JHTML::_('element.editrow', JText::_('Height'), JHTML::_('control.text', $this->name.'_params[height]', $height, 'maxlength="4"'));
		$html .= JHTML::_('element.editrow', JText::_('AutoPlay'), JHTML::_('select.booleanlist', $this->name.'_params[autoplay]', '', $autoplay));
		$html .= $javascript;
		$html .= '</table>';

		return $html;
	}

	/*
	   Function: getVideoHTML
	       Gets the HTML for this videoformat

	   Returns:
	       String - html
	*/
	function getVideoHTML($format, $width, $height, $autoplay) {

		// init vars
		$formats  = $this->getVideoFormats();
		$source   = $this->file ? JURI::root().$this->directory.'/'.$this->file : $this->url;
		$autoplay = $autoplay ? 'true' : 'false';

		if (isset($formats[$format])) {

			// parse source link
			if (isset($formats[$format]['regex'])) {
				if (preg_match($formats[$format]['regex'], $source, $matches)) {
					if (isset($matches[1])) {
						$source = $matches[1];
					}
				}
			}

			// video params
			$params = array("{SOURCE}", "{ID}", "{WIDTH}", "{HEIGHT}", "{AUTOPLAY}");

			// replacements
			$replace = array($source, md5($source), $width, $height, $autoplay);

			return JFilterOutput::ampReplace(str_replace($params, $replace, $formats[$format]['html']));
		}

		return null;
	}

	/*
	   Function: getVideoFormats
	       Return all supported video formats and corresponding html

	   Returns:
	       Array - Formats
	*/
	function getVideoFormats() {
				
		// Flash Video
		$formats['flv']['name'] = 'Flash Video (.flv)';
		$formats['flv']['html'] = '
				<div id="{ID}">
					<p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
				</div>
				<script type="text/javascript">
					swfobject.embedSWF("'.ZOO_ADMIN_URI.'elements/video/assets/player/NonverBlaster.swf", "{ID}", "{WIDTH}", "{HEIGHT}", "7.0.0","", { videoURL: "{SOURCE}", teaserURL: "", allowSmoothing: "true", autoplay: "{AUTOPLAY}", scaleIfFullScreen: "true", showScalingButton: "true" }, { wmode: "transparent", play:"{AUTOPLAY}" });
				</script>';
			
		// Flash
		$formats['swf']['name'] = 'Flash (.swf)';
		$formats['swf']['html'] = '
			<div id="{ID}">
				<p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
			</div>
			<script type="text/javascript">
				swfobject.embedSWF("{SOURCE}", "{ID}", "{WIDTH}", "{HEIGHT}", "7.0.0","", {}, { wmode: "transparent", play:"{AUTOPLAY}" });
			</script>';
			
		// Windows Media Video
		$formats['wmv']['name'] = 'Windows Media Video (.wmv)';
		$formats['wmv']['html'] = '
			<object id="{ID}" width="320" height="286" classid="CLSID:22D6f312-B0F6-11D0-94AB-0080C74C7E95" standby="Loading Windows Media Player components..." type="application/x-oleobject" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,7,1112">
				<param name="filename" value="{SOURCE}">
				<param name="Showcontrols" value="True">
				<param name="autoStart" value="{AUTOPLAY}">
				<embed type="application/x-mplayer2" src="{SOURCE}" name="MediaPlayer" autostart="{AUTOPLAY}" width="{WIDTH}" height="{HEIGHT}"></embed>
			</object>';

		// QuickTime Player HTML
		$quicktime = '
			<script type="text/javascript">
				QT_WriteOBJECT_XHTML("{SOURCE}", "{WIDTH}", "{HEIGHT}", "", "AUTOPLAY", "{AUTOPLAY}", "SCALE", "Aspect");
			</script>';
			
		// QuickTime
		$formats['mov']['name'] = 'QuickTime (.mov)';
		$formats['mov']['html'] = $quicktime;
			
		// MPEG4 Video
		$formats['mp4']['name'] = 'MPEG4 Video (.mp4)';
		$formats['mp4']['html'] = $quicktime;

		// MPEG Video
		$formats['mpg']['name'] = 'MPEG Video (.mpg)';
		$formats['mpg']['html'] = $quicktime;

		// Divx Player HTML
		$divx = '
			<object type="video/divx" data="{SOURCE}" style="width:{WIDTH}px;height:{HEIGHT}px;">
				<param name="type" value="video/divx" />
				<param name="src" value="{SOURCE}" />
				<param name="data" value="{SOURCE}" />
				<param name="codebase" value="{SOURCE}" />
				<param name="url" value="{SOURCE}" />
				<param name="mode" value="full" />
				<param name="pluginspage" value="http://go.divx.com/plugin/download/" />
				<param name="allowContextMenu" value="true" />
				<param name="previewImage" value="{SOURCE}" />
				<param name="autoPlay" value="{AUTOPLAY}" />
				<param name="minVersion" value="1.0.0" />
				<param name="custommode" value="none" />
				<p>No video? Get the DivX browser plug-in for <a href="http://download.divx.com/player/DivXWebPlayerInstaller.exe">Windows</a> or <a href="http://download.divx.com/player/DivXWebPlayer.dmg">Mac</a></p>
			</object>';

		// Divx	
		$formats['divx']['name'] = 'DivX (.divx)';
		$formats['divx']['html'] = $divx;

		// Audio Video Interleave
		$formats['avi']['name'] = 'Audio Video Interleave (.avi)';
		$formats['avi']['html'] = $divx;
			
		// Youtube
		$formats['youtube.com']['name']  = 'Youtube (youtube.com)';
		$formats['youtube.com']['regex'] = '/.*youtube.com.*v=(.*?)$/';		
		$formats['youtube.com']['html']  = '
			<div id="{ID}">
				<p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
			</div>
			<script type="text/javascript">
				swfobject.embedSWF("http://www.youtube.com/v/{SOURCE}&hl=en&fs=1", "{ID}", "{WIDTH}", "{HEIGHT}", "7.0.0","", {}, { wmode: "transparent", quality: "high", play: "{AUTOPLAY}" });
			</script>';

		// Google Video
		$formats['video.google.com']['name']  = 'Google Video (video.google.com)';
		$formats['video.google.com']['regex'] = '/.*video.google.com.*docid=(.*?)[&]/';
		$formats['video.google.com']['html']  = '
			<div id="{ID}">
				<p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
			</div>
			<script type="text/javascript">
				swfobject.embedSWF("http://video.google.com/googleplayer.swf?docid={SOURCE}&hl=en&fs=true", "{ID}", "{WIDTH}", "{HEIGHT}", "7.0.0","", {}, { wmode: "transparent", quality: "high", play: "{AUTOPLAY}" });
			</script>';

		// Vimeo
		$formats['vimeo.com']['name']  = 'Vimeo (vimeo.com)';
		$formats['vimeo.com']['regex'] = '/.*vimeo.com\/(.*?)$/';
		$formats['vimeo.com']['html']  = '
			<div id="{ID}">
				<p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
			</div>
			<script type="text/javascript">
				swfobject.embedSWF("http://www.vimeo.com/moogaloop.swf?clip_id={SOURCE}&server=www.vimeo.com&show_title=1&show_byline=1&show_portrait=0&fullscreen=1", "{ID}", "{WIDTH}", "{HEIGHT}", "7.0.0","", {}, { wmode: "transparent", quality: "high", scale: "showAll", play: "{AUTOPLAY}", allowfullscreen: "true", allowscriptaccess: "always" });
			</script>';

			
		// Liveleak
		$formats['liveleak.com']['name']  = 'Liveleak (liveleak.com)';
		$formats['liveleak.com']['regex'] = '/.*liveleak.com.*i=(.*?)$/';
		$formats['liveleak.com']['html']  = '
			<div id="{ID}">
				<p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
			</div>
			<script type="text/javascript">
				swfobject.embedSWF("http://www.liveleak.com/e/{SOURCE}", "{ID}", "{WIDTH}", "{HEIGHT}", "7.0.0","", {}, { wmode: "transparent", quality: "high", play: "{AUTOPLAY}", allowfullscreen: "true", allowscriptaccess: "always" });
			</script>';


		// Myspace
		$formats['vids.myspace.com']['name']  = 'Myspace Video (vids.myspace.com)';
		$formats['vids.myspace.com']['regex'] = '/.*vids.myspace.com.*videoid=(.*?)$/';
		$formats['vids.myspace.com']['html']  = '
			<div id="{ID}">
				<p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
			</div>
			<script type="text/javascript">
				swfobject.embedSWF("http://lads.myspace.com/videos/vplayer.swf", "{ID}", "{WIDTH}", "{HEIGHT}", "7.0.0","", {}, { wmode: "transparent", quality: "high", play: "{AUTOPLAY}", allowfullscreen: "true", allowscriptaccess: "sameDomain", flashvars: "m={SOURCE}&v=2&type=video" });
			</script>';

		return $formats;
	}

}