<?php
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?> <h2>Rental Inquirees</h2> <?php

//verify nonce
if (isset($_POST['approve']) || isset($_POST['deny'])) {
    if (!isset($_POST['approve_deny_reservation_inquiry_nonce']) || !wp_verify_nonce($_POST['approve_deny_reservation_inquiry_nonce'], 'approve_deny_reservation_inquiry')) {
        http_response_code(403);
        die('Invalid nonce.');
    }
}


//handle reservation inquiry actions
if (current_user_can('manage_options')) {
    if (isset($_POST['approve'])) {
    
        //get unit
        $unit_id = intval($_POST['unit_id']);
        $unit = get_post($unit_id);
    
        //ensure unit exists
        if (!$unit) {
            http_response_code(404);
            die('Invalid unit id.');
        }
    
        //send notification email to reserver
        $reserver_id = intval($_POST['reserver_id']);
        $reserver = get_userdata($reserver_id);
        $unit = get_post($unit_id);
        $subject = "Reservation Inquiry Approved!";
        $message = "Your reservation inquiry for the storage unit \"" . $unit->post_title . "\" has been approved!";
        wp_mail($reserver->user_email, $subject, $message);
    
        //update storage unit post meta to reflect reservation inquiry approval
        update_post_meta($unit_id, "stpr_tenant", $reserver_id);
        update_post_meta($unit_id, "stpr_reservation_inquirer", "0"); //must set it to 0, not "" or null
        update_post_meta($unit_id, "stpr_last_rental_date", date("Y-m-d H:i:s"));   //update the last rental date (this represents the last date that a rental began)
    
        
    
    
    } else if (isset($_POST['deny'])) {
    
        //get unit
        $unit_id = intval($_POST['unit_id']);
        $unit = get_post($unit_id);
    
        //ensure unit exists
        if (!$unit) {
            http_response_code(404);
            die('Invalid unit id.');
        }
    
        //send notification email to reserver
        $reserver_id = intval($_POST['reserver_id']);
        $reserver = get_userdata($reserver_id);
        $subject = "Reservation Inquiry Denied!";
        $message = "Your reservation inquiry for the storage unit \"" . $unit->post_title . "\" has been denied.";
        wp_mail($reserver->user_email, $subject, $message);
    
        //update storage unit post meta to reflect reservation inquiry denial
        update_post_meta($unit_id, "stpr_reservation_inquirer", "0"); //must set it to 0, not "" or null
    }
}


//query for all storage units which have an inquirer
$inquirer_query = new WP_Query(array(
    'post_type' => 'storagepress_unit',
    'meta_query' => array(
        array(
            'relation' => 'AND',
            array(
                'key' => 'stpr_reservation_inquirer',
                'compare' => 'EXISTS'
            ),
            'key' => 'stpr_reservation_inquirer',
            'value' => '0',
            'compare' => '!='
        )
    )
));

//output all inquiries
if ($inquirer_query->have_posts()) {
    while ($inquirer_query->have_posts()) {
        $inquirer_query->the_post();
        $inquirer_id = get_post_meta(get_the_ID(), "stpr_reservation_inquirer", true);
        $inquirer = get_userdata($inquirer_id);
        ?>
        <div class="postbox postbox-card clearfix">
            <div>
                <h2>Reservation Inquiry for Storage Unit "<?php the_title() ?>"</h2>
            </div>

            <div class="unit_detail_box">
                <div class="unit_detail">
                    <?php
                    $length = get_post_meta(get_the_ID(), "stpr_length", true);
                    $width = get_post_meta(get_the_ID(), "stpr_width", true);
                    $unit = get_post_meta(get_the_ID(), "stpr_unit", true);
                    ?>
                    Size: <?php echo esc_attr($length); ?> <?php echo esc_attr($unit); ?> &times; <?php echo esc_attr($width); ?> <?php echo esc_attr($unit); ?>
                </div>
                <div class="unit_detail">
                    Price: $<?php echo esc_attr(((int)get_post_meta(get_the_ID(), "stpr_price", true)) / 100); ?> / mo.
                </div>
            </div>

            <div class="unit_detail_box">
                <div class="unit_detail">
                    Unit Features: 
                    <span>
                        <?php
                        $features = get_post_meta(get_the_ID(), "stpr_features", true);
                        if ($features) {
                            foreach ($features as $feature) {
                                ?>
                                <div class="feature_option">
                                    <?php echo esc_attr($feature); ?>
                                </div>
                                <?php
                            }
                        }
                        else{
                            ?> <span>No features for this unit!</span><?php
                        }
                        ?>
                    </span>
                </div>

            </div>
            
            <div class="unit_detail_box" style="float: right;">
                <div class="unit_detail">
                    Inquirer: <?php echo esc_attr($inquirer->display_name); ?> (<a href="mailto:<?php echo esc_attr($inquirer->user_email) ?>">Contact</a>)
                </div>
                <div class="unit_detail">
                    <form action="" method="POST">
                        <?php wp_nonce_field('approve_deny_reservation_inquiry', 'approve_deny_reservation_inquiry_nonce'); ?>
                        <input type="hidden" name="reserver_id" value="<?php echo esc_attr($inquirer_id); ?>">
                        <input type="hidden" name="unit_id" value="<?php echo esc_attr(get_the_ID()); ?>">
                        <input name="approve" class="action_btn approve_btn" type="submit" value="Approve">
                        <input name="deny" class="action_btn deny_btn" type="submit" value="Deny">
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
} else{
    ?>
    <div class="postbox postbox-card">
        <h2>No Reservation Inquiries</h2>
    </div>
    <?php

}
?>