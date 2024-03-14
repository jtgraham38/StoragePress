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
        box-shadow: 0 0.2rem 0.5rem #ccc;
        margin-top: 0.2rem;
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

<div id="storagepress_add_feature_option_container">
    <input type="text" id="storagepress_add_feature_option" placeholder="Add a feature option">
    <button type="button" id="storagepress_add_feature_option_button">Add</button>
</div>

<script>
    document.getElementById('storagepress_add_feature_option_button').addEventListener('click', function() {
        let feature_option = document.getElementById('storagepress_add_feature_option').value;
        if(feature_option) {

            //generate a random id
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let id_str = ''
            for (var i = 0; i < 16; i++) {
                id_str += characters.charAt(Math.floor(Math.random() * characters.length));
            }

            //clone the template and add it to the container
            let new_feature_option_input = document.importNode(document.getElementById('feature_option_template').content, true)
            console.log(new_feature_option_input)
            new_feature_option_input.querySelector('label').textContent = feature_option;
            new_feature_option_input.querySelector('input').value = feature_option;
            new_feature_option_input.querySelector('div').id = 'storagepress_feature_option_' + feature_option + "_" + id_str;
            new_feature_option_input.querySelector('button').addEventListener('click', function(event) {
                event.target.parentNode.remove()
            });
            
            document.getElementById('storagepress_feature_options_container').appendChild(new_feature_option_input);
            document.getElementById('storagepress_add_feature_option').value = '';

        }
    });
</script>

<div id="storagepress_feature_options_container"><?php
if(!empty($options)) {
    foreach($options as $key => $value) { ?>
        <div class="feature_option" id="storagepress_feature_option_<?php echo $key ?>">
            <label for="storagepress_feature_options[]"><?php echo $value ?></label>
            <button type="button" onclick="document.getElementById('storagepress_feature_option_<?php echo $key ?>').remove()">&times;</button>
            <input type="hidden" name="storagepress_feature_options[]" value="<?php echo $value ?>">
        </div>
<?php
    }
}
?>
</div>