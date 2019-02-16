<?php
/**
* @package sparky plagin
*/

namespace Inc\Base;

class Activate 
{
    public static function activate(){
        flush_rewrite_rules();
        $default = [];

        if(!get_option( 'sparky_plagin' )){
            update_option('sparky_plagin', $default);
        }

        if(!get_option( 'sparky_plagin_cpt' )){
            update_option('sparky_plagin_cpt', $default);
        }

        if(!get_option( 'sparky_plagin_tax' )){
            update_option('sparky_plagin_tax', $default);
        }
    }
}