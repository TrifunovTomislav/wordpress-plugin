<?php
/**
* @package sparky plagin
*/

namespace Inc\Base;

use Inc\Api\SettingsApi;
use Inc\Base\BaseControler;
use Inc\Api\Callbacks\CptCallbacks;
use Inc\Api\Callbacks\AdminCallbacks;

class CustomPostTypeControler
{
    use BaseControler;
    public $subpages = array();
    public $callbacks;
    public $cpt_callbacks;
    public $custom_post_types = array();
    public $settings;

    public function register(){

        if(!$this->activated('cpt_manager')) return;

        $this->settings = new SettingsApi();
        $this->callbacks = new AdminCallbacks();
        $this->cpt_callbacks = new CptCallbacks();
        $this->setSubpages();
        $this->setSettings();
        $this->setSections();
        $this->setFields();
        $this->settings->addSubPages($this->subpages)->register();
        $this->storeCustomPostTypes();

        if(!empty($this->custom_post_types)){
            add_action('init', array($this,'registerCustomPostTypes'));
        }
        
    }

    public function setSubpages(){
        $this->subpages = [
            [
                'parent_slug' =>  'sparky_plagin',
                'page_title'  =>  'Custom Post Types',
                'menu_title'  =>  'CPT Manager',
                'capability'  =>  'manage_options',
                'menu_slug'   =>  'sparky_cpt',
                'callback'    =>  array($this->callbacks, 'cpt')
            ]
        ];
    }

    public function setSettings(){

        $args = [
            [
            'option_group'  =>  'sparky_plagin_cpt_settings',
            'option_name'   =>  'sparky_plagin_cpt',
            'callback'      =>  array($this->cpt_callbacks, 'cptSanitize')
            ]
        ];
        $this->settings->setSettings($args);
    }

    public function setSections(){

        $args = [
            [
                'id'            =>  'sparky_cpt_index',
                'title'         =>  'Custom Post Type Manager',
                'callback'      =>  array($this->cpt_callbacks, 'cptSectionManager'),
                'page'          =>  'sparky_cpt'
            ]
        ];
        $this->settings->setSections($args);
    }

    public function setFields(){

        $args = [
            [
                'id'       =>  'post_type',
                'title'    =>  'Custom Post Type ID',
                'callback' =>  array($this->cpt_callbacks, 'textField'),
                'page'     =>  'sparky_cpt',
                'section'  =>  'sparky_cpt_index',
                'args'     =>  [
                                    'option_name' => 'sparky_plagin_cpt',
                                    'label_for'   => 'post_type',
                                    'placeholder' => 'eg. Product'
                                ]
            ],
            [
                'id'       =>  'singular_name',
                'title'    =>  'Singular name',
                'callback' =>  array($this->cpt_callbacks, 'textField'),
                'page'     =>  'sparky_cpt',
                'section'  =>  'sparky_cpt_index',
                'args'     =>  [
                                    'option_name' => 'sparky_plagin_cpt',
                                    'label_for'   => 'singular_name',
                                    'placeholder' => 'eg. Product'
                                ]
            ],
            [
                'id'       =>  'plural_name',
                'title'    =>  'Plural name',
                'callback' =>  array($this->cpt_callbacks, 'textField'),
                'page'     =>  'sparky_cpt',
                'section'  =>  'sparky_cpt_index',
                'args'     =>  [
                                    'option_name' => 'sparky_plagin_cpt',
                                    'label_for'   => 'plural_name',
                                    'placeholder' => 'eg. Products'
                                ]
            ],
            [
                'id'       =>  'public',
                'title'    =>  'Public',
                'callback' =>  array($this->cpt_callbacks, 'checkboxField'),
                'page'     =>  'sparky_cpt',
                'section'  =>  'sparky_cpt_index',
                'args'     =>  [
                                    'option_name' => 'sparky_plagin_cpt',
                                    'label_for'   => 'public',
                                    'class'       =>  'ui-toggle'
                                ]
            ],
            [
                'id'       =>  'has_archive',
                'title'    =>  'Archive',
                'callback' =>  array($this->cpt_callbacks, 'checkboxField'),
                'page'     =>  'sparky_cpt',
                'section'  =>  'sparky_cpt_index',
                'args'     =>  [
                                    'option_name' => 'sparky_plagin_cpt',
                                    'label_for'   => 'has_archive',
                                    'class'       =>  'ui-toggle'
                                ]
            ]
        ];   
        $this->settings->setFields($args);
    }

