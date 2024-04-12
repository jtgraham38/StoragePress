<?php
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

//query for all storage units which have an inquirer
$inquirer_query = new WP_Query(array(
    'post_type' => 'sp_storage_units',
    'meta_query' => array(
        array(
            'key' => 'sp_reservation_inquirer',
            'value' => '',
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
                    <button class="action_btn approve_btn">Approve</button>
                    <button class="action_btn deny_btn">Deny</button>
                </div>
            </div>
        </div>
        <?php
    }
}
?>

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