<?php
namespace Ast;

// EXIT IF ACCESSED DIRECTLY
defined('ABSPATH') || exit;

if ( ! class_exists( 'Deactivator' ) ):

    class Deactivator{

        public $plugin_basename;

        public function __construct() {}

        public static function deactivate_ast() {
            delete_transient( '_welcome_redirect_ast' );
        }
        
        public function __destruct() {}
    }
    
endif;