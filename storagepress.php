<?php
/*
Plugin Name: StoragePress
Plugin URI: https://jacob-t-graham.com/storagepress/
Description: StoragePress is a plugin to manage your self-storage business.
Version: 1.0.0
Author: Jacob Graham
Author URI: https://jacob-t-graham.com
Text Domain: storagepress
License: GPL v3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/

//
///NOTE: there may be an issue with getting 404 errors when registering the storage units post type, a quick fix is to visit the settings->permalinks page, and click save changes
///NOTE: but I may need a better solution in the future.
//
//exit if file is called directly
if (!defined('ABSPATH')) {
    exit;
}
//init global post variable

require_once plugin_dir_path(__FILE__) . 'jg_wp_plugin_kit/JGWPPlugin.php';

class StoragePress extends Storagepress_JGWPPlugin{

    // constructor, set values to pass to parent constructor
    public function __construct(){
        //set the plugin prefix before calling super constructor
        $plugin_prefix = "storagepress_";
        $base_dir = plugin_dir_path(__FILE__);
        $base_url = plugin_dir_url(__FILE__);
        $settings_groups = [
            new Storagepress_JGWPSettingsGroup($this, 'storagepress_settings_section', 'StoragePress Settings', 'storagepress_settings_page', function(){
                echo 'Configure settings for your self-storage business.';
            })
        ];
        $settings = [
            new Storagepress_JGWPSetting($this, 'name', array('default' => "", 'sanitize_callback' => 'sanitize_text_field'), 'storagepress_settings_page', 'Business Name', 'storagepress_settings_section'),
            new Storagepress_JGWPSetting($this, 'address', array('default' => "", 'sanitize_callback' => 'sanitize_text_field'), 'storagepress_settings_page', 'Business Address', 'storagepress_settings_section'),
            new Storagepress_JGWPSetting($this, 'email', array('default' => "", 'sanitize_callback' => 'sanitize_text_field'), 'storagepress_settings_page', 'Business Email', 'storagepress_settings_section'),
            new Storagepress_JGWPSetting($this, 'phone', array('default' => "", 'sanitize_callback' => 'sanitize_text_field'), 'storagepress_settings_page', 'Business Phone', 'storagepress_settings_section'),
            new Storagepress_JGWPSetting($this, 'rental_terms', array('default' => "", 'sanitize_callback' => 'sanitize_text_field'), 'storagepress_settings_page', 'Rental Terms', 'storagepress_settings_section'),
            new Storagepress_JGWPSetting($this, 'checks_payable_to', array('default' => "", 'sanitize_callback' => 'sanitize_text_field'), 'storagepress_settings_page', 'Checks Payable To:', 'storagepress_settings_section'),
            new Storagepress_JGWPSetting($this, 'listing_page', array('default' => "", 'sanitize_callback' => 'sanitize_text_field'), 'storagepress_settings_page', 'Unit Listing Page:', 'storagepress_settings_section'),
            new Storagepress_JGWPSetting($this, 'feature_options', array('default' => array(), 'sanitize_callback' => function($input){ 
                if (isset($input) && is_array($input)){
                    foreach($input as $key => $value){
                        $input[$key] = sanitize_text_field($value);
                    }
                    return $input;
                }
                return [];
             }), 'storagepress_settings_page', 'Storage Unit Features:', 'storagepress_settings_section'),
            new Storagepress_JGWPSetting($this, 'default_thumbnail_id', array('default' => null, 'sanitize_callback' => function($input){
                $value = absint($input);
                return $value > 0 ? $value : null;
            }), null, 'Default Thumbnail ID', null),
            new Storagepress_JGWPSetting($this, 'display_credit_link', array('default' => "false", 'sanitize_callback' => 'sanitize_text_field'), 'storagepress_settings_page', 'Display Credit Link:', 'storagepress_settings_section'),
        ];

        $admin_resources = [
            new storagepress_JGWPResource($this, 'alpine.min.js'),
            new storagepress_JGWPResource($this, 'settings.css'),
            new storagepress_JGWPResource($this, 'reservation_inquiries.css'),
            new storagepress_JGWPResource($this, 'feature_options_field.css'),
            new storagepress_JGWPResource($this, 'storage_unit_meta_inputs.css'),
        ];

        //initialize the plugin
        parent::__construct([
            'plugin_prefix'=>$plugin_prefix, 
            'base_dir'=> $base_dir,
            'base_url'=> $base_url,
            'settings_groups'=>$settings_groups,
            'settings' => $settings,
            'admin_resources' => $admin_resources,
            'front_end_resources' => []
        ]);   //call parent 
        
    }

    // implement custom behavior here
    protected function plugin(){
        //defer alpine js to mitigate warning
        add_filter('script_loader_tag', array($this, 'defer_alpinejs'), 10, 3);   //add defer to alpinejs script

        //create pages for managing storage units
        add_action('admin_menu', array($this, 'storagepress_setup_menu'));


        //register storage units post type
        add_action('init', array($this, 'register_storage_units_post_type'));
        add_action('admin_menu', array($this, 'remove_unit_meta_metabox')); //remove the metabox for settings post meta fields
        add_filter('default_post_metadata', array($this, 'set_unit_default_thumbnail'), 10, 4);
        add_action('init', array($this, 'create_unit_default_thumbnail'));  //create the upload for the default unit thumbnail
        //add inputs to the quick edit menu, and save results from them
        //NOTE: this is actually a more complex feature than I thought, because the quick edit form is created by cloning an element using js on the client side,
        //NOTE: so this leads to displaying incorrect default values in the quick edit form
        // add_action('quick_edit_custom_box', array($this, 'display_quick_edit_custom'), 10, 2);    //add inputs
        // add_action('save_post', array($this, 'save_quick_edit_data'));              //save values

        //set cols that appear in storage unit listing
        add_filter('manage_posts_columns', array($this, 'storage_units_columns'));
        add_action('manage_posts_custom_column', array($this, 'storage_units_custom_column'), 10, 2);

        //add inputs to storage unit create form
        add_action('edit_form_after_editor', array($this, 'add_inputs_to_storage_unit_create_form'));
        add_filter( 'enter_title_here', array($this, 'change_title_label') );   //change title field placeholder

        //save the custom fields of the storage units when the unit is saved
        add_action( 'save_post', array($this, 'save_storage_unit_custom_fields'));

        //register templates for storage units frontend display
        add_filter('single_template', array($this, 'register_single_template'));
        add_filter('archive_template', array($this, 'register_archive_template'));

        //register custom blocks
        add_action('init', array($this, 'register_custom_blocks'));

        //create page for the storage unit listing
        add_action('init', array($this, 'create_storage_unit_listing_page'));

        //rest api routes for getting business details
        add_action('rest_api_init', array($this, 'register_rest_routes'));

        //rest route to reserve a unit
        add_action('rest_api_init', array($this, 'register_reserve_unit_route'));

        //let users register for accounts
        add_action('init', array($this, 'allow_registration'));

        //enqueue api script on the unit listing page
        add_action('wp_enqueue_scripts', array($this, 'enqueue_reserve_unit_scripts_on_listing_page'));

        //add inquiries list admin page
        add_action('admin_menu', array($this, 'add_inquiries_list_page'));
        add_action('admin_notices', array($this, 'show_reservation_notices'));

        //register script to create the storagepress window object
        add_action('wp_enqueue_scripts', array($this, 'register_storagepress_window_object'));

    }

    //register script to create the storagepress window object
    public function register_storagepress_window_object(){
        wp_enqueue_script('storagepress_window_object', $this->get_base_url() . 'resources/js/storagepress_global.js', array(), null, true);
        wp_localize_script('storagepress_window_object', 'php_args', array(
            'reserve_unit_rest_route' => rest_url('storagepress/v1/reserve-unit')
        ));
    }

    //show notices for reservation inquiries
    public function show_reservation_notices(){

        //verify the nonce
        if (isset($_POST['approve']) || isset($_POST['deny'])) {
            if (!isset($_POST['approve_deny_reservation_inquiry_nonce']) || !wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['approve_deny_reservation_inquiry_nonce'] ) ), 'approve_deny_reservation_inquiry')) {
                http_response_code(403);
                die('Invalid nonce.');
            }
        }

        //show the admin notices
        if (isset($_POST['approve'])){
            ?>
            <div class="notice notice-success is-dismissible">
                <p>Reservation Inquiry Approved!</p>
            </div>
            <?php
        } else if (isset($_POST['deny'])){
            ?>
            <div class="notice notice-error is-dismissible">
                <p>Reservation Inquiry Denied!</p>
            </div>
            <?php
        }
    }

    //add inquiries list admin page
    public function add_inquiries_list_page(){
        add_submenu_page(
            'storagepress', // $parent_slug
            'Reservation Inquiries', // $page_title
            'Reservation Inquiries', // $menu_title
            'manage_options', // $capability
            'storagepress_reservation_inquiries_page', // $menu_slug
            function(){
                require_once plugin_dir_path(__FILE__) . 'elements/storagepress_reservation_inquiries_page.php';
            } // $function
        );
    }

    //enqueue api script on the unit listing page
    public function enqueue_reserve_unit_scripts_on_listing_page(){
        //see if there is currently a listing page set
        $listing_page_id = get_option( 'storagepress_listing_page', null);
        $listing_page = get_post($listing_page_id);

        //if no listing page is set...
        if ($listing_page){
            wp_enqueue_script('wp-api');
            //wp_enqueue_script( 'storagepress_reserve_units', $this->get_base_url() . 'resources/js/reserve_unit_page.js');
        }
    }

    //register rest route to reserve a unit
    public function register_reserve_unit_route(){
        register_rest_route('storagepress/v1', '/reserve-unit', array(
            'methods' => 'POST',
            'permission_callback' => function(){
                return is_user_logged_in();
            },
            'callback' => function($request){
                //get the storage unit based on id
                $unit_id = $request->get_param('unit_id');
                $unit = get_post($unit_id);
                if (!$unit || $unit->post_type != 'storagepress_unit'){
                    return new WP_Error('invalid_unit_id', 'Invalid unit id', array('status' => 404));
                }

                //ensure this user has no other active inquiries
                $inquiries = get_posts(array(
                    'post_type' => 'storagepress_unit',
                    'meta_key' => 'stpr_reservation_inquirer',
                    'meta_value' => get_current_user_id()
                ));
                if (count($inquiries) > 0){
                    return new WP_Error('user_has_active_inquiries', 'You already have an active inquiry', array('status' => 400));
                }

                //see if the unit is already rented
                if (get_post_meta($unit_id, "stpr_tenant", true)){
                    return new WP_Error('unit_already_rented', 'This unit is already rented', array('status' => 400));
                }

                //set the inquirer to the current user
                if (get_post_meta($unit_id, "stpr_reservation_inquirer", true)){
                    return new WP_Error('unit_already_reserved', 'This unit has already been reserved', array('status' => 400));
                }
                $inquirer = wp_get_current_user();
                update_post_meta( $unit_id, "stpr_reservation_inquirer", $inquirer->ID);
                update_post_meta( $unit_id, "stpr_last_rental_date", date('Y-m-d H:i:s'));

                //send an email to the business owner
                $business_email = get_option('storagepress_email');
                if ($business_email){
                    $subject = 'Reservation Inquiry for ' . $unit->post_title;
                    $message = 'A reservation inquiry has been made for the storage unit ' . $unit->post_title . ' by ' . $request->get_param('name') . ' (' . $request->get_param('email') . ')';
                    wp_mail($business_email, $subject, $message);
                }

                //return successful redirect
                return rest_ensure_response(array('message' => 'Reservation inquiry successful'));

            }
        ));
    }

    //register rest api routes
    public function register_rest_routes(){
        register_rest_route('storagepress/v1', '/business-details', array(
            'methods' => 'GET',
            'permission_callback' => '__return_true', // this line was added to allow anyone to access the endpoint
            'callback' => function(){
                $business = array(
                    'storagepress_name' => get_option('storagepress_name', ''),
                    'storagepress_address' => get_option('storagepress_address', ''),
                    'storagepress_email' => get_option('storagepress_email', ''),
                    'storagepress_phone' => get_option('storagepress_phone', ''),
                    'storagepress_rental_terms' => get_option('storagepress_rental_terms', ''),
                    'storagepress_checks_payable_to' => get_option('storagepress_checks_payable_to', ''),
                    'storagepress_features' => get_option('storagepress_feature_options', array()),
                    'storagepress_listing_page' => get_option('storagepress_listing_page', null)
                );
                return $business;
            }
        ));
    }

    public function create_storage_unit_listing_page(){
        //see if there is currently a listing page set
        $listing_page_id = get_option( 'storagepress_listing_page', null);
        $listing_page = get_post($listing_page_id);

        //if no listing page is set...
        if (!$listing_page || $listing_page->post_type != 'page'){
            //create the page
            $page = array(
                'post_title' => 'Storage Units',
                'post_content' => file_get_contents($this->get_base_dir() . 'elements/storagepress_default_listing_page.php'),
                'post_status' => 'publish',
                'post_type' => 'page',
            );
            $page_id = wp_insert_post($page);

            //update the option to create the page
            if ($page_id && !is_wp_error($page_id)) {
                // Update the option with the ID of the new page
                update_option('storagepress_listing_page', $page_id);
            }
        }
    }

    //register custom blocks
    public function register_custom_blocks(){
        register_block_type($this->get_base_dir() . '/blocks/storage-unit-business-detail-block/build');
        register_block_type($this->get_base_dir() . '/blocks/storage-unit-meta-block/build');
        register_block_type($this->get_base_dir() . '/blocks/storage-unit-reserve-block/build');
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
            'supports' => array('title', 'thumbnail', 'custom-fields'),
            'has_archive' => true,
            'show_in_rest' => true,
            'public' => true,
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
                'featured_image' => 'Unit Image',
                'set_featured_image' => 'Set unit image',
                'remove_featured_image' => 'Remove unit image',
                'use_featured_image' => 'Use as unit image',
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
        register_post_type('storagepress_unit', $args);

        //add attributes to storage unit post type
        register_post_meta('storagepress_unit', 'stpr_length', array(  //length
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
            'object_subtype' => 'storagepress_unit'
        ));
        register_post_meta('storagepress_unit', 'stpr_width', array(  //width
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
            'object_subtype' => 'storagepress_unit'
        ));
        register_post_meta('storagepress_unit', 'stpr_unit', array(  //unit
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
            'object_subtype' => 'storagepress_unit'
        ));
        register_post_meta('storagepress_unit', 'stpr_features', array(  //type
            'show_in_rest' => array(
                'schema' => array(  //schema for the rest api
                    'type' => 'array',
                    'items' => array(
                        'type' => 'string', // whatever type each array entry should be
                    ),
                )
            ),
            'single' => true,
            'type' => 'array',
            'object_subtype' => 'storagepress_unit'
        ));
        register_post_meta('storagepress_unit', 'stpr_price', array(  //price
            'show_in_rest' => true,
            'single' => true,
            'type' => 'number',
            'object_subtype' => 'storagepress_unit'
        ));
        register_post_meta('storagepress_unit', 'stpr_tenant', array(  //status
            'show_in_rest' => true,
            'single' => true,
            'type' => 'int',
            'object_subtype' => 'storagepress_unit'
        ));
        register_post_meta('storagepress_unit', 'stpr_last_rental_date', array(  //date the last rental began
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
            'object_subtype' => 'storagepress_unit'
        ));
        register_post_meta('storagepress_unit', 'stpr_last_vacant_date', array(  //date the last vacancy began
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
            'object_subtype' => 'storagepress_unit'
        ));
        register_post_meta('storagepress_unit', 'stpr_reservation_inquirer', array(  //date of last payment
            'show_in_rest' => true,
            'single' => true,
            'type' => 'int',
            'object_subtype' => 'storagepress_unit'
        ));
    }

    //remove the metabox for settings post meta fields
    public function remove_unit_meta_metabox(){
        remove_meta_box('postcustom', 'storagepress_unit', 'normal');    //id (in html), post type, context
    }

    public function create_unit_default_thumbnail(){
        //exit if default thumbnail already exists
        $atttachment_id = get_option('storagepress_default_thumbnail_id', null);
        if ($atttachment_id && wp_get_attachment_url($atttachment_id)){
            return;
        }

        // The path to the file you want to upload
        $file_path = $this->get_base_dir() . 'resources/images/default_thumbnail.png';

         // The contents of the file
        $file_contents = file_get_contents($file_path);

        // The name of the file
        $file_name = basename($file_path);

        // Upload the file to the WordPress uploads directory
        $upload = wp_upload_bits($file_name, null, $file_contents);

        if (isset($upload) && !$upload['error']) {
            //get data from the upload
            $file = $upload['file'];
            $url = $upload['url'];
            $type = $upload['type'];
            
            //insert the file into the media library
            $attachment = array(
                'post_mime_type' => $type,
                'post_title' => sanitize_file_name(preg_replace('/\.[^.]+$/', '', basename($file_name))),
                'post_content' => '',
                'post_status' => 'inherit',
                'guid' => $url
            );
            $attach_id = wp_insert_attachment($attachment, $file);

            //set the default thumbnail id
            if ($attach_id){
                update_option('storagepress_default_thumbnail_id', $attach_id);
            }
        } else{
            //log the error
            error_log('Error uploading default thumbnail: ' . $upload['error']);
        }
    }

    //set default thumbnail for storage units
    public function set_unit_default_thumbnail($value, $post_id, $meta_key, $single){
        if (get_post_type($post_id) == 'storagepress_unit' && $meta_key == '_thumbnail_id' && !$value){
            return get_option('storagepress_default_thumbnail_id', null);
        }
        return $value;
    }


    //change the label of the title field for storage units
    public function change_title_label($title){
        $screen = get_current_screen();
        if('storagepress_unit' == $screen->post_type){
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
        //ensure this only runs when a storage unit is being saved
        if (get_post_type($post_id) != 'storagepress_unit') return;
        //verify the nonce
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && ( !isset($_POST['storagepress_unit_meta_fields_nonce_field']) || !wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['storagepress_unit_meta_fields_nonce_field'] ) ), 'storagepress_unit_meta_nonce') )) {
            http_response_code(403);
            die('Invalid nonce!!!');
        }

        //ensure the user has permission to edit the post
        if (!current_user_can('edit_post', $post_id)) {
            http_response_code(403);
            die('You do not have permission to edit this post.');
        }

        //save the fields
        if(isset($_POST['post_type']) && $_POST['post_type'] == 'storagepress_unit'){
            // save length
            if(isset($_POST['stpr_length'])){
                $length = floatval($_POST['stpr_length']);
                update_post_meta($post_id, 'stpr_length', $length);
            }
            //save width
            if(isset($_POST['stpr_width'])){
                $width = floatval($_POST['stpr_width']);
                update_post_meta($post_id, 'stpr_width', $width);
            }
            // save unit
            if(isset($_POST['stpr_unit'])){
                $unit = sanitize_text_field($_POST['stpr_unit']);
                update_post_meta($post_id, 'stpr_unit', $unit);
            }

            // get features from request, and save them
            
            if(isset($_POST['stpr_features'])){
                $stpr_features = array_map('sanitize_text_field', $_POST['stpr_features']);
                update_post_meta($post_id, 'stpr_features', $stpr_features, false);
            }
            else{
                //if no value was sent, set the features to empty
                update_post_meta($post_id, 'stpr_features', []);
            }

            // save price
            if(isset($_POST['stpr_price'])){
                $price = floatval($_POST['stpr_price']) * 100;
                update_post_meta($post_id, 'stpr_price', $price);
            }

            // save tenant
            if(isset($_POST['stpr_tenant'])){
                $tenant = intval($_POST['stpr_tenant']);
                update_post_meta($post_id, 'stpr_tenant', $tenant);
            }
            
            //update if now vacant
            if (isset($_POST['stpr_tenant']) && $_POST['stpr_tenant'] == "null"){
                //if the tenant is not set, set the last vacant date to today
                update_post_meta($post_id, 'stpr_last_vacant_date', date('Y-m-d H:i:s'));
            }
            else if (isset($_POST['stpr_tenant'])){
                //if the tenant is set, set the last rental date to today
                update_post_meta($post_id, 'stpr_last_rental_date', date('Y-m-d H:i:s'));
            }

            // // save last rental date
            // if(isset($_POST['stpr_last_rental_date'])){
            //     $last_rental_date = sanitize_text_field($_POST['stpr_last_rental_date']);
            //     update_post_meta($post_id, 'stpr_last_rental_date', $last_rental_date);
            // }

            // // save last payment date
            // if(isset($_POST['stpr_last_vacant_date'])){
            //     $last_payment_date = sanitize_text_field($_POST['stpr_last_vacant_date']);
            //     update_post_meta($post_id, 'stpr_last_vacant_date', $last_payment_date);
            // }
        }
    }

    // add inputs to the quick edit menu
    function display_quick_edit_custom($column_name, $post_type){
        
        //require_once plugin_dir_path(__FILE__) . 'elements/quick_edit_custom.php';
        if ($post_type != 'storagepress_unit') return;

        switch ($column_name) {
            case 'price': ?> 
                <fieldset class="inline-edit-col-right" style="display: flex; flex-direction: column;">
                    <div class="inline-edit-col">
                        <label for="stpr_size">
                            <span class="title">Size</span>
                            <span class="input-text-wrap">
                                <?php include_once $this->base_dir . 'elements/size_storage_unit_meta_field.php'; ?>
                            </span>
                        </label>
                        <label for="stpr_price">
                            <span class="title">Price</span>
                            <span class="input-text-wrap">
                                <?php include_once $this->base_dir . 'elements/price_storage_unit_meta_field.php'; ?>
                            </span>
                        </label>
                        <label for="stpr_tenant_select">
                            <span class="title">Tenant</span>
                            <span class="input-text">
                                <?php include_once $this->base_dir . 'elements/tenant_storage_unit_meta_field.php'; ?>
                            </span>
                    </div>
                </fieldset> 
                <?php
                break;
        }
    }

    //save data from those inputs
    // function save_quick_edit_data($post_id){
    //      //TODO: add nonce verification
    //    
    //     if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    //     if (!current_user_can('edit_post', $post_id)) return;
    //     if (isset($_POST['my_custom_field'])) {
    //         update_post_meta($post_id, 'my_custom_field', $_POST['my_custom_field']);
    //     }
    // }

    // add fields to listing of storage units table
    public function storage_units_columns($columns) {
        if (isset($_GET['post_type']) && $_GET['post_type'] === 'storagepress_unit') {
            unset($columns['date']); // remove date column
            $columns['size'] = 'Size'; // add custom field column
            $columns['price'] = 'Price'; // add custom field column
            $columns['tenant'] = 'Tenant'; // add custom field column
        }
        return $columns;
    }

    // populate custom fields columns in storage units table
    public function storage_units_custom_column($column, $post_id) {
        if (isset($_GET['post_type']) && $_GET['post_type'] === 'storagepress_unit') {
            switch ($column) {
                case 'price':
                    // get the custom field value and echo it
                    $custom_field_value = "$" . esc_attr(floatval(get_post_meta($post_id, 'stpr_price', true)) / 100);
                    echo $custom_field_value != "" ? $custom_field_value : "None";
                    break;
                case 'size':
                    // get the custom field value and echo it
                    $unit = esc_attr(get_post_meta($post_id, 'stpr_unit', true));
                    $length = esc_attr(get_post_meta($post_id, 'stpr_length', true));
                    $width = esc_attr(get_post_meta($post_id, 'stpr_width', true));
                    if ($length != "" && $width != "" && $unit != "") {
                        $custom_field_value = $length . " " . $unit . " &times; " . $width . " " . $unit;
                    } else {
                        $custom_field_value = "None";
                    }
                    echo $custom_field_value != "" ? $custom_field_value : "None";
                    break;
                case 'tenant':
                    // get the custom field value and echo it
                    $uid = esc_attr(get_post_meta($post_id, 'stpr_tenant', true));
                    $user = get_user_by('id', $uid);
                    if (isset($user)){
                        if (isset($user->display_name)){
                            if (isset($user->user_email)){
                                $custom_field_value = '<a href="mailto:' . esc_attr($user->user_email) .'">' . esc_attr($user->display_name) . '</a>';
                            }else{
                                $custom_field_value = $user->display_name;
                            }
                        }
                        else{
                            $custom_field_value = "None";
                        }
                    }else{
                        $custom_field_value = "None";
                    }
                    echo wp_kses($custom_field_value != "" ? $custom_field_value : "None", array('a' => array('href' => array())));
                    break;
            }
        }
    }

    //register custom templates to display storage units in
    public function register_single_template($single_template){
        global $post;
        if ($post->post_type == 'storagepress_unit')
            $single_template = $this->get_base_dir() . 'elements/templates/single_storage_unit.php';
        return $single_template;

    }
    public function register_archive_template($archive_template){
        global $post;
        if (is_post_type_archive('storage_unit'))
            $archive_template = $this->get_base_dir() . 'elements/templates/archive_storage_unit.php';
        return $archive_template;
    }

    //allow users to register for accounts
    public function allow_registration(){
        update_option('users_can_register', 1);
    }
}



$plugin = new StoragePress();