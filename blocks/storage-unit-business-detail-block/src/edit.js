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

export default function Edit(props) {
	return (
		<>
			<InspectorControls>
                <PanelBody>
                    <select
                        value={props.attributes.key}
                        onChange={(event)=>{ 
                            props.setAttributes({ key: event.target.value })
                    }}>
                        <option value="none">Choose a Business Detail...</option>
                        <option value="storagepress_name">Business Name</option>
                        <option value="storagepress_address">Business Address</option>
                        <option value="storagepress_phone">Business Phone Number</option>
                        <option value="storagepress_email">Business Email</option>
                        <option value="storagepress_rental_terms">Business Rental Terms</option>
                        <option value="storagepress_checks_payable_to">Make Checks Payable To</option>
                        <option value="storagepress_listing_page">Link to Listing Page</option>
                    </select>
                </PanelBody>
            </InspectorControls>

            <span { ...useBlockProps() }>
                { props.attributes.key }
            </span>
		</>
	);
}
