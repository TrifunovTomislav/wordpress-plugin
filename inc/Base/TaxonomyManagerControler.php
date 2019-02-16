<?php
/**
* @package sparky plagin
*/

namespace Inc\Base;

use Inc\Api\SettingsApi;
use Inc\Base\BaseControler;
use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\TaxonomyCallbacks;

class TaxonomyManagerControler
{
    use BaseControler;
    public $settings;
    public $tax_callbacks;
    public $subpages = [];
    public $taxonomyes =[];
    public $callbacks;

    public function register(){

        if(!$this->activated('taxonomy_manager')) return;

        $this->settings = new SettingsApi();
        $this->callbacks = new AdminCallbacks();
        $this->tax_callbacks = new TaxonomyCallbacks;
        $this->setSubpages();
        $this->setSettings();
        $this->setSections();
        $this->setFields();
        $this->settings->addSubPages($this->subpages)->register();
        $this->storeCustomTaxonomyes();
        
        if(!empty($this->taxonomyes)){
            add_action('init', array($this,'registerCustomTaxonomy'));
        }

    }

    public function setSubpages(){
        $this->subpages = [
            [
                'parent_slug' =>  'sparky_plagin',
                'page_title'  =>  'Custom Taxonomyes',
                'menu_title'  =>  'Taxonomy Manager',
                'capability'  =>  'manage_options',
                'menu_slug'   =>  'sparky_taxonomy',
                'callback'    =>  array($this->callbacks, 'taxonomyes')
            ]
        ];
    }

    public function setSettings(){
        $args = [
            [
                'option_group'  =>  'sparky_plagin_tax_settings',
                'option_name'   =>  'sparky_plagin_tax',
                'callback'      =>  array($this->tax_callbacks, 'taxSanitize')
            ]
        ];
        $this->settings->setSettings($args);
    }

    public function setSections(){
        $args = [
            [
                'id'            =>  'sparky_tax_index',
                'title'         =>  'Custom Taxonomy Manager',
                'callback'      =>  array($this->tax_callbacks, 'taxSectionManager'),
                'page'          =>  'sparky_taxonomy'
            ]
        ];
        $this->settings->setSections($args);
    }

    public function setFields(){

        $args = [
            [
                'id'       =>  'taxonomy',
                'title'    =>  'Custom Taxonomy ID',
                'callback' =>  array($this->tax_callbacks, 'textField'),
                'page'     =>  'sparky_taxonomy',
                'section'  =>  'sparky_tax_index',
                'args'     =>  [
                                    'option_name' => 'sparky_plagin_tax',
                                    'label_for'   => 'taxonomy',
                                    'placeholder' => 'eg. genre',
                                    'array'       => 'taxonomy'
                                ]
            ],
            [
                'id'       =>  'singular_name',
                'title'    =>  'Singular name',
                'callback' =>  array($this->tax_callbacks, 'textField'),
                'page'     =>  'sparky_taxonomy',
                'section'  =>  'sparky_tax_index',
                'args'     =>  [
                                    'option_name' => 'sparky_plagin_tax',
                                    'label_for'   => 'singular_name',
                                    'placeholder' => 'eg. Genre',
                                    'array'       => 'taxonomy'
                                ]
            ],
            [
                'id'       =>  'hierarchical',
                'title'    =>  'Hierarchical',
                'callback' =>  array($this->tax_callbacks, 'checkboxField'),
                'page'     =>  'sparky_taxonomy',
                'section'  =>  'sparky_tax_index',
                'args'     =>  [
                                    'option_name' => 'sparky_plagin_tax',
                                    'label_for'   => 'hierarchical',
                                    'class'       =>  'ui-toggle',
                                    'array'       => 'taxonomy'
                                ]
            ],
            [
                'id'       =>  'objects',
                'title'    =>  'Post Types',
                'callback' =>  array($this->tax_callbacks, 'checkboxPostTypesField'),
                'page'     =>  'sparky_taxonomy',
                'section'  =>  'sparky_tax_index',
                'args'     =>  [
                                    'option_name' => 'sparky_plagin_tax',
                                    'label_for'   => 'objects',
                                    'class'       =>  'ui-toggle',
                                    'array'       => 'taxonomy'
                                ]
            ]
        ];   
        $this->settings->setFields($args);
    }

    public function storeCustomTaxonomyes(){

        $options = get_option( 'sparky_plagin_tax' ) ?: array();

        foreach($options as $option){
            $this->taxonomyes[] = [
                'taxonomy'      => $option['taxonomy'],
                'singular_name' => $option['singular_name'],
                'hierarchical'  => isset($option['hierarchical']) ?: false,
                'objects'       => isset($option['objects']) ? $option['objects'] : null
            ];
        }
        
    }

    // Register Custom Taxonomy
    function registerCustomTaxonomy() {
        foreach($this->taxonomyes as $taxonomy){
            $labels = array(
                'name'                       => _x( $taxonomy['singular_name'] . 's', 'Taxonomy General Name', 'text_domain' ),
                'singular_name'              => _x( $taxonomy['singular_name'], 'Taxonomy Singular Name', 'text_domain' ),
                'menu_name'                  => __( $taxonomy['singular_name'], 'text_domain' ),
                'all_items'                  => __( 'All ' . $taxonomy['singular_name'], 'text_domain' ),
                'parent_item'                => __( 'Parent Item', 'text_domain' ),
                'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
                'new_item_name'              => __( 'New Item Name', 'text_domain' ),
                'add_new_item'               => __( 'Add New Item', 'text_domain' ),
                'edit_item'                  => __( 'Edit Item', 'text_domain' ),
                'update_item'                => __( 'Update Item', 'text_domain' ),
                'view_item'                  => __( 'View Item', 'text_domain' ),
                'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
                'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
                'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
                'popular_items'              => __( 'Popular Items', 'text_domain' ),
                'search_items'               => __( 'Search Items', 'text_domain' ),
                'not_found'                  => __( 'Not Found', 'text_domain' ),
                'no_terms'                   => __( 'No items', 'text_domain' ),
                'items_list'                 => __( 'Items list', 'text_domain' ),
                'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
            );
            $args = array(
                'labels'                     => $labels,
                'hierarchical'               => $taxonomy['hierarchical'],
                'public'                     => true,
                'show_ui'                    => true,
                'show_admin_column'          => true,
                'show_in_nav_menus'          => true,
                'show_tagcloud'              => true,
            );
                        
            $objects = isset($taxonomy['objects']) ? array_keys( $taxonomy['objects'] ) : null;
            register_taxonomy( $taxonomy['taxonomy'], $objects, $args );
        }
    }
}

