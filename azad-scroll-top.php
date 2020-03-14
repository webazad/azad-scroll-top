<?php
/* 
Plugin Name: Azad Scroll Top
Description: A very simple scroll top button for entire site.
Plugin URi: gittechs.com/plugin/azad-scroll-top 
Author: Md. Abul Kalam Azad
Author URI: gittechs.com/author
Author Email: webdevazad@gmail.com
Version: 1.0.1
Text Domain: azad-scroll-top
*/

if(! defined( 'ABSPATH' )) exit;

require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
$plugin_data = get_plugin_data( __FILE__ );

define( 'AST_NAME', $plugin_data[ 'Name' ] );
define( 'AST_VERSION', $plugin_data[ 'Version' ] );
define( 'AST_TEXTDOMAIN', $plugin_data[ 'TextDomain' ] );
define( 'AST_PATH', plugin_dir_path( __FILE__ ) );
define( 'AST_URL', plugin_dir_url( __FILE__ ) );
define( 'AST_BASENAME', plugin_basename( __FILE__ ) );

if(file_exists(dirname(__FILE__) . '/vendor/autoload.php')){
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

if ( class_exists( 'Inc\\Init' ) ) :    
    Inc\Init::register_services();
endif;

class Azad_Scroll_Top{

    public function __construct(){

        add_action( 'plugins_loaded', array( $this, 'constants' ), 1 );
        add_action( 'plugins_loaded', array( $this, 'i18n' ), 2 );
        add_action( 'plugins_loaded', array( $this, 'includes' ), 3 );
        add_action( 'plugins_loaded', array( $this, 'admin' ), 4 );

    }

    public function constants(){
        //echo 'Constants';
    }

    public function i18n(){
        //echo 'i18n';
    }

    public function includes(){
        define( 'ADMIN', plugin_dir_path(__FILE__) );
        require_once( ADMIN . 'inc/functions.php' );
    }

    public function admin(){
        if( is_admin() ){
            require_once( ADMIN . 'admin/admin.php' );            
        }
    }

    public function __destruct(){}
}

new Azad_Scroll_Top();

function activate_ast() {
	Inc\Admin\Azad_Scroll_Top_Activator::activate();
}

function deactivate_ast() {
	Inc\Admin\Azad_Scroll_Top_Deactivator::Deactivate();
}

register_activation_hook( __FILE__, 'activate_ast' );
register_deactivation_hook( __FILE__, 'deactivate_ast' );

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
				'page' => 'azad_scroll_top_settings_page'
				),
			admin_url( 'admin.php' )
		) );

	}

}