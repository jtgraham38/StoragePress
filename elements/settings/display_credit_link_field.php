<?php 
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<input type="checkbox" name="storagepress_display_credit_link" <?php checked(get_option('storagepress_display_credit_link')) ?> value="1"/>
<small>&nbsp; This displays a small link to the developer's website on reservation modals.  This helps aid development efforts and debugging.  If you can display it, it would be much appreciated!</small>
<br>
<br>

<p>Visit <a href="https://jacob-t-graham.com" target="_blank">my website</a> for inquiries!</p>
<p>Would online payment processing be useful to your business?  <a href="https://jacob-t-graham.com/contact" target="_blank">Send me an email</a> letting me know, and if there is enough interest, I will build a solution that integrates with this plugin!</p>