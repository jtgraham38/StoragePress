<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>
<?php 
//if an option was chosen in the input...
global $post;
if ($post->post_type != 'sp_storage_units'){
	echo '<span>Post of type "' . $post->post_type . '" is not a Storage Unit.</span> ';
} else{
	//for some reason, the key attr will not be set if the input is not changed
	if (array_key_exists('key', $attributes)){
		$output = '';
		switch($attributes['key']){
			case 'sp_size':
				$output = get_post_meta($post->ID, 'sp_length', true) . ' ' . get_post_meta($post->ID, 'sp_unit', true) . ' &times; ' . get_post_meta($post->ID, 'sp_width', true) . ' ' . get_post_meta($post->ID, 'sp_unit', true);
				break;
			case 'sp_price':
				$output = '$' . (get_post_meta($post->ID, 'sp_price', true) / 100) . "/mo.";
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
					?>
					<style>
						.feature_tag{
							padding: 0.25rem;
							margin: 0.25rem;
							border: 1px solid #8c8f94;
							border-radius: 0.5rem;
							font-size: smaller;
						}
					</style>
					<?php
					//display features
					foreach($features[0] as $feature){
						$output .= '<span class="feature_tag">' . $feature . '</span> '; 
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
		echo '<span ' . get_block_wrapper_attributes() . '>' . $output . '</span>';
	}
	//otherwise, display default content
	else{
		echo '<span>(No Storage Unit Meta Chosen)</span>';
	}
}