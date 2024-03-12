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

class StoragePress{

    // constructor
    public function __construct(){
        //create pages for managing storage units
        add_action('admin_menu', array($this, 'storagepress_setup_menu'));

        //register storage units post type
        add_action('init', array($this, 'register_storage_units_post_type'));

        //set cols that appear in storage unit listing
        add_filter('manage_storage_units_posts_columns', array($this, 'storage_units_columns'));
        add_action('manage_storage_units_posts_custom_column', array($this, 'storage_units_custom_column'), 10, 2);

        //add inputs to storage unit create form
        add_action('edit_form_after_editor', array($this, 'add_inputs_to_storage_unit_create_form'));

        //save the custom fields of the storage units when the unit is saved
        add_action( 'save_post', array($this, 'save_storage_unit_custom_fields'));
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
            'storagepress_settings', // $menu_slug
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
        register_meta('sp_storage_units', 'sp_size', array(  //size
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
        ));
        register_meta('sp_storage_units', 'sp_type', array(  //type
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
        ));
        register_meta('sp_storage_units', 'sp_price', array(  //price
            'show_in_rest' => true,
            'single' => true,
            'type' => 'number',
        ));
        register_meta('sp_storage_units', 'sp_status', array(  //status
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
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

    // add inputs to storage unit create form
    public function add_inputs_to_storage_unit_create_form(){
        require_once plugin_dir_path(__FILE__) . 'elements/storage_unit_meta_inputs.php';
    }

    //save custom fields for storage units
    function save_storage_unit_custom_fields($post_id){
        if(isset($_POST['post_type']) && $_POST['post_type'] == 'sp_storage_units'){
            // save size
            if(isset($_POST['sp_size'])){
                $size = sanitize_text_field($_POST['sp_size']);
                update_post_meta($post_id, 'sp_size', $size);
            }

            // save type
            if(isset($_POST['sp_type'])){
                $type = sanitize_text_field($_POST['sp_type']);
                update_post_meta($post_id, 'sp_type', $type);
            }

            // save price
            if(isset($_POST['sp_price'])){
                $price = floatval($_POST['sp_price']);
                update_post_meta($post_id, 'sp_price', $price);
            }

            // save status
            if(isset($_POST['sp_status'])){
                $status = sanitize_text_field($_POST['sp_status']);
                update_post_meta($post_id, 'sp_status', $status);
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
            if(isset($_POST['sp_last_payment_date'])){
                $last_payment_date = sanitize_text_field($_POST['sp_last_payment_date']);
                update_post_meta($post_id, 'sp_last_payment_date', $last_payment_date);
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


}

$plugin = new StoragePress();