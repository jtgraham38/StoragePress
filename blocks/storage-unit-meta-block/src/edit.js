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
export default function Edit(props) {
	//set select input options
	const options = {
		'none': 'Choose a Business Detail...',
		'sp_size': 'Size',
		'sp_price': 'Price',
		'sp_features': 'Features',
		'sp_available': 'Availability',
	}

	//get meta
	console.log(props.context)
	if (props.context.postType == 'sp_storage_units'){
		/*
		TODO: props.context gives the post type and post id of the current post, including context-based in the
		query loop.  However, I cannot get the metadata yet.  I need to figure out how to get the metadata.
		*/
		const result = useSelect(
			(select) =>{
				const { getEditedEntityRecord, getUser } = select( coreStore );
				const record = getEditedEntityRecord( 'postType', 'sp_storage_units', props.context.postId );
				console.log("record", record)
			}
		);
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
