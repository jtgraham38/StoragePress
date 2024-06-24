<?php 
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<?php
if (isset($post->ID)){ $length = get_post_meta($post->ID, 'stpr_length', true); }
if (isset($post->ID)){ $width = get_post_meta($post->ID, 'stpr_width', true); }
if (isset($post->ID)){ $unit = get_post_meta($post->ID, 'stpr_unit', true); }
?>

<div id="stpr_size"  style="display: flex; flex-direction: row; align-items: center;">
    <input class="storagepress_settings_input" type="number" min="0" step="0.25" id="stpr_length" name="stpr_length" value="<?php echo isset($length) ? esc_attr($length) : ''; ?>" size="25" />
    <span>&times;</span>
    <input class="storagepress_settings_input" type="number" min="0" step="0.25" id="stpr_width" name="stpr_width" value="<?php echo isset($width) ? esc_attr($width) : ''; ?>" size="25" />
    <select name="stpr_unit" id="stpr_unit" style="margin-left: 0.25rem;">
        <option value="ft" <?php echo isset($unit) && $unit == 'ft' ? 'selected' : ''; ?>>ft</option>
        <option value="m" <?php echo isset($unit) && $unit == 'm' ? 'selected' : ''; ?>>m</option>
    </select>
</div>