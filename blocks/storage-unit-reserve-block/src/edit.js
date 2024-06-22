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
import { useSelect } from '@wordpress/data';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit(props) {

	// //get post data if the context we are in is that of a storage unit
	// const db_record = useSelect((select) => {
	// 	if (props.context.postType == 'storagepress_unit') {
    //         const { getEditedEntityRecord } = select('core');
	// 		const record = getEditedEntityRecord('postType', props.context.postType, props.context.postId);
	// 		return record;
    //     }
    //     return {};
	// })

	// //format metadata for outputting
	// console.log(db_record)

	//jsx body
	return (
		<>
			<div>
				<span { ...useBlockProps() } className='storagepress-reserve-button'>
					Reserve
				</span>
			</div>
		</>
	);
}
