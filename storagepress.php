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

    // add storagepress menu
    public function storagepress_setup_menu(){
        add_menu_page( 'StoragePress', 'StoragePress', 'manage_options', 'storagepress', array($this, 'storagepress_init'), 'dashicons-vault');
    }

    // initialize storagepress
    public function storagepress_init(){
        echo "<h1>StoragePress</h1>";
    }

    // register storage units post type
    public function register_storage_units_post_type() {
        $args = array(
            'public' => true,
            'label'  => 'Storage Units',
            'show_in_menu' => 'storagepress',
            'labels' => array(
                'add_new' => 'Add New Storage Unit',
                'search_items' => 'Search Storage Units',
            ),
        );
        register_post_type('storage_units', $args);
    }

    // add custom field to storage units listing
    public function storage_units_columns($columns) {
        unset($columns['date']); // remove date column
        $columns['size'] = 'Size'; // add custom field column
        $columns['price'] = 'Price'; // add custom field column
        $columns['status'] = 'Status'; // add custom field column
        return $columns;
    }

    // populate custom field column
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

    // register custom meta box
    public function register_price_meta_box() {
        add_meta_box('price_meta_box', 'Price', array($this, 'price_meta_box_callback'), 'storage_units', 'side', 'high');
    }

    // callback function for custom meta box
    public function price_meta_box_callback($post) {
        wp_nonce_field(basename(__FILE__), 'price_nonce');
        $stored_meta = get_post_meta($post->ID);
        $price = isset($stored_meta['price']) ? $stored_meta['price'][0] : '';
        echo '<input type="number" name="price" value="' . esc_attr($price) . '"/>';
    }

    // save custom meta box data
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