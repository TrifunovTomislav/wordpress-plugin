<?php
/**
* @package sparky plagin
*/

namespace Inc\Base;

use Inc\Api\SettingsApi;
use Inc\Base\BaseControler;
use Inc\Api\Callbacks\AdminCallbacks;

class LoginManagerControler
{
    use BaseControler;
    public $subpages = [];
    public $callbacks;

    public function register(){

        if(!$this->activated('login_manager')) return;

        $this->settings = new SettingsApi();
        $this->callbacks = new AdminCallbacks();
        $this->setSubpages();
        $this->settings->addSubPages($this->subpages)->register();

        add_action('wp_enqueue_scripts', array($this, 'enqueue'));
        add_action('wp_head', array($this, 'addAuthTemplate'));
        add_action('wp_ajax_nopriv_sparky-login', array($this, 'login'));

    }

    public function enqueue(){

        if(is_user_logged_in()) return;

        wp_enqueue_style('authStyle', SPARKY_PLUGIN_URL . 'assets/auth.css');
        wp_enqueue_script('authScript', SPARKY_PLUGIN_URL . 'assets/auth.js');
    }

    public function addAuthTemplate(){

        if(is_user_logged_in()) return;

        $file = SPARKY_PLUGIN_PATH . 'templates/auth.php';

        if(file_exists($file)){
            load_template($file, true);
        }
    }

    public function login(){
        check_ajax_referer( 'ajax-login-nonce', 'sparky_auth');

        $info = [];
        $info['user_login'] = $_POST['username'];
        $info['user_password'] = $_POST['password'];
        $info['remember'] = true;

        $user_signon = wp_signon($info, true);

        if(is_wp_error($user_signon)){
            echo json_encode([
                'status' => false,
                'message' => 'Wrong username or passowrd'
            ]);
            die();
        }

        echo json_encode([
            'status' => true,
            'message' => 'Login successful, redirecting...'
        ]);
        die();
    }

    public function setSubpages(){
        $this->subpages = [
            [
                'parent_slug' =>  'sparky_plagin',
                'page_title'  =>  'Custom Login Manager',
                'menu_title'  =>  'Login Manager',
                'capability'  =>  'manage_options',
                'menu_slug'   =>  'sparky_login',
                'callback'    =>  array($this->callbacks, 'login')
            ]
        ];
    }

}

