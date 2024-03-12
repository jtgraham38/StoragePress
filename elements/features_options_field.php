<?php 
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

features options
<?php
$options = get_option('storagepress_feature_options');
if(!empty($options)) {
    foreach($options as $key => $value) { ?>
        <input type="text" name="storagepress_feature_options[]" value="<?php echo $value ?>"> <?php
    }
}