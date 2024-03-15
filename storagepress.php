<?php
/*
Plugin Name: StoragePress
Plugin URI: https://jacob-t-graham.com
Description: StoragePress is a plugin to manage your self-storage business.
Version: 1.0.0
Author: Jacob Graham
Author URI: https://jacob-t-graham.com
Text Domain: storagepress
*/

require_once plugin_dir_path(__FILE__) . '_jg_wp_plugin_kit/JGWPPlugin.php';

class StoragePress extends JGWPPlugin{

    // constructor
    public function __construct(){
        //set the plugin prefix before calling super constructor
        $this->plugin_prefix = "storagepress_";
        $this->base_dir = plugin_dir_path(__FILE__);

        $this->settings_groups = [
            new JGWPSettingsGroup($this, 'storagepress_settings_section', 'StoragePress Settings', 'storagepress_settings_page', function(){
                echo 'Configure settings for your self-storage business.';
            })
        ];
        
        $this->settings = [
            new JGWPSetting($this, 'name', array('default' => "", 'sanitize_callback' => 'sanitize_text_field'), 'storagepress_settings_page', 'Business Name', 'storagepress_settings_section'),
            new JGWPSetting($this, 'address', array('default' => "", 'sanitize_callback' => 'sanitize_text_field'), 'storagepress_settings_page', 'Business Address', 'storagepress_settings_section'),
            new JGWPSetting($this, 'email', array('default' => "", 'sanitize_callback' => 'sanitize_text_field'), 'storagepress_settings_page', 'Business Email', 'storagepress_settings_section'),
            new JGWPSetting($this, 'phone', array('default' => "", 'sanitize_callback' => 'sanitize_text_field'), 'storagepress_settings_page', 'Business Phone', 'storagepress_settings_section'),
            new JGWPSetting($this, 'rental_terms', array('default' => "", 'sanitize_callback' => 'sanitize_text_field'), 'storagepress_settings_page', 'Rental Terms', 'storagepress_settings_section'),
            new JGWPSetting($this, 'checks_payable_to', array('default' => "", 'sanitize_callback' => 'sanitize_text_field'), 'storagepress_settings_page', 'Checks Payable To:', 'storagepress_settings_section'),
            new JGWPSetting($this, 'feature_options', array('default' => array(), 'sanitize_callback' => function($input){ 
                foreach($input as $key => $value){
                    $input[$key] = sanitize_text_field($value);
                }
                return $input;
             }), 'storagepress_settings_page', 'Storage Unit Features:', 'storagepress_settings_section')
             
            ];

        parent::__construct();   //call parent constructor
        //defer alpine js to mitigate warning
        add_filter('script_loader_tag', array($this, 'defer_alpinejs'), 10, 3);   //add defer to alpinejs script

        //create pages for managing storage units
        add_action('admin_menu', array($this, 'storagepress_setup_menu'));


        //register storage units post type
        add_action('init', array($this, 'register_storage_units_post_type'));

        //set cols that appear in storage unit listing
        add_filter('manage_storage_units_posts_columns', array($this, 'storage_units_columns'));
        add_action('manage_storage_units_posts_custom_column', array($this, 'storage_units_custom_column'), 10, 2);

        //add inputs to storage unit create form
        add_action('edit_form_after_editor', array($this, 'add_inputs_to_storage_unit_create_form'));
        add_filter( 'enter_title_here', array($this, 'change_title_label') );   //change title field placeholder

        //save the custom fields of the storage units when the unit is saved
        add_action( 'save_post', array($this, 'save_storage_unit_custom_fields'));
        
    }

    //enqueue admin scripts and styles
    public function admin_resources($hook){
        global $post;   //get the post, if set

        //add settings styling if on storage unit settings page
        if(($post && 'sp_storage_units' === $post->post_type || (('post.php' === $hook || 'post-new.php' === $hook || 'edit.php' === $hook) && (isset($_GET['post_type']) && 'storage_unit' === $_GET['post_type']))) 
        || (isset($_GET['page']) && 'storagepress_settings_page' === $_GET['page'])){
            //enqueue styles
            wp_enqueue_style('storagepress_settings_style', plugin_dir_url(__FILE__) . 'assets/css/settings.css', array(), true);

            //enqueue scripts
            wp_enqueue_script('storagepress_alpinejs', plugin_dir_url(__FILE__) . 'assets/js/alpine.min.js', array(), true);
        }
    }

