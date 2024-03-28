<?php
if (array_key_exists('key', $attributes)){
    switch($attributes['key']){
        case 'storagepress_name':
            echo get_option('storagepress_name');
            break;
        case 'storagepress_address':
            ?> 
            <address style="display: inline;">
                <?php echo get_option('storagepress_address'); ?>
            </address>
            <?php
            break;
        case 'storagepress_phone':
            ?> 
            <a href="tel:<?php echo get_option('storagepress_phone'); ?>"><?php echo get_option('storagepress_phone'); ?></a>
            <?php
            break;
        case 'storagepress_email':
            ?> 
            <a href="mailto:<?php echo get_option('storagepress_email'); ?>"><?php echo get_option('storagepress_email'); ?></a>
            <?php
            break;
        case 'storagepress_rental_terms':
            ?>
            <details style="display: inline;">
                <summary>Rental Terms</summary>
                <p><?php echo get_option('storagepress_rental_terms'); ?></p>
            </details>
            <?php
            break;
        case 'storagepress_checks_payable_to':
            ?>
            <i><?php echo get_option('storagepress_checks_payable_to'); ?></i>
            <?php
            break;
        case 'storagepress_listing_page':
            //get the url of the listing page
            $listing_page_id = get_option('storagepress_listing_page');
            $listing_page = get_post($listing_page_id);
            if ($listing_page){
                ?>
                <a href="<?php echo get_permalink($listing_page); ?>"><?php echo $listing_page->post_title; ?></a>
                <?php
            }else{
                echo '<span>(No Listing Page Set)</span>';
            }
            break;
        default:
            echo '<span>(Invalid Business Detail Chosen)</span>';
    }
}
else{
    echo '<span>(No Business Detail Chosen)</span>';
}