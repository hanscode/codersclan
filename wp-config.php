<?php
# Database Configuration
define( 'DB_NAME', 'coders_clan' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', 'Hsp820608*' );
define( 'DB_HOST', 'localhost' );
define( 'DB_HOST_SLAVE', 'localhost' );
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_unicode_ci');
$table_prefix = 'wp_';

# Security Salts, Keys, Etc
define('AUTH_KEY',         '&@<X;D|L++EL4tH$3|+2d{Mk;cdby-%%NJtO]>WAhuH&^1I >k=#aGw$e8| =C!-');
define('SECURE_AUTH_KEY',  'bjJOmfPI|3B1>}sb,lV^ahi/GP;s6P8$~Xu ckE+Z!`1orjp~~Jp|`~b.En$X`8u');
define('LOGGED_IN_KEY',    'y+au`bVp+~t6sYov:b-fR.`b a@>jB|U=;<61fJCF_W$dVCJ+j)v.=c#4mGW->6(');
define('NONCE_KEY',        '[5b=aUn:n=M|%^KL*}:+lfJCg>N{jetC|i+y}xi@h@uVJ+ZO7R,D5F,qF=.[x5{7');
define('AUTH_SALT',        'y7rB@z;>z2^JPkLa(@(B+aLRA&QU?3}E5(<=(~C.cw#BRo#50#*:@!u4VBztaF|k');
define('SECURE_AUTH_SALT', 'iP]ocEgU&g4GNx[2u(-GKJ5GZ:+ctU.#NggB1/Pq/,7z*9&h+sD- Xbm!<6XACu.');
define('LOGGED_IN_SALT',   'i{@_zX~ankQ4m`++6xl6|-R%atBs]Sf%24an 9PiPE:fecQ|omdBAaeb!dT#Vk~{');
define('NONCE_SALT',       '!J Ll9C^S9}<6-0{Od^mmei1E8{;}i%$e*>FAeqmbi]q,y,t2Te|AP~hWzarzle=');


# Localized Language Stuff

define( 'WP_CACHE', TRUE );

define( 'WP_AUTO_UPDATE_CORE', false );

define( 'PWP_NAME', 'codersclan' );

define( 'FS_METHOD', 'direct' );

define( 'FS_CHMOD_DIR', 0775 );

define( 'FS_CHMOD_FILE', 0664 );

define( 'PWP_ROOT_DIR', '/nas/wp' );

define( 'WPE_APIKEY', 'e45cd88eebcc08e7f63fd6c02ef5c3dd79f74dd5' );

define( 'WPE_CLUSTER_ID', '140056' );

define( 'WPE_CLUSTER_TYPE', 'pod' );

define( 'WPE_ISP', true );

define( 'WPE_BPOD', false );

define( 'WPE_RO_FILESYSTEM', false );

define( 'WPE_LARGEFS_BUCKET', 'largefs.wpengine' );

define( 'WPE_SFTP_PORT', 2222 );

define( 'WPE_LBMASTER_IP', '' );

define( 'WPE_CDN_DISABLE_ALLOWED', true );

define( 'DISALLOW_FILE_MODS', FALSE );

define( 'DISALLOW_FILE_EDIT', FALSE );

define( 'DISABLE_WP_CRON', false );

define( 'WPE_FORCE_SSL_LOGIN', false );

define( 'FORCE_SSL_LOGIN', false );

/*SSLSTART*/ if ( isset($_SERVER['HTTP_X_WPE_SSL']) && $_SERVER['HTTP_X_WPE_SSL'] ) $_SERVER['HTTPS'] = 'on'; /*SSLEND*/

define( 'WPE_EXTERNAL_URL', false );

define( 'WP_POST_REVISIONS', FALSE );

define( 'WPE_WHITELABEL', 'wpengine' );

define( 'WP_TURN_OFF_ADMIN_BAR', false );

define( 'WPE_BETA_TESTER', false );

umask(0002);


$memcached_servers=array ( );
define('WPLANG','');



# That's It. Pencils down
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');

$_wpe_preamble_path = null; if(false){}
