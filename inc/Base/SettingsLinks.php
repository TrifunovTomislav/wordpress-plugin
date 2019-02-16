<?php
/**
* @package sparky plagin
*/

namespace Inc\Base;

class SettingsLinks 
{
    public function register(){
        add_filter( "plugin_action_links_" . SPARKY_PLUGIN_NAME, array($this, 'settingsLink'));
    }

    // settings link
    public function settingsLink($links){
        //add custom settings link
        $settings_link = '<a href="admin.php?page=sparky_plagin">settings</a>';
        array_push($links, $settings_link);
        return $links;
    }
}