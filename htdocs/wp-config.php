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
define('AUTH_KEY',         '_3Y|Kk=cWqp6[A.`~Cy#$L 8?$BMsFIO &5,m*G-K)g|2|hE K&o^2}M#~.PoqN`');
define('SECURE_AUTH_KEY',  '8 4;Zhm-p!;XBH~K+z#(BEf+s~Y?w(o*msCRrC5DuUZHE7;wjzj;%(g.]9Y@Xd*4');
define('LOGGED_IN_KEY',    'reQw(CS6JVlW02[vgQ`?lO,oYt?}S:v^R&HHV+!;fE@?y I-d{ha1[6!U^[oJe>y');
define('NONCE_KEY',        'BcBr./.M1:8|U<ad|J0O 8U7g+b,p}S&`rh[#A3utdACAj*-Ybc<|zSi6o#e>td$');
define('AUTH_SALT',        'lP--ke;Nt;/ffFFa)/Y=tl +xE*}J-Ybl.c#Tr*R_-]._*;C1|wnd874p|H!+x^s');
define('SECURE_AUTH_SALT', 'f0fb/X,V>x4R!.+{!@#7(8UC>FJVv+bPS#|b4.:d<]tf.~]wW#S,s]:6c;1o|1Jq');
define('LOGGED_IN_SALT',   'caeq(|+2}lAUE*j;s|Y-g53W>=e:lt!Sz0 x7Q(~~%j=za&1Zp@rFAj_+pA.u+Li');
define('NONCE_SALT',       'L(868`JTO@|#PE)`?SLY0[@8,EtU;?NaSgAmv1,,Z+dhc>Hid8h(9c|!aa6T!^h|');

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
