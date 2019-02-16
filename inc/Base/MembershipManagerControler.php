<?php
/**
* @package sparky plagin
*/

namespace Inc\Base;

use Inc\Api\SettingsApi;
use Inc\Base\BaseControler;
use Inc\Api\Callbacks\AdminCallbacks;

class MembershipManagerControler
{
    use BaseControler;
    public $subpages = [];
    public $callbacks;

    public function register(){

        if(!$this->activated('membership_manager')) return;
        
        $this->settings = new SettingsApi();
        $this->callbacks = new AdminCallbacks();
        $this->setSubpages();
        $this->settings->addSubPages($this->subpages)->register();

    }

    public function setSubpages(){
        $this->subpages = [
            [
                'parent_slug' =>  'sparky_plagin',
                'page_title'  =>  'Custom Membership Manager',
                'menu_title'  =>  'Membership Manager',
                'capability'  =>  'manage_options',
                'menu_slug'   =>  'sparky_membership',
                'callback'    =>  array($this->callbacks, 'membership')
            ]
        ];
    }

}

