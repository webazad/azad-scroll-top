<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function azad_scroll_top_admin_menu(){
    $settings = add_options_page(
            esc_html('Azad Scroll Top Settings','ast'),
            esc_html('Azad Scroll Top','ast'),
            'manage_options',
            'azad_scroll_top_settings_page',
            'azad_scroll_top_plugin_settings_render_page',
    );
    if(! $settings){
        return;
    }
    add_action('load-' . $settings, 'azad_scroll_top_styles_scripts');
}
add_action('admin_menu','azad_scroll_top_admin_menu');
function azad_scroll_top_styles_scripts(){
    wp_enqueue_style('azad-scroll-top-admin',plugins_url('assets/css/scroll-top-admin.css',dirname(__FILE__)),array('wp-color-picker'),null);
    wp_enqueue_script('azad-scroll-top-script',plugins_url('assets/js/scroll-top-admin.js',dirname(__FILE__)),array('jquery','wp-color-picker'),null,true);
}
function azad_scroll_top_register_settings(){
    register_setting(
            'azad_scroll_top_settings',
            'azad_scroll_top_plugin_settings',
            'azad_scroll_top_plugin_settings_validate'
    );
}
add_action('admin_init','azad_scroll_top_register_settings');
function azad_scroll_top_setting_sections_fields(){
    // Add general section.
    add_settings_section(
            'azad_scroll_top_general_settings',
            '',
            '__return_false',
            'azad_scroll_top_settings_page',
    );
    // Add enable/disable checkbox setting field.
    add_settings_field(
            'azad_scroll_top_enable',
            esc_html__('Enable:','ast'),
            'azad_scroll_top_enable_field',
            'azad_scroll_top_settings_page',
            'azad_scroll_top_general_settings'
    );
    // Add background setting field.
    add_settings_field(
            'azad_scroll_top_type',
            esc_html__('Type:','ast'),
            'azad_scroll_top_type_field',
            'azad_scroll_top_settings_page',
            'azad_scroll_top_general_settings'
    );
    // Add position setting field.
    add_settings_field(
            'azad_scroll_top_position',
            esc_html__('Position:','ast'),
            'azad_scroll_top_position_field',
            'azad_scroll_top_settings_page',
            'azad_scroll_top_general_settings'
    );
    // Add background setting field.
    add_settings_field(
            'azad_scroll_top_bg_color',
            esc_html__('Background Color:','ast'),
            'azad_scroll_top_bg_color_field',
            'azad_scroll_top_settings_page',
            'azad_scroll_top_general_settings'
    );
    // Add color setting field.
    add_settings_field(
            'azad_scroll_top_color',
            esc_html__('Icon/Text Color:','ast'),
            'azad_scroll_top_color_field',
            'azad_scroll_top_settings_page',
            'azad_scroll_top_general_settings'
    );
    // Add redius setting field.
    add_settings_field(
            'azad_scroll_top_radius',
            esc_html__('Style:','ast'),
            'azad_scroll_top_radius_field',
            'azad_scroll_top_settings_page',
            'azad_scroll_top_general_settings'
    );
    // Add animation setting field.
    add_settings_field(
            'azad_scroll_top_animation',
            esc_html__('Animation:','ast'),
            'azad_scroll_top_animation_field',
            'azad_scroll_top_settings_page',
            'azad_scroll_top_general_settings'
    );
    // Add animation setting field.
    add_settings_field(
            'azad_scroll_top_speed',
            esc_html__('Speed:','ast'),
            'azad_scroll_top_speed_field',
            'azad_scroll_top_settings_page',
            'azad_scroll_top_general_settings'
    );
    // Add animation setting field.
    add_settings_field(
            'azad_scroll_top_distance',
            esc_html__('Distance:','ast'),
            'azad_scroll_top_distance_field',
            'azad_scroll_top_settings_page',
            'azad_scroll_top_general_settings'
    );
    // Add animation setting field.
    add_settings_field(
            'azad_scroll_top_target',
            esc_html__('Target(optional):','ast'),
            'azad_scroll_top_target_field',
            'azad_scroll_top_settings_page',
            'azad_scroll_top_general_settings'
    );
    // Add animation setting field.
    add_settings_field(
            'azad_scroll_top_css',
            esc_html__('Custom CSS:','ast'),
            'azad_scroll_top_css_filed',
            'azad_scroll_top_settings_page',
            'azad_scroll_top_general_settings'
    );
}
add_action('admin_init','azad_scroll_top_setting_sections_fields');

