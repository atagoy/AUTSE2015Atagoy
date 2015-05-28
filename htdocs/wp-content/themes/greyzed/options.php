<?php
$options = array (

array(    "name" => "Greyzed by <a href='http://www.theforge.co.za' target='_blank'>The Forge Web Creations</a>",
        "type" => "title"),

array(    "type" => "open"),

array(  "name" => "Social Links for Posts?",
        "desc" => "",
        "id" => $shortname."_theme_social",
        "type" => "checkbox",
        "std" => ""), 	
		
array(    "name" => "Social Groups",
        "type" => "title"),

/* SOCIAL GROUPS */

array(    "name" => "Instructions:<br /><br />You may choose to use 5 social groups for your theme. Please note that if you add more than 5, only the first 5 will be used. Leaving the field blank will exclude the social group.",
        "desc" => "",
        "id" => "",
        "std" => "",
        "type" => "info2"),

array(    "name" => "Facebook:",
        "desc" => "",
        "id" => $shortname."_facebook",
        "std" => "",
        "type" => "texter"),

array(    "name" => "Twitter:",
        "desc" => "",
        "id" => $shortname."_twitter",
        "std" => "",
        "type" => "texter"),

array(    "name" => "Flickr:",
        "desc" => "",
        "id" => $shortname."_flickr",
        "std" => "",
        "type" => "texter"),

array(    "name" => "Linked In:",
        "desc" => "",
        "id" => $shortname."_linkedin",
        "std" => "",
        "type" => "texter"),
		
array(    "name" => "Last.fm:",
        "desc" => "",
        "id" => $shortname."_lastfm",
        "std" => "",
        "type" => "texter"),
		
array(    "name" => "YouTube:",
        "desc" => "",
        "id" => $shortname."_youtube",
        "std" => "",
        "type" => "texter"),				



/* END SOCIAL GROUPS */

array(    "type" => "close"),

array(    "name" => "Statistics Tracking",
        "type" => "title"),

array(    "type" => "open"),

array(    "name" => "Feedburner URL:",
        "desc" => "If you use FeedBurner, you can put your URL above.<br />Eg. <a href='http://feeds2.feedburner.com/theforgeweb' target='_blank'>http://feeds2.feedburner.com/theforgeweb</a>",
        "id" => $shortname."_feedburner",
        "std" => "",
        "type" => "texter2"),

array(    "name" => "Feedburner Email URL:",
        "desc" => "If you use FeedBurner, you can put your <strong>email</strong> URL above.<br />Eg. <a href='http://feedburner.google.com/fb/a/mailverify?uri=theforgeweb&loc=en_US' target='_blank'>http://feedburner.google.com/fb/a/mailverify?uri=theforgeweb&loc=en_US</a>",
        "id" => $shortname."_feedburner_email",
        "std" => "",
        "type" => "texter2"),


array(    "name" => "Google Analytics Code:",
        "desc" => "If you use Google Analytics, you can put your tracking code above.",
        "id" => $shortname."_analytics",
        "std" => "",
        "type" => "textarea"),

array(    "type" => "close")

);
?>