    public function storeCustomPostTypes(){

        $options = get_option('sparky_plagin_cpt') ?: array();

         foreach ($options as $option){
            $this->custom_post_types[] = 
                [
                    'post_type'     => $option['post_type'],
                    'name'          => $option['plural_name'],
                    'singular_name' => $option['singular_name'],
                    'public'        => isset($option['public']) ?: false,
                    'has_archive'   => isset($option['has_archive']) ?: false
                
            ];
        } 
    }

    // Register Custom Post Type
    public function registerCustomPostTypes(){
        
        foreach ($this->custom_post_types as $post_type) {
            
            $labels = array(
                'name'                  => _x( $post_type['name'], 'Post Type General Name', 'text_domain' ),
                'singular_name'         => _x( $post_type['singular_name'], 'Post Type Singular Name', 'text_domain' ),
                'menu_name'             => __( $post_type['name'], 'text_domain' ),
                'name_admin_bar'        => __( $post_type['singular_name'], 'text_domain' ),
                'archives'              => __( $post_type['singular_name'] . ' Archives', 'text_domain' ),
                'attributes'            => __( $post_type['singular_name'] . ' Attributes', 'text_domain' ),
                'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
                'all_items'             => __( 'All ' . $post_type['name'], 'text_domain' ),
                'add_new_item'          => __( 'Add New ' . $post_type['singular_name'], 'text_domain' ),
                'add_new'               => __( 'Add New', 'text_domain' ),
                'new_item'              => __( 'New ' . $post_type['singular_name'], 'text_domain' ),
                'edit_item'             => __( 'Edit ' . $post_type['singular_name'], 'text_domain' ),
                'update_item'           => __( 'Update ' . $post_type['singular_name'], 'text_domain' ),
                'view_item'             => __( 'View ' . $post_type['singular_name'], 'text_domain' ),
                'view_items'            => __( 'View ' . $post_type['name'], 'text_domain' ),
                'search_items'          => __( 'Search ' . $post_type['singular_name'], 'text_domain' ),
                'not_found'             => __( 'Not found', 'text_domain' ),
                'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
                'featured_image'        => __( 'Featured Image', 'text_domain' ),
                'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
                'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
                'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
                'insert_into_item'      => __( 'Insert into ' . $post_type['singular_name'], 'text_domain' ),
                'uploaded_to_this_item' => __( 'Uploaded to this ' . $post_type['singular_name'], 'text_domain' ),
                'items_list'            => __( $post_type['name'] . ' list', 'text_domain' ),
                'items_list_navigation' => __( $post_type['name'] . ' list navigation', 'text_domain' ),
                'filter_items_list'     => __( 'Filter '. $post_type['name'] . ' list', 'text_domain' ),
            );
            $args = array(
                'label'                 => __( $post_type['singular_name'], 'text_domain' ),
                'description'           => __( $post_type['name'] . ' i njihova namena', 'text_domain' ),
                'labels'                => $labels,
                'supports'              => array( 'title', 'editor', 'thumbnail' ),
                'show_in_rest'           => true,
                'taxonomies'            => array( 'category', 'post_tag' ),
                'hierarchical'          => false,
                'public'                => $post_type['public'],
                'show_ui'               => true,
                'show_in_menu'          => true,
                'menu_position'         => 5,
                'menu_icon'				=> 'dashicons-smiley',
                'show_in_admin_bar'     => true,
                'show_in_nav_menus'     => true,
                'can_export'            => true,
                'has_archive'           => $post_type['has_archive'],
                'exclude_from_search'   => false,
                'publicly_queryable'    => true,
                'capability_type'       => 'post',
            );
            register_post_type( $post_type['post_type'], $args );
        }    
    }
}

