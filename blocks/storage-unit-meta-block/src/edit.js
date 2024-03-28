/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';
import { BlockControls, AlignmentToolbar } from '@wordpress/block-editor';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
const { InspectorControls } = wp.blockEditor;
const { PanelBody } = wp.components;
import { useSelect } from '@wordpress/data';
import { store as coreDataStore } from '@wordpress/core-data';
import { useEntityRecord, useEntityProp } from '@wordpress/core-data';	//gets a record of a post from the api
export default function Edit(props) {
	//set select input options
	const options = {
		'none': 'Choose a Unit Meta Detail...',
		'sp_size': 'Size',
		'sp_price': 'Price',
		'sp_features': 'Features',
		'sp_available': 'Availability',
	}

	//get meta if the context we are in is that of a storage unit
	if (props.context.postType == 'sp_storage_units'){
		//get the unit record from the db, then return it's meta object
		const meta = useSelect((select) => {
			const { getEditedEntityRecord } = select('core');
			const record = getEditedEntityRecord('postType', props.context.postType, props.context.postId);
			return record.meta;
		})
		console.log(meta)
	}

	//get content
	return (
		<>
			<InspectorControls>
				<PanelBody>
					<select
						value={props.attributes.key}
						onChange={(event)=>{ 
							props.setAttributes({ key: event.target.value })
					}}>
						{
							Object.entries(options).map(([value, label]) => (
								<option key={value} value={value}>
									{label}
								</option>
							))
						}
					</select>
				</PanelBody>
			</InspectorControls>

			<div { ...useBlockProps() }>
				{ options[props.attributes.key] }
			</div>
		</>
	);
}
