-- --------------------------------------------------------

CREATE TABLE `#__zoo_core_catalog` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `ALIAS_INDEX` (`alias`)
) TYPE=MyISAM ;

-- --------------------------------------------------------

CREATE TABLE `#__zoo_core_category` (
  `id` int(11) NOT NULL auto_increment,
  `catalog_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `parent` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `ALIAS_INDEX` (`alias`),
  KEY `CATALOG_INDEX` (`catalog_id`)
) TYPE=MyISAM ;

-- --------------------------------------------------------

CREATE TABLE `#__zoo_core_category_item` (
  `catalog_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY  (`catalog_id`,`category_id`,`item_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

CREATE TABLE `#__zoo_core_item` (
  `id` int(11) NOT NULL auto_increment,
  `type_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `publish_up` datetime NOT NULL,
  `publish_down` datetime NOT NULL,
  `hits` int(11) NOT NULL,
  `state` tinyint(3) NOT NULL,
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `metadata` text NOT NULL,
  `access` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_by_alias` varchar(255) NOT NULL,
  `search_data` text NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `ALIAS_INDEX` (`alias`),
  KEY `TYPE_INDEX` (`type_id`),
  KEY `PUBLISH_INDEX` (`publish_up`,`publish_down`),
  KEY `STATE_INDEX` (`state`),
  KEY `ACCESS_INDEX` (`access`),
  KEY `CREATED_BY_INDEX` (`created_by`),
  KEY `NAME_INDEX` (`name`),
  FULLTEXT KEY `SEARCH_FULLTEXT` (`name`,`metakey`,`metadesc`,`search_data`)
) TYPE=MyISAM ;

-- --------------------------------------------------------

CREATE TABLE `#__zoo_core_type` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `elements` text NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `ALIAS_INDEX` (`alias`)
) TYPE=MyISAM ;
-- --------------------------------------------------------

