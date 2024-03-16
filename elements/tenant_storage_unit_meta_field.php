<?php
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>
<select class="storagepress_settings_input" id="sp_tenant_select" name="sp_tenant">
    <?php 
    $users = get_users();
    foreach ($users as $user) {
        //$selected = isset($post->ID) && get_post_meta($post->ID, 'sp_tenant', true) == $user->ID ? 'selected' : '';
        ?><option value="<?php echo esc_attr($user->ID)?>"> <?php echo esc_html($user->display_name) ?> </option> <?php
    }
    ?>
</select>
<div>TODO: select user</div>