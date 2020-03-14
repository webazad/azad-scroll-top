<?php
namespace Inc;

// EXIT IF ACCESSED DIRECTLY
defined('ABSPATH') || exit;
if ( ! class_exists( 'Init' ) ):

     final class Init{
         public $plugin_basename;
         public function __construct() {}
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
        private static function instantiate($class) {
            $service = new $class();
            return $service;
        }
        public function __destruct() {}
    }
    
endif;