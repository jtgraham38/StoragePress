<?php
abstract class JGWPPlugin {

    protected $plugin_prefix;   //used to prefix settings, options, etc
    protected $settings_groups; //plugin settings groups, must contain array of JGWPSettingsGroup objects
    protected $settings;        //plugin settings

    public function __construct() {

        //ensure the user set a plugin prefix
        if (empty($this->plugin_prefix)) {
            throw new Exception('You must set a plugin prefix.');
        }

        //check if the last character of plugin prefix is an underscore
        if (substr($this->plugin_prefix, -1) !== '_') {
            // If not, add one
            $this->plugin_prefix .= '_';
        }

        //register settings
        add_action('admin_init', array($this, 'init_settings'));

        //enqueue admin resources
        add_action('admin_enqueue_scripts', array($this, 'admin_resources'));

        //enqueue front-end resources
        add_action('wp_enqueue_scripts', array($this, 'front_end_resources'));

    }


    public function init_settings(){
        //create settings sections
        foreach ($this->settings_groups as $group) {
            $group->set_prefix($this->plugin_prefix);
            $group->add();
        }

        //create settings that are not in a section
        foreach ($this->settings as $setting) {
            $setting->set_prefix($this->plugin_prefix);
            $setting->add();
        }
    }

    abstract public function admin_resources($hook);

    abstract public function front_end_resources($hook);
}

class JGWPBase{
    private $plugin_prefix;

    protected function set_prefix($prefix){
        $this->plugin_prefix = $prefix;
    }
}

class JGWPSettingsGroup extends JGWPBase{
    
    private $section_id;
    private $section_title;
    private $page;
    private $callback;

    public function __construct($section_id, $section_title, $page, $callback){
        $this->section_id = $section_id;
        $this->section_title = $section_title;
        $this->page = $page;
        $this->callback = $callback;
    }

    public function add(){
        add_settings_section(
            $this->section_id,      //id
            $this->section_title,   //title
            $this->callback,        //callback
            $this->page             //page to appear on
        );
    }
}


class JGWPSetting extends JGWPBase{
    private $name;
    private $args;

    private $lbl;
    private $page;
    private $section_id;


    public function __construct($name, $args, $page, $lbl, $section_id = null){
        //setting args
        $this->name = $name;
        $this->args = $args;
        //field args
        $this->page = $page;
        $this->section_id = $section_id;
        $this->lbl = $lbl;
    }

    public function add(){
        //register setting
        register_setting(
            $this->plugin_prefix . "_settings",    //TODO: switch this to the plugin prefix + "_settings"
            $this->name,    //option name
            $this->args    //args
        );

        //register field
        add_settings_field(
            $this->name . '_field',                 //id
            $this->lbl,                             //title
            function(){
                require plugin_dir_path(__FILE__) . 'elements/settings/' . $this->name . '_field.php';
            },                                      //callback to get input from "elements/settings/" + $this.name + "_field.php"
            $this->page,                            //page
            isset($this->section_id) ? $this->section_id : 'default'                            //section id to appear on
        );
    }
}