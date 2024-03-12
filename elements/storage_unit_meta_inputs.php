<?php global $post; //get the post being edited ?>

<div>
    <label class="storagepress_input_label" for="sp_size">Size:</label>
    <div id="sp_size"  style="display: flex; flex-direction: row; align-items: center;">
        <input class="storagepress_settings_input" type="text" id="sp_length" name="sp_length" value="<?php echo isset($post->ID) && get_post_meta($post->ID, 'sp_length', true) ? esc_attr(get_post_meta($post->ID, 'sp_length', true)) : ''; ?>"size="25" />
        <span>&times;</span>
        <input class="storagepress_settings_input" type="text" id="sp_width" name="sp_width" value="<?php echo isset($post->ID) && get_post_meta($post->ID, 'sp_width', true) ? esc_attr(get_post_meta($post->ID, 'sp_width', true)) : ''; ?>"size="25" />
        <select name="sp_unit" id="sp_unit" style="margin-left: 0.25rem;">
            <option value="ft" <?php echo isset($post->ID) && get_post_meta($post->ID, 'sp_unit', true) == 'ft' ? 'selected' : ''; ?>>ft</option>
            <option value="m" <?php echo isset($post->ID) && get_post_meta($post->ID, 'sp_unit', true) == 'm' ? 'selected' : ''; ?>>m</option>
        </select>
    </div>
</div>

<div>
    <label class="storagepress_input_label" for="sp_price">Price:</label>
    <span>$</span>
    <input class="storagepress_settings_input" type="number" min="0" step="0.01" id="sp_price" name="sp_price" value="<?php echo isset($post->ID) && get_post_meta($post->ID, 'sp_price', true) ? esc_attr(floatval(get_post_meta($post->ID, 'sp_price', true)) / 100) : ''; ?>" size="25" />
    <span>/ month</span>
</div>

<div>
    <label class="storagepress_input_label" for="sp_last_payment_amount">Type:</label>
    <small style="display: block;">
        TODO: need to change type into multivalued attribute, then make the 
    </small>
    <?php 
        $types = ["get", "these", "from", "plugin", "setting"];
        foreach ($types as $type) {
            ?>
            <div style="display: inline-flex; flex-direction: row; align-items: center; margin-right: 0.5rem;">
                <label class="storagepress_input_label" style="margin-right: 0.25rem;" for="sp_is_<?php echo $type ?>">Is <?php echo $type ?>?</label>
                <input type="checkbox" id="sp_is_<?php echo $type ?>" <?php echo false ? "checked" : ""?> > 
            </div>
            <?php
        }
    ?>
</div>

<div>
    <label class="storagepress_input_label" for="sp_tenant">Tenant:</label>
    <select class="storagepress_settings_input" id="sp_tenant_select" name="sp_tenant">
        <?php 
        $users = get_users();
        foreach ($users as $user) {
            //$selected = isset($post->ID) && get_post_meta($post->ID, 'sp_tenant', true) == $user->ID ? 'selected' : '';
            ?><option value="<?php echo esc_attr($user->ID)?>"> <?php echo esc_html($user->display_name) ?> </option> <?php
        }
        ?>
    </select>

</div>

<div>
    <label class="storagepress_input_label" for="sp_last_rental_date">Last Rental Date:</label>
    <input class="storagepress_settings_input" type="date" id="sp_last_rental_date" name="sp_last_rental_date" value="<?php echo isset($post->ID) && get_post_meta($post->ID, 'sp_last_rental_date', true) ? date('Y-m-d', strtotime(esc_attr(get_post_meta($post->ID, 'sp_last_rental_date', true)))) : ''; ?>" size="25" />
</div>

<div>
    <label class="storagepress_input_label" for="sp_last_vacant_date">Last Vacant Date:</label>
    <input class="storagepress_settings_input" type="date" id="sp_last_vacant_date" name="sp_last_vacant_date" value="<?php echo isset($post->ID) && get_post_meta($post->ID, 'sp_last_vacant_date', true) ? date('Y-m-d', strtotime(esc_attr(get_post_meta($post->ID, 'sp_last_vacant_date', true)))) : ''; ?>" size="25" />
</div>