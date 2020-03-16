<?php
namespace Inc;

// EXIT IF ACCESSED DIRECTLY
defined('ABSPATH') || exit;
if ( ! class_exists( 'Init' ) ):

     final class Init{
         public $plugin_basename;
         public function __construct() {
            add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
            add_action( 'plugins_loaded', array( $this, 'constants' ), 1 );
            add_action( 'plugins_loaded', array( $this, 'includes' ), 3 );
            add_action( 'plugins_loaded', array( $this, 'admin' ), 4 );
         }
         public static function get_services() {
             return [
                // Admin\Azad_Scroll_Top_Activator::class,
                // Admin\Enqueue::class,
                // Admin\CustomPostTypeController::class,
                // Admin\SettingsLinks::class
            ];   
        }
        public static function register_services() {
            foreach(self::get_services() as $class){
                $service = self::instantiate($class);
                if(method_exists($service,'register')){
                    $service->register();
                }
            }
        }
        /* Load translation file */
        function load_textdomain() {
            load_plugin_textdomain( 'AST_TEXTDOMAIN', false, dirname( AST_BASENAME ) . '/languages' );
        }
        public function constants(){
            //echo 'Constants';
        }
        public function includes(){
            require_once( AST_PATH . 'inc/functions.php' );
        }
    
        public function admin(){
            if( is_admin() ){
                require_once( AST_PATH . 'admin/admin.php' );            
            }
        }
        private static function instantiate($class) {
            $service = new $class();
            return $service;
        }
        public function __destruct() {}
    }
    
endif;