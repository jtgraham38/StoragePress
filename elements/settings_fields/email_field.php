<?php 
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>
<input type="email" name="storagepress_email" value="<?php echo esc_attr(get_option( 'storagepress_email', '' )) ?>" class="storagepress_settings_input" id="email_input" placeholder="Business Email" title="Please enter a valid email address.">