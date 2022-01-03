<?php

//Begin Really Simple SSL session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple SSL
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

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wwluan_wp' );

/** MySQL database username */
define( 'DB_USER', 'wwluan_wp' );

/** MySQL database password */
define( 'DB_PASSWORD', 'ZHMVLlWzMBwn' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',          '0! ?GJT99:OAV{2h/mXA~(3( 0,8)>oW%YO}o-8,E/OSaTrG%$C7r$i8!F*84nyn' );
define( 'SECURE_AUTH_KEY',   'rc.o+|(e&Dm^k@^}/=dBK@~WWB[2aF]<IY=0&7tAhARvhkfR.gQ(g*YFJ1-:HSy*' );
define( 'LOGGED_IN_KEY',     'i*lqU :q6B;8P.PeX,lN/,_@y^ku(cy5f,vsHFV[<4qafV2 f^*9s2#!^yM6Ayt~' );
define( 'NONCE_KEY',         'fh}OUf93)2/Ll?ar)gW33BG-`05d]RZCye5OkK(a}L6* g0#{{/y+b&|m=3ozF!K' );
define( 'AUTH_SALT',         'K8K4U7*%WpTt[nUJo83 #G|AHr8c-b=tRIHkuvs06SgKBwjqKwFXM~Rvj3P=v< /' );
define( 'SECURE_AUTH_SALT',  'fqM9`i2<#Sbtq{%pW}4WiUb^@~D7$@fGv)wajrV9%2g4zMAruF5DMLabvm0^YXj(' );
define( 'LOGGED_IN_SALT',    '4.0sCUr<a#0T.8]la[4|tQSD]?3,w&0InJd/@g(X+y?%V&K:Xt[#@USzZ,Vn~SKe' );
define( 'NONCE_SALT',        'D8F,E}DJE)Jm$&Y[g0sq#V5F:n~#4E(GaI@~}gjeiG_-x1R#9iwIN+zn&6eg|2P;' );
define( 'WP_CACHE_KEY_SALT', '#tD9ufao4X %S]~ihd%(MN`Z#K28[s@_ndsQ3R>VEP0dE6Uv;&?7!6)MmgB|7M:#' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

define('WP_MEMORY_LIMIT', '300M');


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
