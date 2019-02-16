<?php
/**
* @package sparky plagin
*/

namespace Inc\Api\Callbacks;

class AdminCallbacks 
{
    public function adminDashboard(){
        require_once SPARKY_PLUGIN_PATH . 'templates/admin.php';
    }

    public function cpt(){
        require_once SPARKY_PLUGIN_PATH . 'templates/cpt.php';
    }

    public function customPostGrapes(){
        require_once SPARKY_PLUGIN_PATH . 'templates/customPostGrapes.php';
    }

    public function larkys(){
        require_once SPARKY_PLUGIN_PATH . 'templates/larkys.php';
    }

    public function taxonomyes(){
        require_once SPARKY_PLUGIN_PATH . 'templates/taxonomyes.php';
    }

    public function widgets(){
        require_once SPARKY_PLUGIN_PATH . 'templates/widgets.php';
    }

    public function gallery(){
        require_once SPARKY_PLUGIN_PATH . 'templates/gallery.php';
    }

    public function testimonials(){
        require_once SPARKY_PLUGIN_PATH . 'templates/testimonials.php';
    }

    public function templates(){
        require_once SPARKY_PLUGIN_PATH . 'templates/templates.php';
    }

    public function login(){
        require_once SPARKY_PLUGIN_PATH . 'templates/login.php';
    }

    public function membership(){
        require_once SPARKY_PLUGIN_PATH . 'templates/membership.php';
    }

    public function chat(){
        require_once SPARKY_PLUGIN_PATH . 'templates/chat.php';
    }


    public function sparkyFirstName(){
        $value = esc_attr(get_option('first_name'));
        echo '<input type="text" class="regular-text" name="first_name" value="' . $value . '" placeholder="write your name">';
    }
}