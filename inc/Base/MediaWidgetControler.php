<?php
/**
* @package sparky plagin
*/

namespace Inc\Base;

use Inc\Api\SettingsApi;
use Inc\Base\BaseControler;
use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Widgets\MediaWidget;

class MediaWidgetControler
{
    use BaseControler;
    public $subpages = [];
    public $callbacks;

    public function register(){

        if(!$this->activated('media_widgets')) return;

        $this->settings = new SettingsApi();
        $this->callbacks = new AdminCallbacks();
        $this->setSubpages();
        $this->settings->addSubPages($this->subpages)->register();

        $media_widget = new MediaWidget();
        $media_widget->register();

    }

    public function setSubpages(){
        $this->subpages = [
            [
                'parent_slug' =>  'sparky_plagin',
                'page_title'  =>  'Custom Widget Manager',
                'menu_title'  =>  'Widget Manager',
                'capability'  =>  'manage_options',
                'menu_slug'   =>  'sparky_widgets',
                'callback'    =>  array($this->callbacks, 'widgets')
            ]
        ];
    }

}

