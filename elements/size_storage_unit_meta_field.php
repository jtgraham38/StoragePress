<?php 
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>


<div id="sp_size"  style="display: flex; flex-direction: row; align-items: center;">
    <input class="storagepress_settings_input" type="number" min="0" step="0.25" id="sp_length" name="sp_length" value="<?php echo isset($post->ID) && get_post_meta($post->ID, 'sp_length', true) ? esc_attr(get_post_meta($post->ID, 'sp_length', true)) : ''; ?>"size="25" />
    <span>&times;</span>
    <input class="storagepress_settings_input" type="number" min="0" step="0.25" id="sp_width" name="sp_width" value="<?php echo isset($post->ID) && get_post_meta($post->ID, 'sp_width', true) ? esc_attr(get_post_meta($post->ID, 'sp_width', true)) : ''; ?>"size="25" />
    <select name="sp_unit" id="sp_unit" style="margin-left: 0.25rem;">
        <option value="ft" <?php echo isset($post->ID) && get_post_meta($post->ID, 'sp_unit', true) == 'ft' ? 'selected' : ''; ?>>ft</option>
        <option value="m" <?php echo isset($post->ID) && get_post_meta($post->ID, 'sp_unit', true) == 'm' ? 'selected' : ''; ?>>m</option>
    </select>
</div>