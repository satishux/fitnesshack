<?php
define('COOKIE_DOMAIN', 'fitnesshack.org'); // Added by W3 Total Cache

/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

/** Enable W3 Total Cache Edge Mode */
define('W3TC_EDGE_MODE', true); // Added by W3 Total Cache

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
 //Added by WP-Cache Manager
define( 'WPCACHEHOME', '/home/satishux/public_html/fitnesshack.org/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'satishux_wp2');

/** MySQL database username */
define('DB_USER', 'satishux_wp2');

/** MySQL database password */
define('DB_PASSWORD', 'E(45vLdNuX60^]1');

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
define('AUTH_KEY',         'HICR8i0h7m9JtF3opNphcESQ35DEwUPBHy37DIpoytIcwAlPmhU3IuRJROL2z89e');
define('SECURE_AUTH_KEY',  'FplLqctasCLfr4jnmCtefI6YYUyO4G1jRHGi0qZV5AX4QqAhvLML1QdLbR7jVnY6');
define('LOGGED_IN_KEY',    '4eUslj6k352LyIHbOZ646HrOmcpvhaKSm4vRHQES4PoGzUeac8YDPZuV3cVDmMzM');
define('NONCE_KEY',        'cLNePRbApjE8p9X4cuUZKwJBOOazxo97KMQmTdcZnNqNpG5Kd2lQS3ZGFXOFzjDr');
define('AUTH_SALT',        'P1TTragt8g2dkMOqbpiujwYvqt7ssT23cfm3R0V824149hOm6gZICeLq6OTEa5vb');
define('SECURE_AUTH_SALT', 'BN1oaVr5RvVuDpg6E0x78be2dKiuPhLnIAOexac4m3umCRJqC7kBCwt2K5qbglbB');
define('LOGGED_IN_SALT',   'GCcdTmtGDcRr2t9IFAnZ580GQ51V1w1DoJEWFJB6c0LoCOfd9MkncjNqqb5gvLSg');
define('NONCE_SALT',       '4ytTRdyJgsedPgONKA5tqqmNQkApsino7ySA1g4PO3eRdIJlldBJetWlA6qQW2mu');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');define('FS_CHMOD_DIR',0755);define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**
 * Turn off automatic updates since these are managed upstream.
 */
define('AUTOMATIC_UPDATER_DISABLED', true);


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
