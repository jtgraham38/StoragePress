<?php
abstract class JGWPPlugin {

    protected $plugin_prefix;   //used to prefix settings, options, etc
    

    public function __construct() {

        //ensure the user set a plugin prefix
        if (empty($this->plugin_prefix)) {
            throw new Exception('You must set a plugin prefix.');
        }

        //register settings
        add_action('admin_init', array($this, 'init_settings'));

        //enqueue admin resources
        add_action('admin_enqueue_scripts', array($this, 'admin_resources'));

        //enqueue front-end resources
        add_action('wp_enqueue_scripts', array($this, 'front_end_resources'));

    }


    abstract public function init_settings();

    abstract public function admin_resources($hook);

    abstract public function front_end_resources($hook);
}