<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'ecommerce' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'OkSQqV;%:^-&tMB4P<Vo96VG|@QwGxwN4G[dXF&]3C=.@Z[8Xl]{h]p%P2cT=y^L' );
define( 'SECURE_AUTH_KEY',  'eKw,4:-yqNJeYq._^v9%lwl-7[)[dOwxyt1i}Zx^a<EM:Ja?B$QSuRbz|,ph-sX!' );
define( 'LOGGED_IN_KEY',    '?r%`O*x J])Q>XFA:c=Q<S6&DK+VI`AHm1Q^W9#!d8aom#=w]:VCco_Qt*+a3,`5' );
define( 'NONCE_KEY',        'rWi#KnB8>akU7o0mNc+wpu#)v K,NtA8&[@*PcXM7a8Vudj+sjTBN]Q>vtx$  xE' );
define( 'AUTH_SALT',        'd=Qq&;u..#LtK,l04?z.+>3sx7HirH19+2[agl=jd%f*_dMgQ%>I54c8]pmOgiB*' );
define( 'SECURE_AUTH_SALT', 'G/ iu,!{WP~I;n`fr.S(WH6HNHx2BfAA@qC%*PD<}&{<bT-7]tPhXWW*{Va0qP:`' );
define( 'LOGGED_IN_SALT',   '0V>ANoMvLxhiroWGX?GQ^sV=?9hPxK4MH!3F%[Rc0L~bPS?!ZI7{nk}h-{^tA9 T' );
define( 'NONCE_SALT',       'LvBz.N6Dopm2#?.<$-``L~rc@}?2/rjd-P$<;>L9Y8`E@gZ}Zv@}I=|}~6O)03-3' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'eco_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
