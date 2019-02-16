<?php
/**
* @package sparky plagin
*/

namespace Inc\Base;

class Enqueue 
{
    public function register(){
        add_action( 'admin_enqueue_scripts', array($this, 'enqueue'));
    }

    function enqueue(){
        //enqueue all our scrips
        wp_enqueue_script('media-upload');
        wp_enqueue_media();
        wp_enqueue_style('sparky-plagin-style', SPARKY_PLUGIN_URL . 'assets/sparkyStyle.css');
        wp_enqueue_script('sparky-plagin-script', SPARKY_PLUGIN_URL . 'assets/sparkyScripts.js');

    }
}