    //enqueue front-end scripts and styles
    public function front_end_resources($hook){
        //enqueue styles
        //TODO
    }

    //add defer to the alpine js script
    public function defer_alpinejs($tag, $handle, $src){
        if('storagepress_alpinejs' === $handle){
            $tag = str_replace(' src', ' defer src', $tag);
        }
        return $tag;
    }
    
    // add menu page for managing storage units
    public function storagepress_setup_menu(){
        /**
         * Add the storagepress page to the admin menu
         *
         * @param string   $page_title    The text to be displayed in the title tags of the page when the menu is selected.
         * @param string   $menu_title    The text to be used for the menu.
         * @param string   $capability    The capability required for this menu to be displayed to the user.
         * @param string   $menu_slug     The slug name to refer to this menu by (should be unique for this menu).
         * @param callable $function      The function to be called to output the content for this page.
         * @param string   $icon_url      The URL to the icon to be used for this menu.
         * @param int      $position      The position in the menu order this one should appear.
         *                                (0 - at the top, 5 - below Posts, 10 - below Media, 15 - below Links, 20 - below Pages, etc.)
         */
        add_menu_page(
            'StoragePress', // $page_title
            'StoragePress', // $menu_title
            'manage_options', // $capability
            'storagepress', // $menu_slug
            function(){
                echo "";    //(content added when the custom post type is registered)
            }, // $function
            'dashicons-vault' // $icon_url
        );

        //add a settings submenu
        add_submenu_page(
            'storagepress', // $parent_slug
            'StoragePress Settings', // $page_title
            'Settings', // $menu_title
            'manage_options', // $capability
            'storagepress_settings_page', // $menu_slug
            function(){
                require_once plugin_dir_path(__FILE__) . 'elements/storagepress_settings_page.php';
            } // $function
        );
    }

    // create custom post type to represent storage units
    public function register_storage_units_post_type() {
        //register storage unit post ype
        $args = array(
            'public' => true,
            'label'  => 'Storage Units',
            'show_in_menu' => 'storagepress',
            'supports' => array('title', 'thumbnail'),
            'labels' => array(
                'name' => 'Storage Units',
                'singular_name' => 'Storage Unit',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Storage Unit',
                'edit_item' => 'Edit Storage Unit',
                'new_item' => 'New Storage Unit',
                'view_item' => 'View Storage Unit',
                'view_items' => 'View Storage Units',
                'search_items' => 'Search Storage Units',
                'not_found' => 'No storage units found.',
                'not_found_in_trash' => 'No storage units found in Trash.',
                'parent_item_colon' => 'Parent Storage Unit:',
                'all_items' => 'All Storage Units',
                'archives' => 'Storage Unit Archives',
                'attributes' => 'Storage Unit Attributes',
                'insert_into_item' => 'Insert into storage unit',
                'uploaded_to_this_item' => 'Uploaded to this storage unit',
                'featured_image' => 'Featured Image',
                'set_featured_image' => 'Set featured image',
                'remove_featured_image' => 'Remove featured image',
                'use_featured_image' => 'Use as featured image',
                'filter_items_list' => 'Filter storage units list',
                'items_list_navigation' => 'Storage units list navigation',
                'items_list' => 'Storage units list',
                'item_published' => 'Storage unit published.',
                'item_published_privately' => 'Storage unit published privately.',
                'item_reverted_to_draft' => 'Storage unit reverted to draft.',
                'item_scheduled' => 'Storage unit scheduled.',
                'item_updated' => 'Storage unit updated.',
            )
        );
        register_post_type('sp_storage_units', $args);

        //add attributes to storage unit post type
        register_meta('sp_storage_units', 'sp_length', array(  //length
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
        ));
        register_meta('sp_storage_units', 'sp_length', array(  //width
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
        ));
        register_meta('sp_storage_units', 'sp_unit', array(  //unit
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
        ));
        register_meta('sp_storage_units', 'sp_features', array(  //type
            'show_in_rest' => true,
            'single' => false,
            'type' => 'string',
        ));
        register_meta('sp_storage_units', 'sp_price', array(  //price
            'show_in_rest' => true,
            'single' => true,
            'type' => 'number',
        ));
        register_meta('sp_storage_units', 'sp_tenant', array(  //status
            'show_in_rest' => true,
            'single' => true,
            'type' => 'int',
        ));
        register_meta('sp_storage_units', 'sp_last_rental_date', array(  //date of last rental
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
        ));
        register_meta('sp_storage_units', 'sp_last_vacant_date', array(  //date of last payment
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
        ));
    }

