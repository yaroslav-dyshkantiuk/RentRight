<?php
class RentRight_Template_Loader extends Gamajo_Template_Loader{

    protected $filter_prefix = 'rentright';

    protected $theme_template_directory = 'rentright';

    protected $plugin_directory = RENTRIGHT_PATH;

    protected $plugin_template_directory = 'templates';

    public $templates;

    public function register(){
        add_filter('template_include', [$this,'rentright_templates']);

        $this->templates = array(
            'pages/template-custom.php' => 'Custom Template',
        );
        add_filter('theme_page_templates', [$this, 'custom_template']);
        add_filter('template_include', [$this, 'load_template']);
    }

    public function load_template($template){

        global $post;

        $template_name = get_post_meta($post->ID, '_wp_page_template', true);

        if($this->templates[$template_name]) {
            $file = RENTRIGHT_PATH . $template_name;
            if(file_exists($file)){
                return file;
            }
        }

        return $template;
    }

    public function custom_template($templates){

        $templates = array_merge($templates, $this->templates);

        return $templates;
    }

    public function rentright_templates($template){

        if(is_post_type_archive('property')){
            $theme_files = ['archive-property.php','rentright/archive-property.php'];
            $exist = locate_template($theme_files, false);
            if($exist != ''){
                return $exist;
            } else {
                return plugin_dir_path(__DIR__).'templates/archive-property.php';
            }
        } elseif(is_post_type_archive('agent')){
            $theme_files = ['archive-agent.php','rentright/archive-agent.php'];
            $exist = locate_template($theme_files, false);
            if($exist != ''){
                return $exist;
            } else {
                return plugin_dir_path(__DIR__).'templates/archive-agent.php';
            }
        } elseif(is_singular('property')){
            $theme_files = ['single-property.php','rentright/single-property.php'];
            $exist = locate_template($theme_files, false);
            if($exist != ''){
                return $exist;
            } else {
                return plugin_dir_path(__DIR__).'templates/single-property.php';
            }
        } elseif(is_singular('agent')){
            $theme_files = ['single-agent.php','rentright/single-agent.php'];
            $exist = locate_template($theme_files, false);
            if($exist != ''){
                return $exist;
            } else {
                return plugin_dir_path(__DIR__).'templates/single-agent.php';
            }
        }

        return $template;
    }
}

$rentRight_Template = new RentRight_Template_Loader();
$rentRight_Template->register();