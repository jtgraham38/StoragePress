<?php 
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>
<select name="storagepress_listing_page" class="storagepress_settings_input" id="listing_page_input" title="Please select the page you display your storage units on.">
    <option value="">Select a page</option>
    <?php
    $pages = get_pages();
    foreach ($pages as $page) {
        $selected = get_option('storagepress_listing_page') == $page->ID ? 'selected' : '';
        ?><option value="<?php echo $page->ID?>" <?php echo $selected ?>> <?php echo $page->post_title ?></option><?php
    }
    ?>
</select>