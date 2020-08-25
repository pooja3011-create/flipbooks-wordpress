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
define('DB_NAME', 'youflips_m2');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         'hDOqR]R9]j_IWzQn} 9gMoQ287&Ym>=W#)) hRM@4=/Pc12^2|R%A;U3Qz}b5E_L');
define('SECURE_AUTH_KEY',  'GYcaT]?jcm^A#LiP>+ko*Y,DsL&mPZ{jo[ZKs2Xti?6k^!0zqmc [KoX)vDKDT}l');
define('LOGGED_IN_KEY',    '`a*jrPpFb|oeXK$&25^Yq;4{v]y/LK;M&lgo-V|P`#mUV{cckC!$^`BrM(<HFy2J');
define('NONCE_KEY',        'iD()W%Ocg!8#T:b]]Yv&>u}I^B)Aq%c*V1&U6^ B1Tth*L[L(@*h#_hGr4)Q]R*2');
define('AUTH_SALT',        ' b`aGpO`Rw)*FgJ[M|^Ghy@|mx#f%7X<RpB]J`v^XNN;O6p}M2| RjJ3]tcb}#%4');
define('SECURE_AUTH_SALT', 'T=LJ@]BT|5f>rC-nL<@ppK{#R6w(<SM8l)T|LpOCpB&6`Z/grEk>@MYpJZQw&2+d');
define('LOGGED_IN_SALT',   '.SVn,*//R{OI#ucXZR#9G}m?:G8I 6[bMes9T4}|FC/7k&Xsv?.]G[V@Um6H=Dhr');
define('NONCE_SALT',       '{itoOSVvBx:mgefuST/9#9es^.K8^(l_D]`~u/umgw-&h_6Uc{gzOQ_i8tCW&@2c');

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

define('FS_METHOD', 'direct');

define('S3_UPLOADS', 'http://s3-us-west-2.amazonaws.com/test-flipbook/');
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
