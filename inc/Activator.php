<?php
namespace Ast;

// EXIT IF ACCESSED DIRECTLY
defined('ABSPATH') || exit;

if ( ! class_exists( 'Activator' ) ):

    class Activator{

        public $slug = 'azad-social-sharing';

        public function __construct() {}
        
        public static function activate_ast() {

            set_transient( '_welcome_redirect_ast', true, 60 );
            
        }

        public function __destruct() {}
    }
    
endif;