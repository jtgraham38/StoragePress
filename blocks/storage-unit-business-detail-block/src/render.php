<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>
<?php
if (array_key_exists('key', $attributes)){
    //generate the output based on the key
    $output = '';
    switch($attributes['key']){
        case 'storagepress_name':
            $output = get_option('storagepress_name');
            break;
        case 'storagepress_address':
            $output = get_option('storagepress_address');
            break;
        case 'storagepress_phone':
            $phone_number = get_option('storagepress_phone');
            $output = '<a href="tel:' . $phone_number . '">' . $phone_number . '</a>';
            break;
        case 'storagepress_email':
            $email = get_option('storagepress_email');
            $output = '<a href="mailto:' . $email . '">' . $email . '</a>';
            break;
        case 'storagepress_rental_terms':
            $output = get_option('storagepress_rental_terms');
            break;
        case 'storagepress_checks_payable_to':
            $output = get_option('storagepress_checks_payable_to');
            break;
        case 'storagepress_listing_page':
            //get the url of the listing page
            $listing_page_id = get_option('storagepress_listing_page');
            $listing_page = get_post($listing_page_id);
            if ($listing_page){
                $output = '<a href="' . get_permalink($listing_page) . '">' . $listing_page->post_title . '</a>';
            }else{
                $output = '<span>(No Listing Page Set)</span>';
            }
            break;
        default:
            $output = '<div>(Invalid Business Detail Chosen)</div>';
    }

    //echo out content
    echo '<div ' . get_block_wrapper_attributes() . '>' . $output . '</div>';
}
else{
    echo '<div ' . get_block_wrapper_attributes() . '>(No Business Detail Chosen)</div>';
}

