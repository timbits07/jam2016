<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'jam2016');

/** MySQL database username */
define('DB_USER', 'jam2016_dev');

/** MySQL database password */
define('DB_PASSWORD', 'Z5)okGRS@1D[');

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
define('AUTH_KEY',         'QYcilw/q>(?4sse=}qzx-XQ9d|+OAu3u_4Xt<+Ft{X@R`dUJ9xE6$Xf[{66#yf4C');
define('SECURE_AUTH_KEY',  'Dhxp&vP-M$?_<.C~;YmjXqRONq5l3H;Kix*E%l$se`~OXEPWn To{{gG;yY>CZ40');
define('LOGGED_IN_KEY',    '/}{KU:+9EILplQN.JjRF9sr6r+m~KF@Y%ri48Jx]- FiosK!g<u,}^^(|&5X~-To');
define('NONCE_KEY',        'k%^1c`$SsdB7T&rn2mBb{M-7-rNt|BJR&X:AE@n~?Bxv.-cOiuQx747JNAraZkTr');
define('AUTH_SALT',        'C*;wL+Z8~V-!jkxvkVU8`^o{@H=7c&w6K!:|!PqkWV,Q5s^%6@@:g>+*Wxe-HgXG');
define('SECURE_AUTH_SALT', 'C:{hnA%{ Rj`Y,T}M5)Xv<#;2vWe:~ml+;^>as7o$Fl,i{)B_ORe8zjjMKd@<$^X');
define('LOGGED_IN_SALT',   'l`$3`8;4ZX2Tk(@lo+-xUXTvakcSm|_s*?T-a>nO^;)Lp.(KH jRMRhuqRfb]Yc)');
define('NONCE_SALT',       '9cqB+8kA{+/#{:;GvDC`hy{j07F??Cq `2n,-5bBs+H(~{:<|zcE*WGc*+!SAw)~');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