    //change the label of the title field for storage units
    public function change_title_label($title){
        $screen = get_current_screen();
        if('sp_storage_units' == $screen->post_type){
            $title = 'Enter storage unit label...';
        }
        return $title;
    }

    // add inputs to storage unit create form
    public function add_inputs_to_storage_unit_create_form(){
        require_once plugin_dir_path(__FILE__) . 'elements/storage_unit_meta_inputs.php';
    }

    //save custom fields for storage units
    function save_storage_unit_custom_fields($post_id){
        if(isset($_POST['post_type']) && $_POST['post_type'] == 'sp_storage_units'){
            // save length
            if(isset($_POST['sp_length'])){
                $length = floatval($_POST['sp_length']);
                update_post_meta($post_id, 'sp_length', $length);
            }
            //save width
            if(isset($_POST['sp_width'])){
                $width = floatval($_POST['sp_width']);
                update_post_meta($post_id, 'sp_width', $width);
            }
            // save unit
            if(isset($_POST['sp_unit'])){
                $unit = sanitize_text_field($_POST['sp_unit']);
                update_post_meta($post_id, 'sp_unit', $unit);
            }

            // get features from request, and save them
            if(isset($_POST['sp_features'])){
                $sp_features = array_map('sanitize_text_field', $_POST['sp_features']);
                update_post_meta($post_id, 'sp_features', $sp_features, false);
            }

            // save price
            if(isset($_POST['sp_price'])){
                $price = floatval($_POST['sp_price']) * 100;
                update_post_meta($post_id, 'sp_price', $price);
            }

            // save tenant
            if(isset($_POST['sp_tenant'])){
                $tenant = intval($_POST['sp_tenant']);
                update_post_meta($post_id, 'sp_tenant', $tenant);
            }

            // save last rental date
            if(isset($_POST['sp_last_rental_date'])){
                $last_rental_date = sanitize_text_field($_POST['sp_last_rental_date']);
                update_post_meta($post_id, 'sp_last_rental_date', $last_rental_date);
            }

            // save last payment date
            if(isset($_POST['sp_last_vacant_date'])){
                $last_payment_date = sanitize_text_field($_POST['sp_last_vacant_date']);
                update_post_meta($post_id, 'sp_last_vacant_date', $last_payment_date);
            }
        }
    }

    // add fields to listing of storage units table
    public function storage_units_columns($columns) {
        unset($columns['date']); // remove date column
        $columns['size'] = 'Size'; // add custom field column
        $columns['price'] = 'Price'; // add custom field column
        $columns['status'] = 'Status'; // add custom field column
        return $columns;
    }

    // populate custom fields columns in storage units table
    public function storage_units_custom_column($column, $post_id) {
        switch ($column) {
            case 'price':
                // get the custom field value and echo it
                $custom_field_value = get_post_meta($post_id, 'price', true);
                echo $custom_field_value != "" ? $custom_field_value : "N/A";
                break;
            case 'size':
                // get the custom field value and echo it
                $custom_field_value = get_post_meta($post_id, 'size', true);
                echo $custom_field_value != "" ? $custom_field_value : "N/A";
                break;
            case 'status':
                // get the custom field value and echo it
                $custom_field_value = get_post_meta($post_id, 'status', true);
                echo $custom_field_value != "" ? $custom_field_value : "N/A";
                break;
        }
    }

