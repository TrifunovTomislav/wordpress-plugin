<?php
/**
* @package sparky plagin
*/

namespace Inc\Base;

use Inc\Api\SettingsApi;
use Inc\Base\BaseControler;
use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\TestimonialCallbacks;

class TestimonialManagerControler
{
    use BaseControler;
    public $subpages = [];
    public $callbacks;
    public $settings;
    public $testimonial_callbacks;

    public function register(){

        if(!$this->activated('testimonial_manager')) return;
        $this->settings = new SettingsApi();
        $this->testimonial_callbacks = new TestimonialCallbacks();

        add_action('init', array($this, 'testimonialCPT'));
        add_action('add_meta_boxes', array($this, 'addMetaBoxes'));
        add_action('save_post', array($this, "saveMetaBox"));
        add_action('manage_testimonial_posts_columns', array($this, 'setCustomColumns'));
        add_action('manage_testimonial_posts_custom_column', array($this, 'setCustomColumnsData'), 10, 2);
        add_filter('manage_edit-testimonial_sortable_columns', array($this, 'setCustomColumnsSortable'));

        $this->callbacks = new AdminCallbacks();
        $this->setSubpages();
        $this->settings->addSubPages($this->subpages)->register();
        
        $this->setShortcodePage();
        add_shortcode('testimonial-form', array($this, 'testimonialForm'));
        add_shortcode('testimonial-slideshow', array($this, 'testimonialSlideshow'));
        add_action('wp_ajax_submit_testimonial', array($this, 'submitTestimonial'));
        add_action('wp_ajax_nopriv_submit_testimonial', array($this, 'submitTestimonial'));
    }

    public function submitTestimonial(){

        if(!DOING_AJAX || !check_ajax_referer( 'testimonial-nonce', 'nonce')){
            return $this->returnJson('error');
        }
        
        // sanitize the data
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $message = sanitize_textarea_field($_POST['message']);
        // store the data into testimonial CPT
        $data = [
            'name'     => $name,
            'email'    => $email,
            'approved' => 0,
            'featured' => 0
        ];

        $args = [
            'post_title'    => 'Testimonial from ' . $name,
            'post_content'  => $message,
            'post_author'   => 1,
            'post_status'   => 'publish',
            'post_type'     => 'testimonial',
            'meta_input'    => [
                '_sparky_testimonial_key' => $data
            ]
        ];
        // send response
        $postID = wp_insert_post($args);
        if($postID){
            return $this->returnJson('success');
        }
        
        return $this->returnJson('error');
    }

    public function returnJson($status){
        $return = [
            'status' => $status
        ];
        wp_send_json($return);
        wp_die();
    }

    public function setShortcodePage(){
        $subpage = [
            [
                'parent_slug' => 'edit.php?post_type=testimonial',
                'page_title'  => 'Shortcodes',
                'menu_title'  => 'Shorcodes',
                'capability'  => 'manage_options',
                'menu_slug'   => 'sparky_testimonial_shortcode',
                'callback'     => array($this->testimonial_callbacks, 'shortcodePage')
            ]
        ];
        $this->settings->addSubPages($subpage)->register();
    }

    public function testimonialForm(){
        ob_start();
        echo '<link rel="stylesheet" href="' . SPARKY_PLUGIN_URL . '/assets/form.css' . '" type="text/css" media="all">';
        require_once SPARKY_PLUGIN_PATH . '/templates/contact-form.php';
        echo '<script src="' . SPARKY_PLUGIN_URL . '/assets/form.js' . '"></script>';
        return ob_get_clean();
    }

    public function testimonialSlideshow(){
        ob_start();
        echo '<link rel="stylesheet" href="' . SPARKY_PLUGIN_URL . '/assets/slider.css' . '" type="text/css" media="all">';
        require_once SPARKY_PLUGIN_PATH . '/templates/slider.php';
        echo '<script src="' . SPARKY_PLUGIN_URL . '/assets/slider.js' . '"></script>';
        return ob_get_clean();
    }

    public function testimonialCPT(){

        $labels = [
            'name'          => 'Testimonials',
            'singular_name' => 'Testimonial'
        ];

        $args = [
            'labels'              => $labels,
            'public'              => true,
            'has_archive'         => false,
            'menu_icon'           => 'dashicons-testimonial',
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'supports'            => ['title', 'editor'],
            'show_in_rest'        => true
        ];

        register_post_type('testimonial', $args);
    }

    public function addMetaBoxes(){
        add_meta_box(
            'testimonial_author',
            'Testimonial Options',
            array($this, 'renderFeaturesBox'),
            'testimonial',
            'side',
            'default'
        );
    }

