<?php
/**
* @package sparky plagin
*/

namespace Inc\Pages;

use Inc\Api\SettingsApi;
use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\ManagerCallbacks;
use Inc\Base\BaseControler;

class Admin 
{
    use BaseControler;

    public $settings;
    public $callbacks;
    public $pages = [];
    public $subpages = [];

    public function register(){
        $this->settings = new SettingsApi();
        $this->callbacks = new AdminCallbacks();
        $this->callbacks_mngr = new ManagerCallbacks();
        $this->setPages();
        $this->setSubpages();
        $this->setSettings();
        $this->setSections();
        $this->setFields();
        $this->settings->addPages($this->pages)->withSubPage('Dashboard')->addSubPages($this->subpages)->register();
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
            ],
            [
                'page_title' => 'Larky Plagin',
                'menu_title' => 'Larky',
                'capability' => 'manage_options',
                'menu_slug'  => 'larky_plagin',
                'callback'   => array($this->callbacks, 'larkys'),
                'icon_url'   => 'dashicons-smiley',
                'position'   => 111
            ]
        ];
    }

    public function setSubpages(){
        $this->subpages = [
            [
                'parent_slug' =>  'sparky_plagin',
                'page_title'  =>  'Custom Post Types',
                'menu_title'  =>  'CPT',
                'capability'  =>  'manage_options',
                'menu_slug'   =>  'sparky_cpt',
                'callback'    =>  array($this->callbacks, 'cpt')
            ],
            [
                'parent_slug' =>  'sparky_plagin',
                'page_title'  =>  'Custom Taxonomies',
                'menu_title'  =>  'Taxonomies',
                'capability'  =>  'manage_options',
                'menu_slug'   =>  'sparky_Taxonomies',
                'callback'    =>  array($this->callbacks, 'taxonomies')
            ],
            [
                'parent_slug' =>  'sparky_plagin',
                'page_title'  =>  'Custom Widgets',
                'menu_title'  =>  'Widgets',
                'capability'  =>  'manage_options',
                'menu_slug'   =>  'sparky_widgets',
                'callback'    =>  array($this->callbacks, 'widgets')
            ],
            [
                'parent_slug' =>  'larky_plagin',
                'page_title'  =>  'Custom Post Grapes',
                'menu_title'  =>  'GPT',
                'capability'  =>  'manage_options',
                'menu_slug'   =>  'sparky_gpt',
                'callback'    =>  array($this->callbacks, 'customPostGrapes')
            ],
            [
                'parent_slug' =>  'larky_plagin',
                'page_title'  =>  'Custom Post Types',
                'menu_title'  =>  'CPT',
                'capability'  =>  'manage_options',
                'menu_slug'   =>  'larky_cpt',
                'callback'    =>  array($this->callbacks, 'cpt')
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
        // this way puts to much data in the database
        // foreach ($this->managers as $key => $value){
        //     $args[] = [
        //                 'option_group'  =>  'sparky_palgin_settings',
        //                 'option_name'   =>  $key,
        //                 'callback'      =>  array($this->callbacks_mngr, 'checkboxSanitize')
        //     ];
        // }
        // example of what one member looks like
        // $args = [
        //     [
        //         'option_group'  =>  'sparky_palgin_settings',
        //         'option_name'   =>  'cpt_manager',
        //         'callback'      =>  array($this->callbacks_mngr, 'checkboxSanitize')
        //     ],
        // ]
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
        // example of one member
        // $args = [
        //     [
        //         'id'            =>  'cpt_manager',
        //         'title'         =>  'Activate CPT Manager',
        //         'callback'      =>  array($this->callbacks_mngr, 'checkboxField'),
        //         'page'          =>  'sparky_plagin',
        //         'section'       =>  'sparky_admin_index',
        //         'args'          =>  [
        //                                 'label_for' => 'cpt_manager',
        //                                 'class'     =>  'ui-toggle'
        //                             ]
        //     ]
        $this->settings->setFields($args);
    }
}