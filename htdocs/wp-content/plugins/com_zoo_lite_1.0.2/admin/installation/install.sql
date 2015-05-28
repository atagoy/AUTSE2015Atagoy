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
(1, 'Article', 'article', '<?xml version="1.0" encoding="utf-8"?>\n<type version="1.0.0">\n<params>\n<param type="textarea" name="text" label="Text" description="Enter your article text here. The ''Read more'' button allows you to create a teaser text for your article. If the article has a ''Read more'' break, only the text before the break will be displayed in the catalog/category view. In the item view the full text will be displayed." display="3" default="" feed="1" editor="1" rows="20" cols="60" />\n</params>\n</type>', '');

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__zoo_type_article` (
  `item_id` int(11) NOT NULL,
  `text_value` text,
  PRIMARY KEY (`item_id`)
) TYPE=MyISAM ;