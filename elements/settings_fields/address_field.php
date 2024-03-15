<?php 
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>
<input type="text" name="storagepress_address" value="<?php echo esc_attr(get_option( 'storagepress_address', '' )) ?>" class="storagepress_settings_input" id="address_input" placeholder="Business Address" title="Please enter your business address.">