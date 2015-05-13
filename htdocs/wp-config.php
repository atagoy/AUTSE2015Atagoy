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
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'IYC=sYW8SO:|b|Ry&(}.v4:_k[rhxw)S_UC&RVZxaktJR12#](BWs*OT,cT9?l$q');
define('SECURE_AUTH_KEY',  'nF6}mmWRNwel_ yCI~:s33Faui~GFEZ~`$?7I~V{eGU#yE8<j()*^!f*hfps]{vt');
define('LOGGED_IN_KEY',    '=%aV%P]=s:0z1Ql~%w-AA-.+.V5`S[FMN: ) +3cWa3-K|Pts`ynhzS;H5VD%3G/');
define('NONCE_KEY',        ':@m.Nb[`FUJTRoT:N(?dH~JVHHsm;C!z6)*Y0[::r DN>.<{Y%mL:da%8yzN+7/2');
define('AUTH_SALT',        ';)(mRKM(+jKpkKjWNFJIlf0S=dJ/}-6gjHDoc.6[ Rfh>|8DAEP/1I~|x!o$4,i/');
define('SECURE_AUTH_SALT', '9zsg9c~7J9=|qO}`8-9va[A!?4r15)!Bpuq,B(cqxVRv<uS$(DKM7Hoj`mg6?a?7');
define('LOGGED_IN_SALT',   '@:DBi@b;Bd3 3Ods-v0|d0DAtCj/x|Q[n-L@a^Deo0?5-e5<itWw~+!N+RJL37*d');
define('NONCE_SALT',       'x]9^&LW?:2VeVE|Mt8$&?f:ws^gC-<L=ZlN-ap+hIXvDvnP#lPWwJ2>&*Cy$`Ee9');

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
