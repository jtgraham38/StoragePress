<?php
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

//get unit
$unit = get_post($post->ID);
if ($unit) {
    $tenant_id = get_post_meta($unit->ID, 'sp_tenant', true);
}
?>
<select class="storagepress_settings_input" id="sp_tenant_select" name="sp_tenant">
    <option value="null">No Tenant</option>
    <?php 
    $users = get_users();
    foreach ($users as $user) {
        
        //$selected = isset($post->ID) && get_post_meta($post->ID, 'sp_tenant', true) == $user->ID ? 'selected' : '';
        ?>
        <option 
            value="<?php echo esc_attr($user->ID)?>"
            <?php echo $tenant_id == $user->ID ? 'selected' : ''?>
        > 
            <?php echo esc_html($user->display_name) ?> 
        </option> <?php
    }
    ?>
</select>