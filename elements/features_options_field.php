<?php 
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<style>

    .feature_option {
        display: inline-flex;
        padding: 0.1rem 0 0.1rem 0.2rem;
        align-items: center;
        border: 1px solid #ccc;
        border-radius: 0.5rem;
        box-shadow: 0 0.25rem 0.5rem #ccc;
        margin-top: 0.4rem;
        margin-right: 0.2rem;
    }

    .feature_option button {
        border: none;
        margin-left: 1rem;
        font-size: larger;
        background-color: transparent;
        display: grid;
        place-items: center;
        
    }
    .feature_option button:hover {
        font-weight: bold;
        color: red;
    }
    
    #storagepress_add_feature_option_container{
        display: inline-flex;
        align-items: center;
        border: 1px solid #ccc;
        border-radius: 0.5rem;
        
    }
    #storagepress_add_feature_option_button {
        border: none;
        padding-left: 1rem;
        font-size: larger;
        background-color: transparent;
        display: grid;
        place-items: center;
    }
    #storagepress_add_feature_option_button:hover {
        color: green;
    }
    #storagepress_add_feature_option {
        border: none;
        border-top-left-radius: 0.5rem;
        border-bottom-left-radius: 0.5rem;
    }
</style>

<?php
//update_option('storagepress_feature_options', ['air-conditioned', 'drive-up', 'extra-tall']);
$options = get_option('storagepress_feature_options', []);
//var_dump(get_option('storagepress_feature_options', []));
?>

<template id="feature_option_template">
    <div class="feature_option" id="storagepress_feature_option_{{key}}">
        <label for="storagepress_feature_options[]">{{value}}</label>
        <button type="button">&times;</button>
        <input type="hidden" name="storagepress_feature_options[]" value="{{value}}">
    </div>
</template>

<div x-data="{ feature_options: JSON.parse('<?php echo htmlspecialchars(json_encode($options)) ?>'), new_value: '' }">
    <div id="storagepress_add_feature_option_container">
        <input type="text" id="storagepress_add_feature_option" x-model="new_value" placeholder="Add a feature option">
        <button type="button" id="storagepress_add_feature_option_button" @click="if (new_value) {feature_options.push(new_value); new_value = ''}">Add</button>
    </div>

    <span x-text="new_value"></span>

    <div id="storagepress_feature_options_container">
        <template x-for="(value, key) in feature_options" :key="key">
            <div class="feature_option" id="storagepress_feature_option_{{key}}">
                <label for="storagepress_feature_options[]" x-text="value"></label>
                <button type="button" @click="feature_options.splice(key, 1)">&times;</button>
                <input type="hidden" name="storagepress_feature_options[]" :value="value">
            </div>
        </template>
    </div>
</div>