<?php
/*
 * This code found on http://chrispoole.com/scripts/wordpress-php/wordpress-breadcrumbs/
 * quick and dirty breadcrumb menu
 * initially designed for wordpress
 */

//require_once('../../../../wp-config.php');

function CPbreadcrumbs() {
    $CPtheFullUrl = $_SERVER["REQUEST_URI"];
    $CPurlArray=explode("/",$CPtheFullUrl);
    echo 'You are here : <a href="/">Home</a>';
    while (list($CPj,$CPtext) = each($CPurlArray)) {
        $CPdir='';
        if ($CPj > 1) {
            $CPi=1;
            while ($CPi < $CPj) {
                $CPdir .= '/' . $CPurlArray[$CPi];
                $CPtext = $CPurlArray[$CPi];
                $CPi++;
            }
            if($CPj < count($CPurlArray)-1) echo ' &raquo; <a href="'.$CPdir.'">' . str_replace("-", " ", $CPtext) . '</a>';
        }
    }
    echo wp_title();
}
CPbreadcrumbs();
?>