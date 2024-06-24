<?php 

// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
global $post; //get the post being edited 

//add nonce field
wp_nonce_field('storagepress_unit_meta_nonce', 'storagepress_unit_meta_fields_nonce_field');
//^key of nonce, name of input field
?>


<div class="stpr_input_group">
    <label class="storagepress_input_label" for="stpr_size">Size:</label>
    <?php include_once $this->base_dir . 'elements/size_storage_unit_meta_field.php'; ?>
</div>

<div class="stpr_input_group">
    <label class="storagepress_input_label" for="stpr_price">Price:</label>
    <?php include_once $this->base_dir . 'elements/price_storage_unit_meta_field.php'; ?>
</div>

<div class="stpr_input_group">
    <label class="storagepress_input_label" for="stpr_last_payment_amount">Features:</label>
    <div id="stpr_features">
        <?php 
        if (isset($post->ID)){
            //get all fields to create checkboxes
            $feature_options = get_option('storagepress_feature_options', []);
            $unit_features = get_post_meta($post->ID, 'stpr_features', false);
            $unit_features = isset($unit_features[0]) ? $unit_features[0] : [];

            $feature_options = array_merge($feature_options, $unit_features);
            $feature_options = array_unique($feature_options);


            if ((count($feature_options) > 0)){
                foreach ($feature_options as $feature_option) {
                
                    ?>
                    <div style="display: inline-flex; flex-direction: row; align-items: center; margin-right: 0.5rem;">
                        <label class="storagepress_input_label" style="margin-right: 0.25rem;" for="stpr_is_<?php echo esc_attr($feature_option) ?>">Is <?php echo esc_attr($feature_option) ?>?</label>
                        <input 
                            type="checkbox" id="stpr_is_<?php echo esc_attr($feature_option) ?>" 
                            value="<?php echo esc_attr($feature_option) ?>" name="stpr_features[]"
                            <?php echo in_array($feature_option, $unit_features) ? "checked" : ""?>
                        >
                    </div>
                    <?php
                }
            }
            else{
                ?> <div>No features available!</div> <?php
            }
        }
        ?>
    </div>
</div>



<div class="stpr_input_group" style="display: flex; flex-direction: row;">

    <div style="margin-right: 1rem;">
        <label class="storagepress_input_label" for="stpr_tenant">Tenant:</label>
        <?php require_once $this->base_dir . 'elements/tenant_storage_unit_meta_field.php'; ?>
        
    </div>


    
</div>
