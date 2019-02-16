<?php
/**
* @package sparky plagin
*/

namespace Inc\Api\Callbacks;

use Inc\Base\BaseControler;

class TestimonialCallbacks
{
    public function shortcodePage(){
        return require_once SPARKY_PLUGIN_PATH . '/templates/testimonial.php';
    }
}