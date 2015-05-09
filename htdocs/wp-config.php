<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'serler');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'resu');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'g <dPS_0KazEfi_hq7i*/#|%>%OCB]nNHE(N14a}Lh)6RM0nr0NTca2=T3CnO@Tg');
define('SECURE_AUTH_KEY',  '!NF:s/y9JO>^rI3c6zD+?IjvTh^tI72))Y,BYLsZr WkR9%3En%DGsSSMpDIF>F|');
define('LOGGED_IN_KEY',    'OWI([y9T|F2(vyK6*:wf:2NMf0C&|tRu0|U9+{sgxG}#F6c6nsv-G}d053&+M5`h');
define('NONCE_KEY',        ':|Dhuk@ETaWP.d. -o5I,8&cGM!sE1(K)gt~;rYMGFOp:JdT1(kNf;&AhqRYCG{3');
define('AUTH_SALT',        '<V0G>B].VQ+q|!//,kZ+%ueBQxrarCQd82wTte]N1aNKa4#MZo)v4R~{J*7A@~SE');
define('SECURE_AUTH_SALT', '3p4vUBir?!+WI7:+NAlj)[*5Rc9ROVG4C|k-1V,rdsI:|Cz&IQ_T%O|N$6,M0k*b');
define('LOGGED_IN_SALT',   'Y0&^|0>>YD?K+)?MGPh-tD?Ptk-(LIhwD-B$.^^v0S^H?:,+-!B8R-CTe+/-rAED');
define('NONCE_SALT',       '7s$RJ?+jybwS;939TXgT$!Y~kV#@c._nL}20 Z=Azk-OPW7]|M9%eO,G}RS0gi].');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'se_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
