<?php 
//exit if file is called directly
if (!defined('ABSPATH')) {
    exit;
}

//redirect to listing page
$page_id = get_option('storagepress_listing_page');
if (isset($page_id)  && !is_page($page_id)){
    $page = get_permalink($page_id);
    wp_redirect($page);
    exit;
}
//otherwise, redirect to homepage
else{
    wp_redirect(home_url());
    exit;
}