function azad_scroll_top_enable_field(){ 
    $settings = azad_scroll_top_get_plugin_settings( 'azad_scroll_top_enable' );
?>
<fieldset>
    <legend class="screen-reader-text"><span><?php esc_html_e('Enable','ast'); ?></span></legend>
    <p>
        <input id="enable_scroll_top" type="checkbox" name="azad_scroll_top_plugin_settings[azad_scroll_top_enable]"  value="1" <?php checked(1,$settings); ?>/>
        <label for="enable_scroll_top"><?php esc_html_e('Enable scroll top?','ast'); ?></label>        
    </p>    
</fieldset>
<?php }

function azad_scroll_top_type_field(){ 
    $settings = azad_scroll_top_get_plugin_settings( 'azad_scroll_top_type' );
?>
<fieldset>
    <legend class="screen-reader-text"><span><?php esc_html_e('Type','ast'); ?></span></legend>
    <p>
        <label>
            <input class="scroll-top-type" type="radio" name="azad_scroll_top_plugin_settings[azad_scroll_top_type]"  value="icon" <?php checked('icon',$settings); ?>/>
            <?php esc_html_e('Icon','ast'); ?>
        </label>        
        <label>
            <input id="enable_scroll_top" type="radio" name="azad_scroll_top_plugin_settings[azad_scroll_top_type]"  value="text" <?php checked('text',$settings); ?>/>
            <?php esc_html_e('Text','ast'); ?>
        </label>        
    </p>    
</fieldset>
<?php }

function azad_scroll_top_position_field(){ 
    $settings = azad_scroll_top_get_plugin_settings( 'azad_scroll_top_position' );
?>
<fieldset>
    <legend class="screen-reader-text"><span><?php esc_html_e('Position','ast'); ?></span></legend>
    <p>
        <label>
            <input type="radio" name="azad_scroll_top_plugin_settings[azad_scroll_top_position]"  value="right" <?php checked('right',$settings); ?>/>
            <?php esc_html_e('Right Side','ast'); ?>
        </label><br />       
        <label>
            <input type="radio" name="azad_scroll_top_plugin_settings[azad_scroll_top_position]"  value="left" <?php checked('left',$settings); ?>/>
            <?php esc_html_e('Left Side','ast'); ?>
        </label>        
    </p>    
</fieldset>
<?php }

function azad_scroll_top_bg_color_field(){ 
    $settings = azad_scroll_top_get_plugin_settings( 'azad_scroll_top_bg_color' );
?>
    <input class="color-scroll" type="text" name="azad_scroll_top_plugin_settings[azad_scroll_top_bg_color]"  value="<?php echo sanitize_hex_color($settings); ?>" />
<?php }

function azad_scroll_top_color_field(){ 
    $settings = azad_scroll_top_get_plugin_settings( 'azad_scroll_top_color' );
?>
    <input class="color-scroll" type="text" name="azad_scroll_top_plugin_settings[azad_scroll_top_color]"  value="<?php echo sanitize_hex_color($settings); ?>" />
<?php }

