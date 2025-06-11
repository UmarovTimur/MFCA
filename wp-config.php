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
define( 'DB_NAME', 'u684340623_mfca' );

/** Database username */
define( 'DB_USER', 'wp_user' );

/** Database password */
define( 'DB_PASSWORD', 'wp_password' );

/** Database hostname */
define( 'DB_HOST', 'db' );

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
define( 'AUTH_KEY',         '`>$!y72M*V.71CkEm}EDs>e#nteJ(PWJZ:~LItHrQY``m?/$J7CgF[fo<$*(1b]~' );
define( 'SECURE_AUTH_KEY',  ']Z&^q@0d)i)lb}SsxpNM{v(cl/o0bUE ^X!QHYtEDo=t%,s-^:h}%GSTvCu1.Q3;' );
define( 'LOGGED_IN_KEY',    'YZX;{b4$`ol,*HhlhA>W^2)^V,8Ihm:D#%yH]*m&K4^Bq#Q-_B>OIk|3Yj4lM&eB' );
define( 'NONCE_KEY',        '7F5b{TWF^zUJ124yM&G*i55I>dBx O~Y$mN:7^}L3XI-F_>4,?OIU3~Do6;#V4&|' );
define( 'AUTH_SALT',        ',CyEu`O;=xfD)ja#=~j]d8%t[z7)N$e.2fz]NBKESE%w@ht;23jDfQjt7E~Q.Y;L' );
define( 'SECURE_AUTH_SALT', 'u_ggI<9#0[mVpRxvFW4*;!l1Qv^_v<ePq1%H>*.KbT}.1ISZZmU/K=Kj<$8W<(t{' );
define( 'LOGGED_IN_SALT',   '0a.q&o0q8_XaW FG8&h^I{0@A5$JZ4>A^&RE_y3Ir$nYt1c18QvZs=lcqTfN>|+-' );
define( 'NONCE_SALT',       'O1Gvw}Q};5w&^K/=XyH5+US9dc]<ll7&A)ewd!|<#<CdX#Azm9GD(LP)T7[<T&J~' );

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
$table_prefix = 'wp_';

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

define('WP_HOME', 'http://localhost:8080');
define('WP_SITEURL', 'http://localhost:8080');

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
