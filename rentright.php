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
    require RENTRIGHT_PATH . '/inc/class-rentrightcpt.php';
}
if(!class_exists('Gamajo_Template_Loader')){
    require RENTRIGHT_PATH . 'inc/class-gamajo-template-loader.php';
}
require RENTRIGHT_PATH . '/inc/class-rentright-template-loader.php';
require RENTRIGHT_PATH . '/inc/class-rentright-shortcodes.php';
require RENTRIGHT_PATH . '/inc/class-rentright-filter-widget.php';
require RENTRIGHT_PATH . '/inc/class-rentright-elementor.php';

class RentRight{

    function register(){
        add_action('admin_enqueue_scripts',[$this,'enqueue_admin']);
        add_action('wp_enqueue_scripts', [$this,'enqueue_front']);

        add_action('plugins_loaded',[$this,'load_text_domain']);
        add_action('widgets_init',[$this,'register_widget']);
        add_action('admin_menu',[$this,'add_menu_item']);
        add_filter('plugin_action_links_'.plugin_basename(__FILE__),[$this,'add_plugin_setting_link']);
        add_action('admin_init',[$this, 'settings_init']);
    }

    public function settings_init(){

        register_setting('rentright_settings','rentright_settings_options');

        add_settings_section('rentright_settings_section', esc_html__('Settings','rentright'), [$this,'rentright_settings_section_html'],'rentright_settings');

        add_settings_field('filter_title', esc_html__('Title for Filter','rentright'), [$this,'filter_title_html'],'rentright_settings','rentright_settings_section');
        add_settings_field('archive_title', esc_html__('Title for Archive Page','rentright'), [$this,'archive_title_html'],'rentright_settings','rentright_settings_section');
    }

    public function rentright_settings_section_html(){
        esc_html_e('Settings for RentRight Plugin');
    }

    public function filter_title_html(){

        $options = get_option('rentright_settings_options');

        ?>
        <input type="text" name="rentright_settings_options[filter_title]" value="<?php echo isset($options['filter_title']) ? $options['filter_title'] : "";  ?>" />
        <?php
    }

    public function archive_title_html(){

        $options = get_option('rentright_settings_options');

        ?>
        <input type="text" name="rentright_settings_options[archive_title]" value="<?php echo isset($options['archive_title']) ? $options['archive_title'] : "";  ?>" />
        <?php
    }

    public function add_plugin_setting_link($link){

        $rentright_link = '<a href="admin.php?page=rentright_settings">'.esc_html__('Settings Page','rentright').'</a>';
        array_push($link,$rentright_link);

        return $link;
    }

    public function add_menu_item(){
        add_menu_page(
            esc_html__('RentRight Settings Page','rentright'),
            esc_html__('RentRight','rentright'),
            'manage_options',
            'rentright_settings',
            [$this,'main_admin_page'],
            'dashicons-admin-plugins',
            100,
        );
    }

    public function main_admin_page(){
        require_once RENTRIGHT_PATH .'admin/welcome.php';
    }

    public function register_widget(){
        register_widget('rentright_filter_widget');
    }

    public function get_terms_hierarchical($tax_name,$current_term){

        $taxonomy_terms = get_terms($tax_name,['hide_empty'=>'false','parent'=>0]);

        $html = '';
        if(!empty($taxonomy_terms)){
            foreach($taxonomy_terms as $term){
                if($current_term == $term->term_id){
                    $html .= '<option value="'.$term->term_id.'" selected >'.$term->name.'</option>';
                } else {
                    $html .= '<option value="'.$term->term_id.'" >'.$term->name.'</option>';
                }

                $child_terms = get_terms($tax_name, ['hide_empty'=>false, 'parent'=>$term->term_id]);
                
                if(!empty($child_terms)){
                    foreach($child_terms as $child){
                        if($current_term == $child->term_id){
                            $html .= '<option value="'.$child->term_id.'" selected > - '.$child->name.'</option>';
                        } else {
                            $html .= '<option value="'.$child->term_id.'" > - '.$child->name.'</option>';
                        }
                    }
                }
            
            }
        }
        return $html;
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