function azad_scroll_top_radius_field(){ 
    $settings = azad_scroll_top_get_plugin_settings( 'azad_scroll_top_radius' );
?>
<fieldset class="azad-scroll-top-vertical">
    <legend class="screen-reader-text"><span><?php esc_html_e('Radius','ast'); ?></span></legend>
    <p class="checkbox-img">
        <label for="enable_scroll_top">
            <input type="radio" name="azad_scroll_top_plugin_settings[azad_scroll_top_radius]"  value="rounded" <?php checked('rounded',$settings); ?> />
            <img src="<?php echo plugins_url('assets/img/rounded.png',dirname(__FILE__)); ?>" alt="<?php esc_attr_e('Rounded','ast'); ?>" />
            <span class="screen-reader-text"><?php esc_html_e('Rounded','ast'); ?></span>
        </label>
        <label for="enable_scroll_top">
            <input type="radio" name="azad_scroll_top_plugin_settings[azad_scroll_top_radius]"  value="square" <?php checked('square',$settings); ?> />
            <img src="<?php echo plugins_url('assets/img/square.png',dirname(__FILE__)); ?>" alt="<?php esc_attr_e('Rounded','ast'); ?>" />
            <span class="screen-reader-text"><?php esc_html_e('Rounded','ast'); ?></span>
        </label>
        <label for="enable_scroll_top">
            <input type="radio" name="azad_scroll_top_plugin_settings[azad_scroll_top_radius]"  value="circle" <?php checked('circle',$settings); ?>/>
            <img src="<?php echo plugins_url('assets/img/circle.png',dirname(__FILE__)); ?>" alt="<?php esc_attr_e('Rounded','ast'); ?>" />
            <span class="screen-reader-text"><?php esc_html_e('Rounded','ast'); ?></span>
        </label>
    </p>    
</fieldset>
<?php }
function azad_scroll_top_animation_field(){ 
    $settings = azad_scroll_top_get_plugin_settings( 'azad_scroll_top_animation' );
?>
<fieldset class="azad-scroll-top-vertical">
    <legend class="screen-reader-text"><span><?php esc_html_e('Animation','ast'); ?></span></legend>
    <p>
        <label for="enable_scroll_top">
            <input type="radio" name="azad_scroll_top_plugin_settings[azad_scroll_top_animation]"  value="fade" <?php checked('fade',$settings); ?> />
            <?php esc_html_e( 'Fade', 'scroll-top' ); ?>
        </label>
        <label for="enable_scroll_top">
            <input type="radio" name="azad_scroll_top_plugin_settings[azad_scroll_top_animation]"  value="slide" <?php checked('slide',$settings); ?> />
            <?php esc_html_e( 'Slide', 'scroll-top' ); ?>
        </label>
        <label for="enable_scroll_top">
            <input type="radio" name="azad_scroll_top_plugin_settings[azad_scroll_top_animation]"  value="none" <?php checked('none',$settings); ?>/>
            <?php esc_html_e( 'None', 'scroll-top' ); ?>
        </label>
    </p>    
</fieldset>
<?php }
function azad_scroll_top_speed_field(){ 
    $settings = azad_scroll_top_get_plugin_settings( 'azad_scroll_top_speed' );
?>
    <input type="number" name="azad_scroll_top_plugin_settings[azad_scroll_top_speed]" step="10" min="50" max="500" value="<?php echo (int) $settings; ?>" />
    <?php esc_html_e( 'milisecond', 'scroll-top' ); ?>
<?php }
function azad_scroll_top_distance_field(){ 
    $settings = azad_scroll_top_get_plugin_settings( 'azad_scroll_top_distance' );
?>
    <input type="number" name="azad_scroll_top_plugin_settings[azad_scroll_top_distance]" step="10" min="0" max="500" value="<?php echo (int) $settings; ?>" />
            <?php esc_html_e( 'px', 'scroll-top' ); ?>
<?php }
function azad_scroll_top_target_field(){ 
    $settings = azad_scroll_top_get_plugin_settings( 'azad_scroll_top_target' );
?>
    <input type="text" name="azad_scroll_top_plugin_settings[azad_scroll_top_target]"  value="<?php echo (int) $settings; ?>" />
    <?php esc_html_e( 'Example #page', 'scroll-top' ); ?>
<?php }
function azad_scroll_top_css_filed(){ 
    $settings = azad_scroll_top_get_plugin_settings( 'azad_scroll_top_css' );
?>
    <textarea type="text" name="azad_scroll_top_plugin_settings[azad_scroll_top_css]" cols="50" rows="12"></textarea>
<?php }
function azad_scroll_top_plugin_settings_render_page(){ ?>
<div class="wrap">
    <h2><?php esc_html_e('Azad Scroll Top Settings','ast')?></h2>
    <div id="poststuff">
        <div id="post-body" class="azad-scroll-top-settings metabox-holder columns-2">
            <div id="post-body-content">
                <form method="post" action="options.php">
                    <?php settings_fields('azad_scroll_top_settings'); ?>
                    <?php do_settings_sections('azad_scroll_top_settings_page'); ?>
                    <?php submit_button(esc_html__('Save Settings','ast'),'primary large'); ?>
                </form>
            </div>
            <div id="postbox-container-1" class="postbox-container">
                <div>
                    <div class="postbox">
                        <h3 class="hndle"><span><?php esc_html_e('Plugin Author','ast'); ?></span><h3>                                
                        <div class="inside">
                            <p>You need help with your website to fix an issue or to redesign it because you do not have to do it yourself. Great! i can help you. I am the plugin author and experienced WEB UI designer and WordPress developer with over six years of experience. I am the perfect freelancer for your project! Don't hesitate to <a href="https://www.gittechs.com/author" target="_blank">Contact Me</a>!</p>
                        </div>
                    </div>
                    <div class="postbox">
                        <h3 class="hndle"><span><?php esc_html_e('Plugin Info','ast'); ?></span><h3>
                        <div class="inside">
                            <ul class="ul-square">
                                <li><a href="https://www.gittechs.com/rate" target="_blank"><?php esc_html_e('Author','ast')?></a></li>
                                <li><a href="https://www.gittechs.com/rate" target="_blank"><?php esc_html_e('Support','ast')?></a></li>
                                <li><a href="https://www.gittechs.com/rate" target="_blank"><?php esc_html_e('Please rate the plugin','ast')?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br class="clear" />
    </div>
</div>
<?php }

