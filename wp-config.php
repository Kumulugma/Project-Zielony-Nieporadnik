<?php
define( 'WP_CACHE', true );


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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u521951204_nieporadnik' );

/** MySQL database username */
define( 'DB_USER', 'u521951204_nieporadnik' );

/** MySQL database password */
define( 'DB_PASSWORD', '|8lXXcNm' );

/** MySQL hostname */
define( 'DB_HOST', 'mysql' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'UN,@U6tevw^ZxcGF7^nGS#vaU +Q&M[0,U}#A0|<|Scd|#3FR~GH>w9_%K_y<vHt' );
define( 'SECURE_AUTH_KEY',   'lnk=~.Cp*NND|C+Sd}HHdo60NU|>h79]Rs~G_v6B|$Rm_ZxH.Djav]a)E_&QDBZl' );
define( 'LOGGED_IN_KEY',     'b7J,<%gG~`LoH(5+q~ldG/uv%Q@Sn8N,Sy>GA{Sv?b>Z&VFmYmiWT)f^-.(sDG9+' );
define( 'NONCE_KEY',         'iUIw%BGSM~@q6n-8d_fcTa/JTPz/e}yJ?]qFgUj2($5jOF$8Snnj=`YWcfMfe]E;' );
define( 'AUTH_SALT',         '!U%.+U?J)[oeid{U+mx+?P)#,aaP)[J{4!<=BZ({7s8OCql{]0Agy}`cLeIGvi~G' );
define( 'SECURE_AUTH_SALT',  '&%fXd`mt<DOop@_8>hE, WU_{,}g*z4A9e@uMf`6j9R}Zh-3,8n#]vVd#a+tpv7Q' );
define( 'LOGGED_IN_SALT',    'H{tH^c[i(QL/XVAF$R8lp-.oUi bBOsy/KNv!bnS?r[S  @9tJ~yfc*^0l{XXbmw' );
define( 'NONCE_SALT',        'KKnc!|ZLt8%oUiR+Xn^j7Dx_x9&?3`oXhvneS,Emm{3H:,4M079UuEk^Z6rN=,tW' );
define( 'WP_CACHE_KEY_SALT', '>F$kZ)[L3cXtnAvd}A/`o.s<d5}|OwN@]mt{l?pfW+`KI|I-c0d#(K}H`P;dYTFH' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'zn_';




define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
//Disable File Edits
define('DISALLOW_FILE_EDIT', true);