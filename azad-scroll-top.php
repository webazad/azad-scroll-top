<?php
/* 
Plugin Name: Azad Scroll Top
Description: A very simple scroll top button
Plugin URi: gittechs.com/plugin/azad-scroll-top 
Author: Md. Abul Kalam Azad
Author URI: gittechs.com/author
Author Email: webdevazad@gmail.com
Version: 0.0.0.1
Text Domain: azad-scroll-top
*/
if(! defined('ABSPATH')) exit;
class Azad_Scroll_Top{
    public function __construct(){
        add_action('plugins_loaded',array($this,'constants'),1);
        add_action('plugins_loaded',array($this,'i18n'),2);
        add_action('plugins_loaded',array($this,'includes'),3);
        add_action('plugins_loaded',array($this,'admin'),4);
    }
    public function constants(){
        //echo 'Constants';
    }
    public function i18n(){
        //echo 'i18n';
    }
    public function includes(){
        define('ADMIN',plugin_dir_path(__FILE__));
        require_once(ADMIN.'inc/functions.php');
    }
    public function admin(){
        if(is_admin()){
            require_once(ADMIN.'admin/admin.php');            
        }
    }
    public function __destruct(){}
}
new Azad_Scroll_Top();
