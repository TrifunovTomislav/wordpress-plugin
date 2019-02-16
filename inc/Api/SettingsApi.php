<?php
/**
* @package sparky plagin
*/

namespace Inc\Api;

class SettingsApi
{
    public $admin_pages = [];
    public $admin_subpages = [];
    public $settings = [];
    public $sections = [];
    public $fields = [];

    public function register(){
        if(!empty($this->admin_pages) || !empty($this->admin_subpages)){
            add_action('admin_menu', array($this,'addAdminMenu'));
        }

        if(!empty($this->settings)){
            add_action('admin_init', array($this, 'registerCustomFields'));
        }
    }

    public function addPages(array $pages){
        $this->admin_pages = $pages;
        return $this;
    }

    // define the subpages functionality
    public function withSubPage(string $title = null){
        if (empty($this->admin_pages)){
            return $this;
        }
        
        $admin_page = $this->admin_pages[0];
        $subpage = [
            [
                'parent_slug' =>  $admin_page['menu_slug'],
                'page_title'  =>  $admin_page['page_title'],
                'menu_title'  =>  ($title) ? $title : $admin_page['menu_title'],
                'capability'  =>  $admin_page['capability'],
                'menu_slug'   =>  $admin_page['menu_slug'],
                'callback'    =>  function(){ }
            ]
        ];
        $this->admin_subpages = $subpage;
        return $this;
    }

    // method to add subpages to a page defined
    public function addSubPages(array $pages){
        $this->admin_subpages = array_merge($this->admin_subpages, $pages);
        return $this;
    }

    //add admin menu page and other pages from trhe Admin.php
    public function addAdminMenu(){
        foreach($this->admin_pages as $page){
            add_menu_page(  
                            $page['page_title'], 
                            $page['menu_title'], 
                            $page['capability'], 
                            $page['menu_slug'], 
                            $page['callback'], 
                            $page['icon_url'], 
                            $page['position']
                        );
        }

        foreach($this->admin_subpages as $page){
            add_submenu_page(  
                            $page['parent_slug'], 
                            $page['page_title'], 
                            $page['menu_title'], 
                            $page['capability'], 
                            $page['menu_slug'], 
                            $page['callback']
                        );
        }
    }

    public function setSettings(array $settings){
        $this->settings = $settings;
        return $this;
    }
    
    public function setSections(array $sections){
        $this->sections = $sections;
        return $this;
    }

    public function setFields(array $fields){
        $this->fields = $fields;
        return $this;
    }

    public function registerCustomFields(){
       
        // register settings
        foreach($this->settings as $setting){  
            register_setting(
                $setting['option_group'], 
                $setting['option_name'], 
                isset($setting['callback']) ? $setting['callback'] : ""
            );
        }
        
        // add the settings section
        foreach($this->sections as $section){
            add_settings_section(
                $section['id'], 
                $section['title'], 
                isset($section['callback']) ? $section['callback'] : "", 
                $section['page']
            );
    }

        // add settings field
        foreach($this->fields as $field){
            add_settings_field( 
                $field['id'], 
                $field['title'], 
                isset($field['callback']) ? $field['callback'] : "", 
                $field['page'], 
                $field['section'], 
                isset($field['args']) ? $field['args'] : "" 
            );
        }
    }
}