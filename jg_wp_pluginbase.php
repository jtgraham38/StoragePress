<?php
class JG_WP_Pluginbase {
    public function __construct() {
        add_action('init', array($this, 'init'));
    }

    public function init() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function enqueue_scripts() {
        wp_enqueue_script('jg_wp_pluginbase', plugins_url('jg_wp_pluginbase.js', __FILE__), array('jquery'), '1.0', true);
    }
}