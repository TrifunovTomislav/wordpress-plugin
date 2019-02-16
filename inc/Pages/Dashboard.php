<?php
/**
* @package sparky plagin
*/

namespace Inc\Pages;

use Inc\Api\SettingsApi;
use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\ManagerCallbacks;
use Inc\Base\BaseControler;

class Dashboard 
{
    use BaseControler;

    public $settings;
    public $callbacks;
    public $pages = [];

    public function register(){
        $this->settings = new SettingsApi();
        $this->callbacks = new AdminCallbacks();
        $this->callbacks_mngr = new ManagerCallbacks();
        $this->setPages();
        $this->setSettings();
        $this->setSections();
        $this->setFields();

        $this->settings->addPages($this->pages)->withSubPage('Dashboard')->register();
    }

    public function setPages(){
        $this->pages = [
            [
                'page_title' => 'Sparky Plagin',
                'menu_title' => 'Sparky',
                'capability' => 'manage_options',
                'menu_slug'  => 'sparky_plagin',
                'callback'   => array($this->callbacks, 'adminDashboard'),
                'icon_url'   => 'dashicons-smiley',
                'position'   => 110
            ]
        ];
    }

    public function setSettings(){

        // this way makes only one data in the database
        // one array called "sparky_plagin"
        $args = [
            [
            'option_group'  =>  'sparky_plagin_settings',
            'option_name'   =>  'sparky_plagin',
            'callback'      =>  array($this->callbacks_mngr, 'checkboxSanitize')
            ]
        ];
        $this->settings->setSettings($args);
    }

    public function setSections(){

        $args = [
            [
                'id'            =>  'sparky_admin_index',
                'title'         =>  'Settings Manager',
                'callback'      =>  array($this->callbacks_mngr, 'sparkySectionManager'),
                'page'          =>  'sparky_plagin'
            ]
        ];
        $this->settings->setSections($args);
    }

    public function setFields(){

        $args = [];
        
        foreach ($this->managers as $key => $value){
            $args[] = [
                'id'            =>  $key,
                'title'         =>  $value,
                'callback'      =>  array($this->callbacks_mngr, 'checkboxField'),
                'page'          =>  'sparky_plagin',
                'section'       =>  'sparky_admin_index',
                'args'          =>  [
                                        'option_name' => 'sparky_plagin',
                                        'label_for'   => $key,
                                        'class'       =>  'ui-toggle'
                                    ]
            ];
        }
        $this->settings->setFields($args);
    }
}