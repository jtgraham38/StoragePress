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
        add_action('admin_menu', array($this, 'storagepress_setup_menu'));

        //register storage units post type
        add_action('init', array($this, 'register_storage_units_post_type'));

        //set cols that appear in storage unit listing
        add_filter('manage_storage_units_posts_columns', array($this, 'storage_units_columns'));
        add_action('manage_storage_units_posts_custom_column', array($this, 'storage_units_custom_column'), 10, 2);

        //add price meta to the storage units
        add_action('add_meta_boxes', array($this, 'register_price_meta_box'));
        add_action('save_post', array($this, 'save_price_meta_box'));
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
        register_post_type('storage_units', $args);
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

    // create a meta box to edit the "price" meta field of a storage unit
    public function register_price_meta_box() {
        add_meta_box('price_meta_box', 'Price', array($this, 'price_meta_box_callback'), 'storage_units', 'side', 'high');
    }

    // create a field for the price meta box
    public function price_meta_box_callback($post) {
        wp_nonce_field(basename(__FILE__), 'price_nonce');
        $stored_meta = get_post_meta($post->ID);
        $price = isset($stored_meta['price']) ? $stored_meta['price'][0] : '';
        echo '<input type="number" name="price" value="' . esc_attr($price) . '"/>';
    }

    // save data from the price meta box to the database
    public function save_price_meta_box($post_id) {
        if (!isset($_POST['price_nonce']) || !wp_verify_nonce($_POST['price_nonce'], basename(__FILE__))) {
            return $post_id;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
        if (isset($_POST['price'])) {
            update_post_meta($post_id, 'price', sanitize_text_field($_POST['price']));
        }
    }
}

$plugin = new StoragePress();