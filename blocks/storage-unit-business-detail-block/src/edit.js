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

/* 
other imports, by me
*/
import apiFetch from '@wordpress/api-fetch';
import { useState, useEffect } from '@wordpress/element';


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
    const options = {
        'none': 'Choose a Business Detail...',
        'storagepress_name': 'Business Name',
        'storagepress_address': 'Business Address',
        'storagepress_phone': 'Business Phone Number',
        'storagepress_email': 'Business Email',
        'storagepress_rental_terms': 'Business Rental Terms',
        'storagepress_checks_payable_to': 'Make Checks Payable To'
    }

    const [settings, set_settings] = useState(options)

    //get settings to display in the editor
    useEffect(() => {
        apiFetch( { path: '/storagepress/v1/business-details' } )
        .then( (response) => {
            delete response['storagepress_listing_page']
            response['none'] = 'Choose a Business Detail...'
            set_settings(response);
        } )
    }, [])
	

	return (
		<>
			<InspectorControls>
                <PanelBody>
                    <label>Choose a Business Detail</label>
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
                { settings[props.attributes.key] }
            </div>
		</>
	);
}
