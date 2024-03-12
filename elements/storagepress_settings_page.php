<?php 
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1>StoragePress Settings</h1>
    <form method="post" action="options.php">
        <?php
        // Output the settings fields.
        settings_fields('storagepress_settings');
        do_settings_sections('storagepress_settings');
        submit_button();
        ?>
    </form>
</div>