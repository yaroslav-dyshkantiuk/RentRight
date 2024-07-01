<?php
/*
Plugin Name: RentRight
Plugin URI: #
Description: First Plugin
Version: 1.0
Author: Yaroslav Dyshkantiuk
Author URI: #
License: GPLv2 or later
Text Domain: rentright
Domain Path:  /lang
*/

if(!defined('ABSPATH')){
    die;
}

define('RENTRIGHT_PATH',plugin_dir_path(__FILE__));

if(!class_exists('RentRightCpt')){
    require RENTRIGHT_PATH . '/inc/cpt.php';
}

class RentRight{

    function register(){
        add_action('admin_enqueue_scripts',[$this,'enqueue_admin']);
        add_action('wp_enqueue_scripts', [$this,'enqueue_front']);

        add_action('plugins_loaded',[$this,'load_text_domain']);
    }

    function load_text_domain(){
        load_plugin_textdomain('rentright', false, dirname(plugin_basename(__FILE__)).'/lang');
    }

    public function enqueue_admin(){
        wp_enqueue_style('rentRight_style_admin', plugins_url('/assets/css/admin/style.css',__FILE__));
        wp_enqueue_script('rentRight_script_admin', plugins_url('/assets/js/admin/scripts.js', __FILE__),array('jquery'),'1.0',true);
    }

    public function enqueue_front(){
        wp_enqueue_style('rentRight_style', plugins_url('/assets/css/front/style.css',__FILE__));
        wp_enqueue_script('rentRight_script', plugins_url('/assets/js/front/scripts.js', __FILE__),array('jquery'),'1.0',true);
        wp_enqueue_script('jquery-form');
    }

    static function activation(){
        flush_rewrite_rules();
    }
    static function deactivation(){
        flush_rewrite_rules();
    }
}

if(class_exists('RentRight')){
    $rentRight = new RentRight();
    $rentRight->register();
}

register_activation_hook(__FILE__, array($rentRight, 'activation'));
register_deactivation_hook(__FILE__, array($rentRight, 'deactivation'));