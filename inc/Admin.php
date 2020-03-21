<?php
namespace Ast;

// EXIT IF ACCESSED DIRECTLY
defined('ABSPATH') || exit;

if ( ! class_exists( 'Admin' ) ):

    class Admin{

        /**
         *  Hold the class instance.
         */
        private static $instance = null;


        /**
         * Holds the values to be used in the fields callbacks
         */
        private $options;

        /**
         * Settings key in database, used in get_option() as first parameter
         *
         * @var string
         */
        private $settings_key = 'ast_settings';

        /**
         * Slug of the page, also used as identifier for hooks
         *
         * @var string
         */
        private $slug = 'azad-scroll-top';

        /**
         * Options group id, will be used as identifier for adding fields to options page
         *
         * @var string
         */
        private $options_group_id = 'ast-settings';

        /**
         * Array of all fields that will be printed on the settings page
         *
         * @var array
         */

        private $fields;

        /**
         * Array of styles
         *
         * @var array
         */
        private $styles = array(

            'style' => array(
                '1' => 'rectangle no-labels',
                '2' => 'rounded no-labels'
            ),

            'variant' => array(
                '1' => 'solid',
                '2' => 'outline'
            )

        );

        public function __construct() {

            $this->fields = $this->get_fields();
            $this->options = $this->get_options();

            add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
            add_action( 'admin_init', array( $this, 'page_init' ) );
            // add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

            // add_filter( 'plugin_action_links', array( $this, 'plugin_settings_link' ), 10, 2 );
            // add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

            // add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );
            // add_filter( 'the_content', array( $this, 'print_social_share' ) );
        }

        public static function get_instance() {
            if ( self::$instance == null ) {
                self::$instance = new Admin();
            }
            return self::$instance;
        }

        /* Load translation file */
        function load_textdomain() {
            load_plugin_textdomain( AST_TEXTDOMAIN, false, dirname( AST_BASENAME ) . '/languages' );
        }

        /* Get fields data */
        function get_fields() {

            $fields = array(
                'enable' => array(
                    'id'        => 'enable',
                    'title'     => esc_html__( 'Enable', AST_TEXTDOMAIN ),
                    'sanitize'  => 'checkbox',
                    'default'   => '1'
                ),
                'style'     => array(
                    'id'        => 'style',
                    'title'     => esc_html__( 'Style', AST_TEXTDOMAIN ),
                    'sanitize'  => 'radio',
                    'default'   => '1'
                ),
                'variant'   => array(
                    'id'        => 'variant',
                    'title'     => esc_html__( 'Variant', AST_TEXTDOMAIN ),
                    'sanitize'  => 'radio',
                    'default'   => '1'
                ),
                'color'     => array(
                    'id'        => 'color',
                    'title'     => esc_html__( 'Color', AST_TEXTDOMAIN ),
                    'sanitize'  => 'checkbox',
                    'default'   => array(
                        'type'          => 'default',
                        'custom_color'  => '#ffd635'
                    )
                ),
                'location'  => array(
                    'id'        => 'location',
                    'title'     => esc_html__( 'Location', AST_TEXTDOMAIN ),
                    'sanitize'  => 'radio',
                    'default'   => 'right'
                ),
                'animation' => array(
                    'id'        => 'animation',
                    'title'     => esc_html__( 'Animation', AST_TEXTDOMAIN ),
                    'sanitize'  => 'checkbox',
                    'default'   => array( 'fade' )
                ),
                'speed' => array(
                    'id'        => 'speed',
                    'title'     => esc_html__( 'Speed', AST_TEXTDOMAIN ),
                    'sanitize'  => 'checkbox',
                    'default'   => array( 'post' )
                ),
                'distance' => array(
                    'id'        => 'distance',
                    'title'     => esc_html__( 'Distance', AST_TEXTDOMAIN ),
                    'sanitize'  => 'checkbox',
                    'default'   => array( 'post' )
                )

            );

            $fields = apply_filters( 'meks_ess_modify_options_fields', $fields );

            return $fields;

        }

        /* Add the plugin settings link */
        function plugin_settings_link( $actions, $file ) {

            if ( $file != ASS_BASENAME ) {
                return $actions;
            }

            $actions['azad_ss_settings'] = '<a href="' . esc_url( admin_url( 'options-general.php?page='.$this->slug ) ) . '" aria-label="settings"> '. __( 'Settings', 'meks-easy-social-share' ) . '</a>';

            return $actions;
        }

        /**
         * Get options from database
         */
        private function get_options() {

            $defaults = array();
            foreach ( $this->fields as $field => $args ) {
                $defaults[$field] = $args['default'];
            }
            $defaults   = apply_filters( 'ast_modify_defaults', $defaults );
            $options    = get_option( $this->settings_key );
            $options    = ast_parse_args( $options, $defaults );
            $options    = apply_filters( 'ast_modify_options', $options );
            //print_r( $options );
            return $options;

        }

        /**
         * Enqueue Admin Scripts
         */
        public function enqueue_admin_scripts() {
            global $pagenow;

            if ( $pagenow == 'options-general.php' && isset( $_GET['page'] ) && $_GET['page'] == $this->slug ) {
                wp_enqueue_style( 'azad_ss_settings', AST_URL . 'assets/css/admin.css', array('wp-color-picker'), ASS_VERSION );
                wp_enqueue_script( 'azad_ss_settings', AST_URL . 'assets/js/admin.js', array( 'jquery', 'jquery-ui-sortable', 'wp-color-picker' ), ASS_VERSION, true );
            }
        }

        /**
         * Enqueue Frontend Scripts
         */
        public function enqueue_frontend_scripts() {
            wp_enqueue_style( 'azad_ss-main', AST_URL . 'assets/css/main.css', false, AST_VERSION );
            wp_enqueue_script( 'azad_ss-main', AST_URL . 'assets/js/main.js', array( 'jquery' ), AST_VERSION, true );

            $inline_styles = $this->get_inline_styles();
            if ( !empty( $inline_styles ) ) {
                wp_add_inline_style( 'azad_ss-main', $inline_styles );
            }

        }

        public function get_inline_styles() {
            $styles = '';
            if ( $this->options['color']['type'] == 'custom' ) {
                $styles = '
                    body .meks_ess a {
                        background: '.$this->options['color']['custom_color'].' !important;
                    }
                    body .meks_ess.transparent a::before, body .meks_ess.transparent a span, body .meks_ess.outline a span {
                        color: '.$this->options['color']['custom_color'].' !important;
                    }
                    body .meks_ess.outline a::before {
                        color: '.$this->options['color']['custom_color'].' !important;
                    }
                    body .meks_ess.outline a {
                        border-color: '.$this->options['color']['custom_color'].' !important;
                    }
                    body .meks_ess.outline a:hover {
                        border-color: '.$this->options['color']['custom_color'].' !important;
                    }
                ';
    
            }
    
            return $styles;
        }

        /**
         * Register and add settings
         */
        public function page_init() {
            register_setting(
                $this->options_group_id, // Option group
                $this->settings_key, // Option name
                array( $this, 'sanitize' ) // Sanitize
            );
    
            if ( empty( $this->fields ) ) {
                return false;
            }
    
            $section_id = 'azad_ss_section';
    
           add_settings_section( $section_id, '', '', $this->slug );
    
            foreach ( $this->fields as $field ) {
    
                if ( empty( $field['id'] ) ) {
                    continue;
                }
    
                $action     = 'print_' . $field['id'] . '_field';
                $callback   = method_exists( $this, $action ) ? array( $this, $action ) : $field['action'];
    
                add_settings_field(
                    'azad_ss_' . $field['id'] . '_id',
                    $field['title'],
                    $callback,
                    $this->slug,
                    $section_id,
                    $this->options[$field['id']]
                );
            }
        }

        /**
         * Sanitize each setting field as needed
         *
         * @param unknown $input array $input Contains all settings fields as array keys
         * @return mixed
         */
        public function sanitize( $input ) {

            if ( empty( $this->fields ) || empty( $input ) ) {
                return false;
            }

            $new_input = array();
            foreach ( $this->fields as $field ) {
                if ( isset( $input[$field['id']] ) ) {
                    $new_input[$field['id']] = $this->sanitize_field( $input[$field['id']], $field['sanitize'] );
                }
            }

            return $new_input;
        }

        /**
         * Dynamically sanitize field values
         *
         * @param unknown $value
         * @param unknown $sensitization_type
         * @return int|string
         */
        private function sanitize_field( $value, $sensitization_type ) {
            switch ( $sensitization_type ) {

            case "checkbox":
                $new_input = array();
                foreach ( $value as $key => $val ) {
                    $new_input[$key] = ( isset( $value[$key] ) ) ?
                        sanitize_text_field( $val ) :
                        '';
                }
                return $new_input;
                break;

            case "radio":
                return sanitize_text_field( $value );
                break;

            case "text":
                return sanitize_text_field( $value );
                break;

            default:
                break;
            }
        }

        /**
         * Add options page
         */
        public function add_plugin_page() {
            // This page will be under "Settings"
            add_options_page(
                esc_html__( 'Azad Scroll Top Settings', AST_TEXTDOMAIN ),
                esc_html__( 'Azad Scroll Top', AST_TEXTDOMAIN ),
                'manage_options',
                $this->slug,
                array( $this, 'print_settings_page' )
            );
        }

        public function print_settings_page() {
            ?>
            <div class="wrap">
                <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
                <form method = "post" action="options.php">
                    <?php
                        settings_fields( $this->options_group_id );
                        do_settings_sections( $this->slug );
                        submit_button();
                    ?>
                </form>
            </div>
            <?php
        }

        /**
         * Print Social Share platforms fields
         */
        public function print_enable_field( $args ) {            

            printf(
                '<label><input type="checkbox" id="meks_ess_location_above" name="%s[location]" value="%s" %s/> %s</label><br>',
                $this->settings_key,
                '1',
                checked( $args, '1', false ),
                __( 'Enable Scroll Top', 'meks-easy-social-share' )
            );
            printf( '<div class="platforms-note"><em>%s</em></div>', __( 'Note: To reorder platforms just click, hold, and drag them.', ASS_TEXTDOMAIN ) );
        }

        /**
         * Print Styles
         */
        public function print_style_field( $args ) {

            $this->styles['style'] = apply_filters( 'meks_ess_modify_styles', $this->styles['style'] );

            $i = 1;

            foreach ( $this->styles['style'] as $key => $value ) {

                if ( $i % 2 !== 0 ) {
                    echo '<div class="meks_ess_clear">';
                }

                printf(
                    '<label class="meks-ess-style"><input type="radio" id="meks_ess-style-%s" name="%s[style]" value="%s" %s/><img src="%s" alt="style-%s"> <span>%s</span></label>',
                    $key,
                    $this->settings_key,
                    $key,
                    checked( $args, $key, false ),
                    ASS_URL . 'assets/images/style-'.$key.'.svg',
                    $key,
                    'Style '.$key
                );

                if ( $i % 2 == 0 ) {
                    echo '</div>';
                }

                $i++;
            }

        }

        /**
         * Print Style Variant
         */
        public function print_variant_field( $args ) {

            $this->styles['variant'] =  apply_filters( 'meks_ess_modify_styles', $this->styles['variant'] );

            foreach ( $this->styles['variant'] as $key => $value ) {
                printf(
                    '<label class="meks-ess-style meks-ess-style-variant"><input type="radio" id="meks_ess-variant-%s" name="%s[variant]" value="%s" %s/><img src="%s" alt="variant-%s"> <span>%s</span></label>',
                    $key,
                    $this->settings_key,
                    $key,
                    checked( $args, $key, false ),
                    ASS_URL . 'assets/images/variant-'.$key.'.svg',
                    $key,
                    ucfirst( $value )
                );
            }

        }

        /**
         * Print Style Colors
         */
        public function print_color_field( $args ) {

            printf(
                '<label class="meks_ess-color"><input type="radio" id="meks_ess-color-brand" name="%s[color][type]" value="brand" %s/>%s</label><br>',
                $this->settings_key,
                checked( $args['type'], 'default', false ),
                __( 'Default' , AST_TEXTDOMAIN )
            );

            printf(
                '<label class="meks_ess-color"><input type="radio" id="meks_ess-color-custom" name="%s[color][type]" value="custom" %s/>%s</label><br>',
                $this->settings_key,
                checked( $args['type'], 'custom', false ),
                __( 'Custom' , AST_TEXTDOMAIN )
            );

            printf( '<input type="text" id="meks_ess-custom-color" name="%s[color][custom_color]" value="%s" />',
                $this->settings_key,
                $args['custom_color']
            );

        }

        /**
         * Print Locations radio buttons
         */
        public function print_location_field( $args ) {

            printf(
                '<label><input type="radio" id="meks_ess_location_above" name="%s[location]" value="%s" %s/> %s</label><br>',
                $this->settings_key,
                'left',
                checked( $args, 'left', false ),
                __( 'Left Side', AST_TEXTDOMAIN )
            );
            printf(
                '<label><input type="radio" id="meks_ess_location_below" name="%s[location]" value="%s" %s/> %s</label><br>',
                $this->settings_key,
                'right',
                checked( $args, 'right', false ),
                __( 'Right Side', AST_TEXTDOMAIN )
            );

        }

        /**
         * Print Post Types fields
         */
        public function print_animation_field( $args ) {

            $animation = array('fade','asdf','asdf');

            foreach ( $animation as $key => $type ) {

                $checked =  in_array( $key, $args ) ? $key : '';

                printf(
                    '<label><input type="checkbox" id="meks_ess_post_type_%s" name="%s[animation][]" value="%s" %s/> %s</label><br>',
                    $key,
                    $this->settings_key,
                    $key,
                    checked( $checked, $key, false ),
                    $type->label
                );
            }

        }
        
        /**
         * Print Post Types fields
         */
        public function print_speed_field( $args ) {

            printf(
                '<label><input type="text" id="meks_ess_label" name="%s[label_share][text]" value="%s"/></label><br>',
                $this->settings_key,
                esc_html( $args['text'] )
            );

        }

        /**
         * Print Post Types fields
         */
        public function print_distance_field( $args ) {

            printf(
                '<label><input type="text" id="meks_ess_label" name="%s[label_share][text]" value="%s"/></label><br>',
                $this->settings_key,
                esc_html( $args['text'] )
            );

        }

        public function parse_settings_for_output() {

            $before = '<div class="meks_ess '.$this->styles['style'][$this->options['style']].' '.$this->styles['variant'][$this->options['variant']].'">';
            $after  = '</div>';
    
            $share_label = '';
    
            if ( $this->options['label_share']['active'] ) {
    
                $share_label = $this->options['label_share']['text'];
    
                if ( empty( $share_label ) ) {
                    $share_label = esc_html__( 'Share this', 'meks-easy-social-share' );
                }
    
            }
    
            if (  !empty( $share_label ) ) {
                $before = '<div class="meks_ess_share_label"><h5>' . $share_label . '</h5></div>'. $before;
            }
    
            return array( 'platforms' => $this->options['platforms'], 'before' => $before, 'after' => $after );
        }
    
    
        /**
         * Draw social share box base on settings
         *
         * @return void
         */
        public function print_social_share( $content ) {
    
            if ( empty( $this->options['platforms'] ) || empty( $this->options['post_type'] ) || $this->options['location'] == 'custom' ) {
                return $content;
            }
    
            if ( is_front_page() || is_home() || !is_singular( $this->options['post_type'] )  ) {
                return $content;
            }
    
            $output = azad_ss_share( array(), false, '', '' );
    
            switch ( $this->options['location'] ) {
        
                case 'above':
                    return $output . $content;
                    break;
        
                case 'below':
                    return $content . $output;
                    break;
        
                case 'above_below':
                    return $output . $content . $output;
                    break;
        
                default:
                    break;
            }
    
            return $content;
    
        }
        
        public function __destruct() {}
    }
    
endif;