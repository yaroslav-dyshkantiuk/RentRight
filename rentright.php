<?php
/*
Plugin Name: RentRight
Plugin URI: #
Description: First Plugin
Version: 1.0
Author: Yaroslav Dyshkantiuk
Author URI: #
Licence: GPLv2 or later
Text Domain: rentright
Domain Path:  /lang
*/

if(!defined('ABSPATH')){
    die;
}

class RentRight{
    static function activation(){
        flush_rewrite_rules();
    }
    static function deactivation(){
        flush_rewrite_rules();
    }
}
if(class_exists('RentRight')){
    $rentRight = new RentRight();
}
register_activation_hook(__FILE_, array($rentRight, 'activation'));
register_deactivation_hook(__FILE_, array($rentRight, 'deactivation'));