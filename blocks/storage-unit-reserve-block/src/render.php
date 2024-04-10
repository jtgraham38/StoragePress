<?php
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>
<div>
    <span <?php echo get_block_wrapper_attributes(array('class'=>'storagepress-reserve-button')) ?> onclick="storagepress_reserve_unit_<?php the_ID(); ?>_modal.showModal()">
        Reserve
    </span>

    <dialog id="storagepress_reserve_unit_<?php the_ID(); ?>_modal">
        <button onclick="storagepress_reserve_unit_<?php the_ID(); ?>_modal.close()" class="storagepress-reserve-modal-close-button">&times;</button>
        <h4>Reserve Storage Unit "<?php the_title() ?>"</h4>
        <hr>
        <?php 
        if (!is_user_logged_in()) {
            // get login page url
            $register_url = wp_registration_url();
            $login_url = wp_login_url(get_permalink());
            
            ?>
            <p>You need to be logged in to reserve a storage unit. <a href="<?php echo $login_url; ?>">Login</a> or <a href="<?php echo $register_url?>">Sign Up</a> </p>
            <?php
        }else{
            //get reserve request api route
            $api_route = get_rest_url(null, 'storagepress/v1/reserve-unit');
            $current_user = wp_get_current_user();
            ?>
            <form action="<?php echo $api_route; ?>" method="POST">
                <div>
                    <label for="reserve_unit_<?php the_ID(); ?>_name_input" style="display: block;">Your Name</label>
                    <input name="name" value="<?php echo $current_user->display_name; ?>" type="text" id="reserve_unit_<?php the_ID(); ?>_name_input" placeholder="Name">
                </div>
                <div>
                    <label for="reserve_unit_<?php the_ID(); ?>_email_input" style="display: block;">Your Email</label>
                    <input name="email" value="<?php echo $current_user->user_email; ?>" type="email" id="reserve_unit_<?php the_ID(); ?>_email_input" placeholder="Email">
                </div>
                <input type="submit" value="Submit" <?php echo get_block_wrapper_attributes(array('class'=>'storagepress-reserve-button')) ?>>
            </form>
            <?php
        }
        ?>
    </dialog>
</div>