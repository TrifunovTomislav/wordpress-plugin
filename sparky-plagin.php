<?php
/**
* @package sparky plagin
*/
/*
Plugin Name: Sparky Plagin
Plugin URI: http://sparky-plagin.com
Description: ovaj plagin je vezba za izradu plagina
Version: 1.0.0
Author: Tomas
Author URI://tomaso.com
License: GPLv3 or later
Text Domain: sparky-plagin
*/
/*
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/
// first thing to do whn making a plugin, protection
defined('ABSPATH') or die('gtfo');

// require the composer autoloader
if(file_exists(dirname(__FILE__) . '/vendor/autoload.php')){
	require_once dirname(__FILE__) . '/vendor/autoload.php';
}

// defining constatnts used in the plugin
define('SPARKY_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
define('SPARKY_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('SPARKY_PLUGIN_NAME', plugin_basename( __FILE__ ));

// use Inc\Base\Activate;
// use Inc\Base\Deactivate;

// activation methods runed on plugin activate
function activateSparkiPlagin(){
	Inc\Base\Activate::activate();
}
register_activation_hook( __FILE__, 'activateSparkiPlagin' );

// deactivation methods runed on plugin deactivate
function deactivateSparkiPlagin(){
	Inc\Base\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivateSparkiPlagin' );

// initializing all the plugin classes
if (class_exists('Inc\\Init')){
	Inc\Init::registerServices();
}
