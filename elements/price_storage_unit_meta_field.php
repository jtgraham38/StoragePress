<?php 
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>


<span>$</span>
<?php if (isset($post->ID)){ $price = get_post_meta($post->ID, 'sp_price', true); } ?>
<input class="storagepress_settings_input" type="number" min="0" step="0.01" id="sp_price" name="sp_price" value="<?php echo $price ? esc_attr($price) / 100 : ''; ?>" size="25" />
<span>/ month</span>