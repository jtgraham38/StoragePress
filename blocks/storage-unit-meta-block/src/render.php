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
//if an option was chosen in the input...
global $post;
if ($post->post_type != 'sp_storage_units'){
	echo '<div>Post of type "' . $post->post_type . '" is not a Storage Unit.</div> ';
} else{
	//for some reason, the key attr will not be set if the input is not changed
	if (array_key_exists('key', $attributes)){
		$output = '';
		switch($attributes['key']){
			case 'sp_size':
				$output = esc_attr(get_post_meta($post->ID, 'sp_length', true)) . ' ' . esc_attr(get_post_meta($post->ID, 'sp_unit', true)) . ' &times; ' . esc_attr(get_post_meta($post->ID, 'sp_width', true)) . ' ' . esc_attr(get_post_meta($post->ID, 'sp_unit', true));
				break;
			case 'sp_price':
				$output = '$' . (esc_attr((int)get_post_meta($post->ID, 'sp_price', true) / 100)) . "/mo.";
				break;
			case 'sp_available':
				$tenant = get_post_meta($post->ID, 'sp_tenant', true);
				if ($tenant == 0){
					$output = 'Available';
				}else{
					$output = 'Rented';
				}
				break;
			case 'sp_features':
				$features = get_post_meta($post->ID, 'sp_features', false);
				if (count($features) > 0){
					//feature tag style

					//TODO: the style below will be echoed for each block of this type, migrate it to use more official block styles instead
					?>
					<?php
					//display features
					foreach($features[0] as $feature){
						$output .= '<span class="sp_feature_tag">' . esc_attr($feature) . '</span> '; 
					}
				}
				else{
					$output = 'No features!';
				}
				break;
			default:
				$output = '(Invalid Storage Unit Meta Chosen)'; 
		}

		//display the output
		echo '<div ' . get_block_wrapper_attributes() . '>' . $output . '</div>';
	}
	//otherwise, display default content
	else{
		echo '<div>(No Storage Unit Meta Chosen)</div>';
	}
}