INSERT INTO `#__zoo_core_type` (`id`, `name`, `alias`, `elements`, `params`) VALUES
(1, 'Article', 'article', '<?xml version="1.0" encoding="utf-8"?>\n<type version="1.0.0">\n<params>\n<param type="textarea" name="text" label="Text" description="Enter your article text here. The ''Read more'' button allows you to create a teaser text for your article. If the article has a ''Read more'' break, only the text before the break will be displayed in the catalog/category view. In the item view the full text will be displayed." display="3" default="" feed="1" editor="1" rows="20" cols="60" />\n</params>\n</type>', ''),
(2, 'Download', 'download', '<?xml version="1.0" encoding="utf-8"?>\n<type version="1.0.0">\n<params>\n<param type="text" name="description" label="Description" description="Enter a short description about your download file. It will be displayed in the catalog/category and item view." display="3" default="" />\n<param type="download" name="file" label="File" description="Choose a file you would like to provide as a download. For the following file extensions a specific image will be loaded to highlight the extension name: gz, rar, tar, tgz and zip." display="3" directory="images/stories" download_name="Download {filename}" download_limit="0" download_mode="2" secret="a3c3404f" />\n<param type="text" name="version" label="Version" description="Enter the version number of your downloadable file. It will be displayed in the item view." display="3" default="" />\n<param type="text" name="author" label="Author" description="Enter the name of the author of your downloadable file. It will be displayed in the item view." display="3" default="" />\n<param type="text" name="license" label="License" description="Enter the license of your downloadable file. It will be displayed in the item view." display="3" default="" />\n<param type="rating" name="rating" label="Rating" description="Here you can see the average rating and how many users voted." display="1" stars="5" allow_vote="0" />\n<param type="textarea" name="information" label="Information" description="Enter further information for your downloadable file. It will be displayed in the item view." display="3" default="" feed="0" editor="0" rows="20" cols="60" />\n<param type="intensedebate" name="comments" label="Comments" description="If you enable this option the IntenseDebate comments will be injected at the bottom of the item view." display="1" account="" />\n</params>\n</type>', ''),
(3, 'Blog Classic', 'blog_classic', '<?xml version="1.0" encoding="utf-8"?>\n<type version="1.0.0">\n<params>\n<param type="text" name="author" label="Author" description="Enter the name of the author of this article." display="3" default="" />\n<param type="text" name="subheadline" label="Sub Headline" description="You can enter a sub headline for your article. It will be display right below the article name." display="3" default="" />\n<param type="textarea" name="text" label="Text" description="Enter your article text here. The ''Read more'' button allows you to create a teaser text for your article. If the article has a ''Read more'' break, only the text before the break will be displayed in the catalog/category view. In the item view the full text will be displayed." display="3" default="" feed="2" editor="1" rows="20" cols="60" />\n<param type="image" name="image" label="Image" description="You can choose an image which will be displayed in the item view. If no teaser image is selected it will also be displayed in the catalog/category view." display="3" directory="images/stories" />\n<param type="select" name="imageposition" label="Image Position" description="You can choose where your image should be positioned. At the top, bottom, left or right of your article. ''Between'' will display your image at the ''Read more'' break if it exists." display="3" default="" multiple="0">\n	<option name="Top" value="top" />\n	<option name="Bottom" value="bottom" />\n	<option name="Left" value="left" />\n	<option name="Right" value="right" />\n	<option name="Between" value="between" />\n</param>\n<param type="image" name="teaserimage" label="Teaser Image" description="You can choose a teaser image which will be displayed in the catalog/category view to tease users to read your article." display="3" directory="images/stories" />\n<param type="select" name="teaserimageposition" label="Teaser Image Position" description="You can choose where your teaser image shall be positioned. At the top, bottom, left or right of your article." display="3" default="" multiple="0">\n	<option name="Top" value="top" />\n	<option name="Bottom" value="bottom" />\n	<option name="Left" value="left" />\n	<option name="Right" value="right" />\n</param>\n<param type="text" name="imagelink" label="Image Link" description="By default the image in the catalog/category view is linked to the item view and the image in the item view is not linked. By entering an link here you will give the images in both views an external link." display="3" default="" />\n<param type="video" name="video" label="Video" description="You can choose a video which will be displayed in the item view." display="3" directory="images/stories" />\n<param type="select" name="videoposition" label="Video Position" description="You can choose where your video shall be positioned in the item view. At the top or bottom of your article. ''Between'' will display your video at the ''Read more'' break if it exists." display="3" default="" multiple="0">\n	<option name="Top" value="top" />\n	<option name="Bottom" value="bottom" />\n	<option name="Between" value="between" />\n</param>\n<param type="socialbookmarks" name="socialbookmarks" label="Social Bookmarks" description="If you enable this option social bookmarks will be shown at the bottom of your article in the item view." display="1" google="1" technorati="1" yahoo="1" delicious="1" stumbleupon="1" digg="1" facebook="1" reddit="1" myspace="1" live="1" twitter="1" email="1" />\n<param type="intensedebate" name="comments" label="Comments" description="If you enable this option the IntenseDebate comments will be injected at the bottom of your article in the item view." display="1" account="" />\n</params>\n</type>', ''),
(4, 'Product', 'product', '<?xml version="1.0" encoding="utf-8"?>\n<type version="1.0.0">\n<params>\n<param type="textarea" name="description" label="Description" description="Enter your product description here. The ''Read more'' button allows you to create a teaser description for your product. If the article has a ''Read more'' break, only the text before the break will be displayed in the catalog/category view. In the item view only the text after the break will be displayed." display="3" default="" feed="0" editor="1" rows="20" cols="60" />\n<param type="image" name="image" label="Image" description="You can choose an image which will be displayed in the item view. If no image is selected no image will be displayed in the item view." display="1" directory="images/stories" />\n<param type="image" name="teaserimage" label="Teaser Image" description="You can choose a teaser image which will be displayed in the catalog/category view. If no teaser image is selected no image will be displayed in the catalog/category view." display="2" directory="images/stories" />\n<param type="gallery" name="gallery" label="Gallery" description="Type in the path to your image folder the gallery should use. If you use the gallery it will be displayed in the item view." display="1" style="slideshow" effect="fade" thumb="default" order="asc" spotlight="0" width="90" height="0" resize="1" count="0" prefix="thumb_" thumb_cache_dir="thumbs" thumb_cache_time="1440" load_lightbox="0" rel="" />\n<param type="rating" name="rating" label="Rating" description="Here you can see the average rating and how many users voted." display="3" stars="5" allow_vote="0" />\n<param type="intensedebate" name="comments" label="Comments" description="If you enable this option the IntenseDebate comments will be injected at the bottom of your article in the item view." display="1" account="" />\n</params>\n</type>', '');

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__zoo_core_rating` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `element` varchar(255) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `value` tinyint(4) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM ;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__zoo_type_blog_classic` (
  `item_id` int(11) NOT NULL,
  `author_value` varchar(255) DEFAULT NULL,
  `subheadline_value` varchar(255) DEFAULT NULL,
  `text_value` text,
  `image_file` text,
  `image_params` text NOT NULL,
  `imageposition_value` text,
  `teaserimage_file` text,
  `teaserimage_params` text NOT NULL,
  `teaserimageposition_value` text,
  `imagelink_value` varchar(255) DEFAULT NULL,
  `comments_value` varchar(255) DEFAULT NULL,
  `video_file` text,
  `video_url` text,
  `video_params` text,
  `videoposition_value` text,
  `socialbookmarks_value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`item_id`)
) TYPE=MyISAM ;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__zoo_type_download` (
  `item_id` int(11) NOT NULL,
  `information_value` text,
  `version_value` varchar(255) DEFAULT NULL,
  `file_file` text,
  `file_params` text NOT NULL,
  `author_value` varchar(255) DEFAULT NULL,
  `description_value` varchar(255) DEFAULT NULL,
  `license_value` varchar(255) DEFAULT NULL,
  `rating_value` varchar(255) DEFAULT NULL,
  `rating_params` text,
  `comments_value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`item_id`)
) TYPE=MyISAM ;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__zoo_type_product` (
  `item_id` int(11) NOT NULL,
  `description_value` text,
  `image_file` text,
  `image_params` text,
  `teaserimage_file` text,
  `teaserimage_params` text,
  `rating_value` varchar(255) DEFAULT NULL,
  `rating_params` text,
  `comments_value` varchar(255) DEFAULT NULL,
  `gallery_value` text,
  `gallery_params` text,
  PRIMARY KEY (`item_id`)
) TYPE=MyISAM ;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__zoo_type_article` (
  `item_id` int(11) NOT NULL,
  `text_value` text,
  PRIMARY KEY (`item_id`)
) TYPE=MyISAM ;
