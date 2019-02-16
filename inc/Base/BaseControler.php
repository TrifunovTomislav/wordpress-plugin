<?php
/**
* @package sparky plagin
*/

namespace Inc\Base;

trait BaseControler 
{

    public $managers = [
        'cpt_manager'         =>    'Activate CPT Manager',
        'taxonomy_manager'    =>    'Activate Taxonomy Manager',
        'media_widgets'       =>    'Activate Widget Manager',
        'gallery_manager'     =>    'Activate Gallery Manager',
        'testimonial_manager' =>    'Activate Testimonials Manager',
        'templates_manager'   =>    'Activate Templates Manager',
        'login_manager'       =>    'Activate Login Manager',
        'membership_manager'  =>    'Activate Membership Manager',
        'chat_manager'        =>    'Activate Chat Manager'
    ];

    public function activated(string $key){
        $option = get_option('sparky_plagin');
        return isset($option[$key]) ? $option[$key] : false;
    }
}