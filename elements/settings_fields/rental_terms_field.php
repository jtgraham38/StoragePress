<?php 
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>
<textarea name="storagepress_rental_terms" id="rental_terms_input" class="storagepress_settings_input" placeholder="Rental Terms and Conditions" title="Please enter Terms and Conditions for your unit rentals." style="width: 100%; height: 300px;"><?php echo esc_attr(get_option( 'storagepress_rental_terms', '' )) ?></textarea>
