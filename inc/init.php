<?php 
/**
* @package sparky plagin
*/

namespace Inc;

final class Init 
{
    // store all the class inside an array
    // and return an array full list of classes
    public static function getServices(){
        return [
            Pages\Dashboard::class,
            Base\Enqueue::class,
            Base\SettingsLinks::class,
            Base\CustomPostTypeControler::class,
            Base\TaxonomyManagerControler::class,
            Base\MediaWidgetControler::class,
            Base\GalleryManagerControler::class,
            Base\TestimonialManagerControler::class,
            Base\TemplatesManagerControler::class,
            Base\LoginManagerControler::class,
            Base\MembershipManagerControler::class,
            Base\ChatManagerControler::class
        ];
    }

    // loop thru the classes and call the register 
    // method if it exists
    public static function registerServices(){
        foreach (self::getServices() as $class) {
            $service = self::instantiate($class);
            if(method_exists($service,'register')){
                $service->register();
            }
        }
    }

    // initialize the class
    // param $class from the services array
    // return and instance of that class
    private static function instantiate($class){
        $service = new $class();
        return $service;
    }
}

