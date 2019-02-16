<?php
/**
* @package sparky plagin
*/

namespace Inc\Api\Callbacks;

use Inc\Base\BaseControler;

class ManagerCallbacks 
{
    use BaseControler;
    public function checkboxSanitize($input){

        $output = array();
		foreach ( $this->managers as $key => $value ) {
			$output[$key] = isset( $input[$key] ) ? true : false;
		}
		return $output;
    }

    public function sparkySectionManager(){
        echo "<h3>Manage the Sections and Features of this Plugin by activating the checkboxes from the folowing list.</h3>";
    }

    public function checkboxField($args){
        $name = $args['label_for'];
		$classes = $args['class'];
		$option_name = $args['option_name'];
        $checkbox = get_option( $option_name );
        $checked = isset($checkbox[$name]) ? ($checkbox[$name] ? true : false) : false;

        echo '<div class="' . $classes . '">
                <input type="checkbox" 
                       id="' . $name . '" 
                       name="' . $option_name . '[' . $name . ']" 
                       value="1" 
                       class="" ' . ( $checked ? 'checked' : '') . '>
                            <label for="' . $name . '">
                            <div></div>
                            </label>
            </div>';
    }
}