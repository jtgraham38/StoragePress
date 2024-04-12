<?php
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

//handle reservation inquiry actions
if (isset($_POST['approve'])) {
    $reserver_id = $_POST['reserver_id'];
    $unit_id = $_POST['unit_id'];
    update_post_meta($unit_id, "sp_tenant", $reserver_id);
    update_post_meta($unit_id, "sp_reservation_inquirer", "0"); //must set it to 0, not "" or null
} else if (isset($_POST['deny'])) {
    $unit_id = $_POST['unit_id'];
    update_post_meta($unit_id, "sp_reservation_inquirer", "0"); //must set it to 0, not "" or null
}


//query for all storage units which have an inquirer
$inquirer_query = new WP_Query(array(
    'post_type' => 'sp_storage_units',
    'meta_query' => array(
        array(
            'relation' => 'AND',
            array(
                'key' => 'sp_reservation_inquirer',
                'compare' => 'EXISTS'
            ),
            'key' => 'sp_reservation_inquirer',
            'value' => '0',
            'compare' => '!='
        )
    )
));

//output all inquiries
if ($inquirer_query->have_posts()) {
    while ($inquirer_query->have_posts()) {
        $inquirer_query->the_post();
        $inquirer_id = get_post_meta(get_the_ID(), "sp_reservation_inquirer", true);
        $inquirer = get_userdata($inquirer_id);
        ?>
        <div class="postbox postbox-card clearfix">
            <div>
                <h2>Reservation Inquiry for Storage Unit "<?php the_title() ?>"</h2>
            </div>

            <div class="unit_detail_box">
                <div class="unit_detail">
                    <?php
                    $length = get_post_meta(get_the_ID(), "sp_length", true);
                    $width = get_post_meta(get_the_ID(), "sp_width", true);
                    $unit = get_post_meta(get_the_ID(), "sp_unit", true);
                    ?>
                    Size: <?php echo $length; ?> <?php echo $unit; ?> &times; <?php echo $width; ?> <?php echo $unit; ?>
                </div>
                <div class="unit_detail">
                    Price: $<?php echo ((int)get_post_meta(get_the_ID(), "sp_price", true)) / 100; ?> / mo.
                </div>
            </div>

            <div class="unit_detail_box">
                <div class="unit_detail">
                    Unit Features: 
                    <span>
                        <?php
                        $features = get_post_meta(get_the_ID(), "sp_features", true);
                        if ($features) {
                            foreach ($features as $feature) {
                                ?>
                                <div class="feature_option">
                                    <?php echo $feature; ?>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </span>
                </div>

            </div>
            
            <div class="unit_detail_box" style="float: right;">
                <div class="unit_detail">
                    Inquirer: <?php echo $inquirer->display_name; ?> (<a href="mailto:<?php echo $inquirer->user_email ?>">Contact</a>)
                </div>
                <div class="unit_detail">
                    <form action="" method="POST">
                        <input type="hidden" name="reserver_id" value="<?php echo $inquirer_id; ?>">
                        <input type="hidden" name="unit_id" value="<?php echo get_the_ID(); ?>">
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

todo: notify user of successful reservation response
todo: update reserve button and status meta block labels to match or at least correspond

<style>
    .postbox-card{
        padding: 1.25rem;
        margin: 1rem;

    }

    .unit_detail_box{
        display: inline-block;
        min-width: 8rem;
        max-width: 45%;
        padding: 0 2rem;
    }   

    .unit_detail{
        margin-top: 0.75rem;
        font-size: 1.25rem;
    }
    
    .feature_option {
        display: inline-flex;
        padding: 0.2rem 0.4rem 0.2rem 0.4rem;
        align-items: center;
        border: 1px solid #ccc;
        border-radius: 0.5rem;
        box-shadow: 0 0.25rem 0.5rem #ccc;
        margin-top: 0.4rem;
        margin-right: 0.2rem;
    }

    .action_btn{
        padding: 0.4rem 0.8rem;
        border: none;
        border-radius: 0.5rem;
        color: white;
        font-size: larger;
    }

    .approve_btn{
        background-color: green;
    }

    .approve_btn:hover{
        background-color: darkgreen;
    }

    .deny_btn{
        background-color: red;
    }

    .deny_btn:hover{
        background-color: darkred;
    }

    /* clearfix */
    .clearfix::after {
        content: "";
        clear: both;
        display: table;
    }
</style>