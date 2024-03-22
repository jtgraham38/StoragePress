<?php 
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>
<input type="text" name="storagepress_checks_payable_to" value="<?php echo esc_attr(get_option( 'storagepress_checks_payable_to', '' )) ?>" class="storagepress_settings_input" id="checks_payable_to_input" placeholder="Make Checks Payable To" title="Please enter the name you would like customers to make their checks out to.">