<?php
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>
<div>
    <?php 
        $tenant_id = get_post_meta(get_the_ID(), "sp_tenant", true);
        $inquirer_id = get_post_meta(get_the_ID(), "sp_reservation_inquirer", true);

        // var_dump($tenant_id);
        // echo "<br>";
        // var_dump($inquirer_id);
        if ($inquirer_id || $tenant_id){
    ?>
        <span <?php echo get_block_wrapper_attributes(array('class'=>'storagepress-reserve-button')) ?>>
            <?php
                if ($inquirer_id){
                    echo "Pending";
                }else{
                    echo "Occupied";
                }
            ?>
        </span>
    <?php
        } else {
    ?>

        <span <?php echo get_block_wrapper_attributes(array('class'=>'storagepress-reserve-button')) ?> onclick="storagepress_reserve_unit_<?php the_ID(); ?>_modal.showModal()">
            Reserve
        </span>

        <dialog id="storagepress_reserve_unit_<?php the_ID(); ?>_modal">
            <button onclick="storagepress_reserve_unit_<?php the_ID(); ?>_modal.close()" class="storagepress-reserve-modal-close-button">&times;</button>
            <h3>Reserve Storage Unit "<?php the_title() ?>"</h3>
            <hr>
            <?php 
            if (!is_user_logged_in()) {
                // get login page url
                $register_url = wp_registration_url();
                $login_url = wp_login_url(get_permalink());
                
                ?>
                <p>You need to be logged in to reserve a storage unit. <a href="<?php echo esc_url($login_url); ?>">Login</a> or <a href="<?php echo esc_url($register_url) ?>">Sign Up</a> </p>
                <?php
            }else{
                //get reserve request api route
                $current_user = wp_get_current_user();
                ?>
                <form class="storage-unit-reserve-form">
                    <?php //no nonce field neede here because the window api script's nonce is added to the ajax request on submit ?> 
                    <input type="hidden" name="unit_id" value="<?php the_ID(); ?>">
                    <div>
                        <label for="reserve_unit_<?php the_ID(); ?>_name_input" style="display: block;">Your Name</label>
                        <input name="name" value="<?php echo esc_attr($current_user->display_name); ?>" class="storagepress_text_input" type="text" id="reserve_unit_<?php the_ID(); ?>_name_input" placeholder="Name">
                    </div>
                    <div style="margin-bottom: 0.25rem;">
                        <label for="reserve_unit_<?php the_ID(); ?>_email_input" style="display: block;">Your Email</label>
                        <input name="email" value="<?php echo esc_email($current_user->user_email); ?>" class="storagepress_text_input" type="email" id="reserve_unit_<?php the_ID(); ?>_email_input" placeholder="Email">
                    </div>
                    <input type="submit" value="Submit" <?php echo get_block_wrapper_attributes(array('class'=>'storagepress-reserve-button')) ?>>
                </form>
                <?php
            }
            ?>

            <?php
                if (get_option('storagepress_display_credit_link')){
                    ?>
                    <a target="#blank" href="https://jacob-t-graham.com/category/wordpress-development/storagepress/" style="float: right;">JG</a>
                    
                <?php } ?>

        </dialog>

    <?php
        }
    ?>
</div>