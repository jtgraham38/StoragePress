<?php 
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>
<input type="text" name="storagepress_phone" value="<?php echo esc_attr(get_option( 'storagepress_phone', '' )) ?>" class="storagepress_settings_input" id="phone_input" placeholder="Business Phone Number" pattern="\d{3}-\d{3}-\d{4}" title="Please enter a valid phone number (e.g. 123-456-7890).">
