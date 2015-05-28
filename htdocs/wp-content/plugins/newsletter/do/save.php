<?php

header('Content-Type: text/html;charset=UTF-8');
header('X-Robots-Tag: noindex,nofollow,noarchive');
header('Cache-Control: no-cache,no-store,private');
include '../../../../wp-load.php';

$user = NewsletterSubscription::instance()->save_profile();
NewsletterSubscription::instance()->show_message('profile', $user, NewsletterSubscription::instance()->options['profile_saved']);
