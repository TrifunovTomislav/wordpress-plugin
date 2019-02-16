<?php
/**
* @package sparky plagin
*/

namespace Inc\Base;

use Inc\Api\SettingsApi;
use Inc\Base\BaseControler;
use Inc\Api\Callbacks\AdminCallbacks;

class TemplatesManagerControler
{
    use BaseControler;
    public $subpages = [];
    public $callbacks;
    public $templates;

    public function register(){

        if(!$this->activated('templates_manager')) return;

        $this->settings = new SettingsApi();
        $this->callbacks = new AdminCallbacks();
        $this->setSubpages();
        $this->settings->addSubPages($this->subpages)->register();

        $this->templates = [
            'page-templates/two-columns-tpl.php' => 'Two Columns Layout'
        ];

        add_filter('theme_page_templates', array($this, 'customTemplates'));
        add_filter('template_include', array($this, 'loadTemplate'));

    }

    public function customTemplates($templates){
        $templates = array_merge($templates, $this->templates);
        return $templates;
    }

    public function loadTemplate($template){
        global $post;
        if(!$post){
            return $template;
        }
        // if is the fron page, load a custom template
        if(is_front_page()){
            $file = SPARKY_PLUGIN_PATH . 'page-templates/front-page.php';
            if(file_exists($file)){
                return $file;
            }
        }

        $template_name = get_post_meta($post->ID, '_wp_page_template', true);
        
        if(!isset($this->templates[$template_name])){
            return $template;
        }

        $file = SPARKY_PLUGIN_PATH . $template_name;

        if(file_exists($file)){
            return $file;
        }
        return $template;
    }

    public function setSubpages(){
        $this->subpages = [
            [
                'parent_slug' =>  'sparky_plagin',
                'page_title'  =>  'Custom Templates Manager',
                'menu_title'  =>  'Templates Manager',
                'capability'  =>  'manage_options',
                'menu_slug'   =>  'sparky_templates',
                'callback'    =>  array($this->callbacks, 'templates')
            ]
        ];
    }

}

