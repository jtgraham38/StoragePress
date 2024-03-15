<?php 
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>
<input type="text" name="storagepress_name" value="<?php echo esc_attr(get_option( 'storagepress_name', '' )) ?>" class="storagepress_settings_input" id="name_input" placeholder="Business Name" title="Please enter the name of your business.">