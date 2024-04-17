<?php 
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>
<select name="storagepress_listing_page" class="storagepress_settings_input" id="listing_page_input" title="Please select the page you display your storage units on.">
    <option value="">Create New Page (None selected)</option>
    <?php
    $pages = get_pages();
    foreach ($pages as $page) {
        $selected = get_option('storagepress_listing_page') == $page->ID ? 'selected' : '';
        ?><option value="<?php echo esc_attr($page->ID)?>" <?php echo esc_attr($selected) ?>> <?php echo $page->post_title ?></option><?php
    }
    ?>
</select>