<?php 

// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
global $post; //get the post being edited 
?>

<style>
    .sp_input_group{
        margin-bottom: 0.75rem;
    }
</style>

<div class="sp_input_group">
    <label class="storagepress_input_label" for="sp_size">Size:</label>
    <?php include_once $this->base_dir . 'elements/size_storage_unit_meta_field.php'; ?>
</div>

<div class="sp_input_group">
    <label class="storagepress_input_label" for="sp_price">Price:</label>
    <?php include_once $this->base_dir . 'elements/price_storage_unit_meta_field.php'; ?>
</div>

<div class="sp_input_group">
    <label class="storagepress_input_label" for="sp_last_payment_amount">Features:</label>
    <div id="sp_features">
        <?php 
        if (isset($post->ID)){
            //get all fields to create checkboxes
            $feature_options = get_option('storagepress_feature_options', []);
            $unit_features = get_post_meta($post->ID, 'sp_features', false);
            $unit_features = isset($unit_features[0]) ? $unit_features[0] : [];

            $feature_options = array_merge($feature_options, $unit_features);
            $feature_options = array_unique($feature_options);


            foreach ($feature_options as $feature_option) {
                
                ?>
                <div style="display: inline-flex; flex-direction: row; align-items: center; margin-right: 0.5rem;">
                    <label class="storagepress_input_label" style="margin-right: 0.25rem;" for="sp_is_<?php echo $feature_option ?>">Is <?php echo $feature_option ?>?</label>
                    <input 
                        type="checkbox" id="sp_is_<?php echo $feature_option ?>" 
                        value="<?php echo $feature_option ?>" name="sp_features[]"
                        <?php echo in_array($feature_option, $unit_features) ? "checked" : ""?>
                    >
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>

<div class="sp_input_group">
    

</div>

<div class="sp_input_group" style="display: flex; flex-direction: row;">

    <div style="margin-right: 1rem;">
        <label class="storagepress_input_label" for="sp_tenant">Tenant:</label>
        <?php require_once $this->base_dir . 'elements/tenant_storage_unit_meta_field.php'; ?>
    </div>
    <div style="margin-right: 1rem;">
        <label class="storagepress_input_label" for="sp_last_vacant_date">Last Vacant Date:</label>
        <input class="storagepress_settings_input" type="date" id="sp_last_vacant_date" name="sp_last_vacant_date" value="<?php echo isset($post->ID) && get_post_meta($post->ID, 'sp_last_vacant_date', true) ? date('Y-m-d', strtotime(esc_attr(get_post_meta($post->ID, 'sp_last_vacant_date', true)))) : date("Y-m-d"); ?>" size="25" />
    </div>
    <div>
        <label class="storagepress_input_label" for="sp_last_rental_date">Last Rental Date:</label>
        <input class="storagepress_settings_input" type="date" id="sp_last_rental_date" name="sp_last_rental_date" value="<?php echo isset($post->ID) && get_post_meta($post->ID, 'sp_last_rental_date', true) ? date('Y-m-d', strtotime(esc_attr(get_post_meta($post->ID, 'sp_last_rental_date', true)))) : ''; ?>" size="25" />
    </div>
</div>
