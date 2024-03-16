<?php 
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>


<span>$</span>
<input class="storagepress_settings_input" type="number" min="0" step="0.01" id="sp_price" name="sp_price" value="<?php echo isset($post->ID) && get_post_meta($post->ID, 'sp_price', true) ? esc_attr(floatval(get_post_meta($post->ID, 'sp_price', true)) / 100) : ''; ?>" size="25" />
<span>/ month</span>