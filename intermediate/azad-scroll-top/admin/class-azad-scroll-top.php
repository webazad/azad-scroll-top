<?php
class Azad_Scroll_Top_Main {

    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Array of all fields that will be printed on the settings page
     *
     * @var array
     */
    private $fields;

    /**
     * Slug of the page, also used as identifier for hooks
     *
     * @var string
     */
    public $slug = 'azad-scroll-top';

    /**
     * Settings key in database, used in get_option() as first parameter
     *
     * @var string
     */
    public $settings_key = 'azad_scroll_top_settings';

    /**
     * Options group id, will be used as identifier for adding fields to options page
     *
     * @var string
     */
    public $options_group_id = 'azad-scroll-top-settings';

    public function __construct() {

        $this->fields = $this->get_fields();
        $this->options = $this->get_options();

        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );

    }

    /* Get fields data */
    function get_fields() {

        $fields = array(
            'enable' => array(
                'id' => 'enable',
                'title' => esc_html__( 'Enable', AST_TEXTDOMAIN ),
                'sanitize' => 'checkbox',
                'default' => array(
                    'active' => 1
                )
            ),
            'type' => array(
                'id' => 'type',
                'title' => esc_html__( 'Type', AST_TEXTDOMAIN ),
                'sanitize' => 'radio',
                'default' => 'icon'
            ),
            'position' => array(
                'id' => 'position',
                'title' => esc_html__( 'Position', AST_TEXTDOMAIN ),
                'sanitize' => 'radio',
                'default' => 'right'
            ),
            'background' => array(
                'id' => 'background',
                'title' => esc_html__( 'Background', AST_TEXTDOMAIN ),
                'sanitize' => 'checkbox',
                'default' => array(
                    'type' => 'brand',
                    'custom_color' => '#ffd635'
                )
            ),
            'icon_color' => array(
                'id' => 'icon_color',
                'title' => esc_html__( 'Icon / Text Color', AST_TEXTDOMAIN ),
                'sanitize' => 'text',
                'default' => 'asdf'
            ),
            'style' => array(
                'id' => 'style',
                'title' => esc_html__( 'Style', AST_TEXTDOMAIN ),
                'sanitize' => 'radio',
                'default' => 'circle'
            ),
            // 'label_share' => array(
            //     'id' => 'label_share',
            //     'title' =>  esc_html__( 'Share label', 'meks-easy-social-share' ),
            //     'sanitize' => 'checkbox',
            //     'default' => array(
            //         'text' => 'Share this',
            //         'active' => 0
            //     )
            // ),

        );

        $fields = apply_filters( 'ast_options_fields', $fields );

        return $fields;

    }

    /**
     * Get options from database
     */
    private function get_options() {

        $defaults = array();

        foreach ( $this->fields as $field => $args ) {
            $defaults[$field] = $args['default'];
        }

        $defaults = apply_filters( 'ast_modify_defaults', $defaults );

        $options = get_option( $this->settings_key );

        $options = ast_parse_args( $options, $defaults );

        $options = apply_filters( 'ast_modify_options', $options );

        //print_r( $options );

        return $options;

    }

    /**
     * Add options page
     */
    public function add_plugin_page() {
        // This page will be under "Settings"
        add_options_page(
            esc_html__( 'Azad Scroll Top', AST_TEXTDOMAIN ),
            esc_html__( 'Azad Scroll Top', AST_TEXTDOMAIN ),
            'manage_options',
            $this->slug,
            array( $this, 'print_settings_page' )
        );
    }

    /**
     * Options page callback
     */
    public function print_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form method="post" action="options.php">
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

        $section_id = 'azad_scroll_top_section';

        add_settings_section( $section_id, '', '', $this->slug );

        foreach ( $this->fields as $field ) {

            if ( empty( $field['id'] ) ) {
                continue;
            }

            $action = 'print_' . $field['id'] . '_field';
            $callback = method_exists( $this, $action ) ? array( $this, $action ) : $field['action'];

            add_settings_field(
                'ast_' . $field['id'] . '_id',
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
     * Print Social Share platforms fields
     */
    public function print_enable_field( $args ) { ?>
        <div class="platforms-sortable">
        <?php
            printf(
                '<label for="%1$s">
                <input type="hidden" name="%2$s[enable][active]" value="0">
                <input type="checkbox" id="%1$s" name="%2$s[enable][active]" value="1" %3$s>%4$s</label>',
                $args,
                $this->settings_key,
                checked( $args['active'], '1', false ),
                __( 'Enable scroll top?', AST_TEXTDOMAIN )
            );
        ?>
        </div>
        <?php
    }

    /**
     * Print Type radio buttons
     */
    public function print_type_field( $args ) {

        printf(
            '<label><input type="radio" id="%1$s_icon" name="%1$s[type]" value="%2$s" %3$s/> %4$s</label> ',
            $this->settings_key,
            'icon',
            checked( $args, 'icon', false ),
            __( 'Icon', AST_TEXTDOMAIN )
        );
        printf(
            '<label><input type="radio" id="%1$s_text" name="%1$s[type]" value="%2$s" %3$s/> %4$s</label>',
            $this->settings_key,
            'text',
            checked( $args, 'text', false ),
            __( 'Text', AST_TEXTDOMAIN )
        );

    }

    /**
     * Print Type radio buttons
     */
    public function print_position_field( $args ) {

        printf(
            '<label><input type="radio" id="%1$s_icon" name="%1$s[position]" value="%2$s" %3$s/> %4$s</label> ',
            $this->settings_key,
            'right',
            checked( $args, 'right', false ),
            __( 'Right', AST_TEXTDOMAIN )
        );
        printf(
            '<label><input type="radio" id="%1$s_text" name="%1$s[position]" value="%2$s" %3$s/> %4$s</label>',
            $this->settings_key,
            'left',
            checked( $args, 'left', false ),
            __( 'Left', AST_TEXTDOMAIN )
        );

    }

    /**
     * Print Backgrond Color
     */
    public function print_background_field( $args ) {

        printf(
            '<label class="meks_ess-color"><input type="radio" id="meks_ess-color-brand" name="%s[background][type]" value="brand" %s/>%s</label><br>',
            $this->settings_key,
            checked( $args['type'], 'brand', false ),
            __( 'Brand' , 'meks-easy-social-share' )
        );

        printf(
            '<label class="meks_ess-color"><input type="radio" id="meks_ess-color-custom" name="%s[background][type]" value="custom" %s/>%s</label><br>',
            $this->settings_key,
            checked( $args['type'], 'custom', false ),
            __( 'Custom' , 'meks-easy-social-share' )
        );

        printf( '<input type="text" id="meks_ess-custom-color" name="%s[background][custom_color]" value="%s" />',
            $this->settings_key,
            $args['custom_color']
        );

    }

    /**
     * Print Icon Color
     */
    public function print_icon_color_field( $args ) {

        printf( '<input type="text" id="meks_ess-custom-color" name="%1$s[icon_color]" value="%2$s" />',
            $this->settings_key,
            $args
        );

    }

    /**
     * Print Style
     */
    public function print_style_field( $args ) {

        printf( '<input type="radio" id="meks_ess-custom-color" name="%1$s[style]" value="%2$s" />',
            $this->settings_key,
            $args
        );
        printf( '<input type="radio" id="meks_ess-custom-color" name="%1$s[style]" value="%2$s" />',
            $this->settings_key,
            checked( $args, 'circle', false ),
            __( 'Circle' , 'meks-easy-social-share' )
        );

    }
}

/**
 * Parse args ( merge arrays )
 *
 * Similar to wp_parse_args() but extended to also merge multidimensional arrays
 *
 * @param array   $a - set of values to merge
 * @param array   $b - set of default values
 * @return array Merged set of elements
 * @since  1.0.0
 */

if ( ! function_exists( 'ast_parse_args' ) ):
	function ast_parse_args( &$a, $b ) {

		$a = (array)$a;
		$b = (array)$b;
		$r = $b;
		foreach ( $a as $k => &$v ) {
			if ( is_array( $v ) && !isset( $v[0] ) && isset( $r[ $k ] ) ) {
				$r[ $k ] = ast_parse_args( $v, $r[ $k ] );
			} else {
				$r[ $k ] = $v;
			}
		}

		return $r;
	}
endif;