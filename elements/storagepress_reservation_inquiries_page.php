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
        <div>
            <h4>Reservation Inquiry for Storage Unit "<?php the_title() ?>"</h4>
            <p>
                <strong>Name:</strong> <?php echo $inquirer->display_name; ?>
            </p>
            <p>
                <strong>Email:</strong> <?php echo $inquirer->user_email; ?>
            </p>
            <hr>
        </div>
        <?php
    }
}
?>

