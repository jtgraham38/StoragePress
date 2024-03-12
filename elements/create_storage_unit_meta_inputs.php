<?php global $post; //get the post being edited ?>

<div>
    <label for="sp_size">Size:</label>
    <input type="text" id="sp_size" name="sp_size" value="<?php echo isset($post->ID) && get_post_meta($post->ID, 'sp_size', true) ? esc_attr(get_post_meta($post->ID, 'sp_size', true)) : ''; ?>" size="25" />
</div>

<div>
    <label for="sp_price">Price:</label>
    <input type="text" id="sp_price" name="sp_price" value="<?php echo isset($post->ID) && get_post_meta($post->ID, 'sp_price', true) ? esc_attr(get_post_meta($post->ID, 'sp_price', true)) : ''; ?>" size="25" />
</div>

<div>
    <label for="sp_last_payment_amount">Type:</label>
    <input type="text" id="sp_type" name="sp_type" value="<?php echo isset($post->ID) && get_post_meta($post->ID, 'sp_type', true) ? esc_attr(get_post_meta($post->ID, 'sp_type', true)) : ''; ?>" size="25" />
</div>

<div>
    <label for="sp_status">Status:</label>
    <input type="text" id="sp_status" name="sp_status" value="<?php echo isset($post->ID) && get_post_meta($post->ID, 'sp_status', true) ? esc_attr(get_post_meta($post->ID, 'sp_status', true)) : ''; ?>" size="25" />
</div>

<div>
    <label for="sp_tenant">Tenant:</label>
    <input type="text" id="sp_tenant" name="sp_tenant" value="<?php echo isset($post->ID) && get_post_meta($post->ID, 'sp_tenant', true) ? esc_attr(get_post_meta($post->ID, 'sp_tenant', true)) : ''; ?>" size="25" />
</div>

<div>
    <label for="sp_last_rental_date">Last Rental Date:</label>
    <input type="text" id="sp_last_rental_date" name="sp_last_rental_date" value="<?php echo isset($post->ID) && get_post_meta($post->ID, 'sp_last_rental_date', true) ? esc_attr(get_post_meta($post->ID, 'sp_last_rental_date', true)) : ''; ?>" size="25" />
</div>

<div>
    <label for="sp_last_vacant_date">Last Vacant Date:</label>
    <input type="text" id="sp_last_vacant_date" name="sp_last_vacant_date" value="<?php echo isset($post->ID) && get_post_meta($post->ID, 'sp_last_vacant_date', true) ? esc_attr(get_post_meta($post->ID, 'sp_last_vacant_date', true)) : ''; ?>" size="25" />
</div>