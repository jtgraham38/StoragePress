<?php 
//exit if file is called directly
if (!defined('ABSPATH')) {
    exit;
}
//init global post variable
global $post;

//include header
get_header();
?>

<main id="main" class="site-main">
    <h1>Storage Unit Post Type single</h1>
    <?php var_dump($post); ?>
</main


<?php
//include footer and sidebar
get_sidebar();
?>
<footer>
    <?php get_footer(); ?>
</footer>
