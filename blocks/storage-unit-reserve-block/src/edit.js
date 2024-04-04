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
import { useState } from '@wordpress/element';
import { Modal } from '@wordpress/components';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit(props) {
	const [ is_open, set_is_open ] = useState(false);
	const open_modal = () => set_is_open(true);
	const close_modal = () => set_is_open(false);

	return (
		<>
			<div>
				<span { ...useBlockProps() } onClick={open_modal} className='storagepress-reserve-button'>
					Reserve
				</span>

				{ is_open && (
					<Modal
						title="Reserve"
						onRequestClose={close_modal}
					>
						<div>
							// Your modal content goes here
						</div>
					</Modal>
				)}
			</div>
		</>
	);
}