    public function renderFeaturesBox($post){
        wp_nonce_field('sparky_testimonial','sparky_testimonial_nonce');
        $data = get_post_meta($post->ID, '_sparky_testimonial_key', true);
        $name = isset($data['name']) ? $data['name'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $approved = isset($data['approved']) ? $data['approved'] : false;
        $featured = isset($data['featured']) ? $data['featured'] : false;
        ?>
    <p>
        <label class="meta-label" for="sparky_testimonial_author">Author Name</label>
        <input type="text" 
               id="sparky_testimonial_author" 
               name="sparky_testimonial_author"
               class="widefat" 
               value="<?php echo esc_attr($name) ?>">
    </p>
    <p>
        <label class="meta-label" for="sparky_testimonial_email">Author Email</label>
        <input type="email"
               class="widefat" 
               id="sparky_testimonial_email" 
               name="sparky_testimonial_email" 
               value="<?php echo esc_attr($email) ?>">
    </p>
    <div class="meta-container">
        <label class="meta-label w-50 text-left" 
               for="sparky_testimonial_approved">Approved</label>
        <div class="text-right w-50 inline">
            <div class="ui-toggle inline">
                <input type="checkbox" 
                       id="sparky_testimonial_approved" 
                       name="sparky_testimonial_approved" 
                       value="1" <?php echo $approved ? 'checked' : ''; ?>>
                <label for="sparky_testimonial_approved"><div></div></label>
            </div>
        </div>
    </div>
    <div class="meta-container">
        <label class="meta-label w-50 text-left" 
               for="sparky_testimonial_featured">Featured</label>
        <div class="text-right w-50 inline">
            <div class="ui-toggle inline">
                <input type="checkbox" 
                       id="sparky_testimonial_featured" 
                       name="sparky_testimonial_featured" 
                       value="1" <?php echo $featured ? 'checked' : ''; ?>>
                <label for="sparky_testimonial_featured"><div></div></label>
            </div>
        </div>
    </div>
    
        <?php
    }

    public function saveMetaBox($post_id){
        if(!isset($_POST['sparky_testimonial_nonce'])){
            return $post_id;
        }

        $nonce = $_POST['sparky_testimonial_nonce'];
        if(!wp_verify_nonce($nonce, 'sparky_testimonial')){
            return $post_id;
        }

        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
            return $post_id;
        }

        if(!current_user_can( 'edit_post', $post_id )){
            return $post_id;
        }

        $data = [
            'name'     => sanitize_text_field($_POST['sparky_testimonial_author']),
            'email'    => sanitize_email($_POST['sparky_testimonial_email']),
            'approved' => isset($_POST['sparky_testimonial_approved']) ? 1 : 0,
            'featured' => isset($_POST['sparky_testimonial_featured']) ? 1 : 0
        ];
        update_post_meta($post_id, '_sparky_testimonial_key', $data);
    }

    public function setCustomColumns($columns){
        $title = $columns['title'];
        $date = $columns['date'];
        unset($columns['title'], $columns['date']);
        $columns['name'] = 'Author Name';
        $columns['title'] = $title;
        $columns['approved'] = 'Approved';
        $columns['featured'] = 'Featured';
        $columns['date'] = $date;

        return $columns;
    }

    public function setCustomColumnsData($column, $post_id){
        $data = get_post_meta($post_id, '_sparky_testimonial_key', true);
        $name = isset($data['name']) ? $data['name'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $approved = isset($data['approved']) && $data['approved'] === 1 ? '<strong>YES</strong>' : 'NO';
        $featured = isset($data['featured']) && $data['featured'] === 1 ? '<strong>YES</strong>' : 'NO';
        
        switch($column){
            case 'name': echo '<strong>' . $name . '</strong></br><a href="mailto:' . $email . '">' . $email . '</a>';
            break;
            case 'approved': echo $approved;
            break;
            case 'featured': echo $featured;
            break;
        }
    }

    public function setCustomColumnsSortable($columns){
            $columns['name'] = 'name';
            $columns['approved'] = 'approved';
            $columns['featured'] = 'featured';
            return $columns;
        }

    public function setSubpages(){
        $this->subpages = [
            [
                'parent_slug' =>  'sparky_plagin',
                'page_title'  =>  'Custom Testimonials Manager',
                'menu_title'  =>  'Testimonials Manager',
                'capability'  =>  'manage_options',
                'menu_slug'   =>  'sparky_testimonials',
                'callback'    =>  array($this->callbacks, 'testimonials')
            ]
        ];
    }

}