    //initialize plugin settings
    public function __init_settings(){
        // create section for settings
        add_settings_section(
            'storagepress_settings_section',        //id
            'StoragePress Settings',        //title
            function(){                     //callback
                echo 'Configure settings for your self-storage business.';
            },
            'storagepress_settings_page'         //page to appear on
        );

        // create the settings fields
        add_settings_field(
            'storagepress_name_field',  //id
            'Business Name',       //title
            function(){
                require_once plugin_dir_path(__FILE__) . 'elements/settings/name_field.php';
            },  //callback
            'storagepress_settings_page',     //page
            'storagepress_settings_section'  //section id to appear on (optional)
        );
        add_settings_field(
            'storagepress_address_field',  //id
            'Business Address',       //title
            function(){
                require_once plugin_dir_path(__FILE__) . 'elements/settings/address_field.php';
            },  //callback
            'storagepress_settings_page',     //page
            'storagepress_settings_section'  //section id to appear on (optional)
        );
        add_settings_field(
            'storagepress_email_field',  //id
            'Business Email',       //title
            function(){
                require_once plugin_dir_path(__FILE__) . 'elements/settings/email_field.php';
            },  //callback
            'storagepress_settings_page',     //page
            'storagepress_settings_section'  //section id to appear on (optional)
        );
        add_settings_field(
            'storagepress_phone_field',  //id
            'Business Phone',       //title
            function(){
                require_once plugin_dir_path(__FILE__) . 'elements/settings/phone_field.php';
            },  //callback
            'storagepress_settings_page',     //page
            'storagepress_settings_section'  //section id to appear on (optional)
        );
        add_settings_field(
            'storagepress_rental_terms_field',  //id
            'Rental Terms',       //title
            function(){
                require_once plugin_dir_path(__FILE__) . 'elements/settings/rental_terms_field.php';
            },  //callback
            'storagepress_settings_page',     //page
            'storagepress_settings_section'  //section id to appear on (optional)
        );
        add_settings_field(
            'storagepress_checks_payable_to_field',  //id
            'Checks Payable To:',       //title
            function(){
                require_once plugin_dir_path(__FILE__) . 'elements/settings/storagepress_checks_payable_to_field.php';
            },  //callback
            'storagepress_settings_page',     //page
            'storagepress_settings_section'  //section id to appear on (optional)
        );
        add_settings_field(
            'storagepress_features_field',  //id
            'Storage Unit Features:',       //title
            function(){
                require_once plugin_dir_path(__FILE__) . 'elements/settings/feature_option_field.php';
            },  //callback
            'storagepress_settings_page',     //page
            'storagepress_settings_section'  //section id to appear on (optional)
        );



        // create the settings themselves
        register_setting(
            'storagepress_settings_group',    //option group
            'storagepress_name',    //option name
            array(                    //args
                'default' => "", //default value
                'sanitize_callback' => 'sanitize_text_field' //sanitize callback
            )
        );
        register_setting(
            'storagepress_settings_group',    //option group
            'storagepress_address',    //option name
            array(                    //args
                'default' => "", //default value
                'sanitize_callback' => 'sanitize_text_field' //sanitize callback
            )
        );
        register_setting(
            'storagepress_settings_group',    //option group
            'storagepress_email',    //option name
            array(                    //args
                'default' => "", //default value
                'sanitize_callback' => 'sanitize_text_field' //sanitize callback
            )
        );
        register_setting(
            'storagepress_settings_group',    //option group
            'storagepress_phone',    //option name
            array(                    //args
                'default' => "", //default value
                'sanitize_callback' => 'sanitize_text_field' //sanitize callback
            )
        );
        register_setting(
            'storagepress_settings_group',    //option group
            'storagepress_rental_terms',    //option name
            array(                    //args
                'default' => "", //default value
                'sanitize_callback' => 'sanitize_text_field' //sanitize callback
            )
        );
        register_setting(
            'storagepress_settings_group',    //option group
            'storagepress_checks_payable_to',    //option name
            array(                    //args
                'default' => "", //default value
                'sanitize_callback' => 'sanitize_text_field' //sanitize callback
            )
        );
        register_setting(
            'storagepress_settings_group',    //option group
            'storagepress_feature_options',    //option name
            array(                    //args
                'default' => array(), //default value
                'sanitize_callback' => function($input){ 
                    foreach($input as $key => $value){
                        $input[$key] = sanitize_text_field($value);
                    }
                    return $input;
                 } //sanitize callback
            )
        );
    }
}



$plugin = new StoragePress();