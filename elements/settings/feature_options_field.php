<?php 
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

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

<div x-data="{ feature_options: JSON.parse('<?php echo esc_attr(json_encode($options)) ?>'), new_value: '' }">
    <div id="storagepress_add_feature_option_container">
        <input type="text" id="storagepress_add_feature_option" x-model="new_value" placeholder="Add a feature option" title="Please enter the name of a unit feature you would like to be able to attach to your storage units (e.g. climate-controlled, drive-up access, etc.).">
        <button type="button" id="storagepress_add_feature_option_button" @click="if (new_value) {feature_options.push(new_value); new_value = ''}">Add</button>
    </div>

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