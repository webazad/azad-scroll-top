<?php
/* 
 Plugin Name: Azad Scroll Top
 Description: A very simple scroll top button for entire site.
  Plugin URI: gittechs.com/plugin/azad-scroll-top 
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

require_once( AST_DIRPATH . 'inc/functions.php' );

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
    require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

if ( class_exists( 'Ast\\Init' ) ) :    
    Ast\Init::register_services();
endif;

register_activation_hook( __FILE__, array( 'Ast\Activator', 'activate_ast' ) );
register_deactivation_hook( __FILE__, array( 'Ast\Deactivator', 'deactivate_ast' ) );

if ( ! function_exists( 'ast_safe_welcome_redirect' ) ) {
	add_action( 'admin_init', 'ast_safe_welcome_redirect' );
	function ast_safe_welcome_redirect() {
		if ( ! get_transient( '_welcome_redirect_ast' ) ) {
			return;
		}
		delete_transient( '_welcome_redirect_ast' );
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}
		wp_safe_redirect( add_query_arg(
			array(
				'page' => 'azad-scroll-top'
				),
			admin_url( 'admin.php' )
		) );
	}
}