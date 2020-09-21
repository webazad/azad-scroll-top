<?php
/* 
 Plugin Name: Azad Scroll Top
 Description: A very simple scroll top button
  Plugin URi: gittechs.com/plugin/azad-scroll-top 
      Author: Md. Abul Kalam Azad
  Author URI: gittechs.com/author
Author Email: webdevazad@gmail.com
     Version: 1.0.0
 Text Domain: azad-scroll-top
*/

if ( ! defined( 'ABSPATH' ) ) exit;

require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
$plugin_data = get_plugin_data( __FILE__ );

define( 'AST_NAME', $plugin_data[ 'Name' ] );
define( 'AST_VERSION', $plugin_data[ 'Version' ] );
define( 'AST_TEXTDOMAIN', $plugin_data[ 'TextDomain' ] );
define( 'AST_PATH', plugin_dir_path( __FILE__ ) );
define( 'AST_URL', plugin_dir_url( __FILE__ ) );
define( 'AST_BASENAME', plugin_basename( __FILE__ ) );

/* Includes */
require_once AST_PATH . 'inc/functions.php';
require_once AST_PATH .'inc/admin.php';