function azad_scroll_top_plugin_settings_validate($settings){
    $settings['azad_scroll_top_enable'] = isset($settings['azad_scroll_top_enable']) && 1 == $settings['azad_scroll_top_enable'] ? 1 : 0;
    
    $valid_type = array('text','icon');
    if(!in_array($settings['azad_scroll_top_type'], $valid_type)){
        $settings['azad_scroll_top_type'] = 'icon';
    }
    
    $valid_position = array('left','right');
    if(!in_array($settings['azad_scroll_top_position'], $valid_position)){
        $settings['azad_scroll_top_position'] = 'right';
    }
    
    $valid_radius = array('rounded','square','circle');
    if(!in_array($settings['azad_scroll_top_radius'], $valid_radius)){
        $settings['azad_scroll_top_radius'] = 'rounded';
    }
    
    $valid_animation = array('fade','slide','none');
    if(!in_array($settings['azad_scroll_top_animation'], $valid_animation)){
        $settings['azad_scroll_top_animation'] = 'fade';
    }
    
    $settings['azad_scroll_top_bg_color']   = sanitize_hex_color($settings['azad_scroll_top_bg_color']);
    $settings['azad_scroll_top_color']      = sanitize_hex_color($settings['azad_scroll_top_color']);
    $settings['azad_scroll_top_speed']      = absint($settings['azad_scroll_top_speed']);
    $settings['azad_scroll_top_distance']   = absint($settings['azad_scroll_top_distance']);
    $settings['azad_scroll_top_target']     = absint($settings['azad_scroll_top_target']);
    $settings['azad_scroll_top_css']        = absint($settings['azad_scroll_top_css']);
    
    return $settings;
}