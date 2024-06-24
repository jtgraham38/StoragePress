<?php 
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>


<span>$</span>
<?php if (isset($post->ID)){ $price = get_post_meta($post->ID, 'stpr_price', true); } ?>
<input class="storagepress_settings_input" type="number" min="0" step="0.01" id="stpr_price" name="stpr_price" value="<?php echo esc_attr($price ? $price / 100 : ''); ?>" size="25" />
<span>/ month</span>