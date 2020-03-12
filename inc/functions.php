<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function ast_default_settings(){
    $default_settings = array(
        'azad_scroll_top_enable'        => 1,
        'azad_scroll_top_type'          => 'icon',
        'azad_scroll_top_bg_color'      => '#00bfff',
        'azad_scroll_top_position'      => 'right',
        'azad_scroll_top_color'         => '#ffffff',
        'azad_scroll_top_radius'        => 'square',
        'azad_scroll_top_animation'     => 'fade',
        'azad_scroll_top_speed'         => 300,
        'azad_scroll_top_distance'      => 300,
        'azad_scroll_top_target'        => '',
        'azad_scroll_top_css'           => ''
    );
    return apply_filters( 'ast_default_settings', $default_settings );
}
function azad_scroll_top_get_plugin_settings( $option = '' ){
    $settings = get_option( 'azad_scroll_top_plugin_settings', ast_default_settings() );
    return $settings[$option];
}
function azad_scroll_top_head(){ 
    $enable         = azad_scroll_top_get_plugin_settings('azad_scroll_top_enable');
    $radius         = azad_scroll_top_get_plugin_settings('azad_scroll_top_radius');
    $bg_color       = azad_scroll_top_get_plugin_settings('azad_scroll_top_bg_color');
    $icon_color     = azad_scroll_top_get_plugin_settings('azad_scroll_top_color');
    
    // Border Radius
    $scroll_radius = '0';
    if($radius === 'rounded'){
        $scroll_radius = '4px';
    }elseif($radius === 'circle'){
        $scroll_radius = '50%';
    }else{
        $scroll_radius = '0';
    }
    if($enable){
        echo "<style>
            #scrollUp {
                border-radius: $scroll_radius;
                background: $bg_color;
                color: $icon_color;
                font-size: 20px;
                border: 2px dotted white;
                box-sizing: content-box;
				transition: all .5s;
            }
			#scrollUp:hover {
                border-radius: $scroll_radius;
                background: $icon_color;
                color: $bg_color;
                font-size: 20px;
                border: 2px dotted $bg_color;
                box-sizing: content-box;
            }
        </style>";  
    }
}    
add_action('wp_head','azad_scroll_top_head');
function azad_scroll_top(){
    $enable         = azad_scroll_top_get_plugin_settings('azad_scroll_top_enable');
    $scroll_type    = '<span class="scroll-top"><i class="icon-up-open"></i></span>';
    $distance       = azad_scroll_top_get_plugin_settings('azad_scroll_top_distance');
    $speed          = absint(azad_scroll_top_get_plugin_settings('azad_scroll_top_speed'));
    $animation      = azad_scroll_top_get_plugin_settings('azad_scroll_top_animation');
    if($enable){
        echo "<script>
            // Parse URL Queries Method
            (function ($) {
                $(function () {
                        $.scrollUp({
                            //animation: $animation,
                            animationSpeed: 300,
                            scrollDistance: $distance,
                            scrollSpeed: $speed,
                            easingType: 'linear',
                            activeOverlay: false,
                            scrollText: '$scroll_type' 
                            //scrollTarget: true 
                        });
                    });
            })(jQuery);
        </script>";
    }    
}
add_action('wp_footer','azad_scroll_top');
function azad_scroll_top_scripts(){
    $enable = azad_scroll_top_get_plugin_settings('azad_scroll_top_enable');
    if($enable){
        wp_enqueue_style('azad-scroll-top-settings',plugins_url('assets/css/scroll-top.css',dirname(__FILE__)),array(),'0.0.0.1','all');
        wp_enqueue_script('azad-scroll-top-script',plugins_url('assets/js/jquery.scrollUp.min.js',dirname(__FILE__)),array("jquery"),'0.0.0.1',false);
    }
}
add_action('wp_enqueue_scripts','azad_scroll_top_scripts');