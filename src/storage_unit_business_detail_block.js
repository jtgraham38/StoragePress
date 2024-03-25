
import { InspectorControls, useBlockProps } from '@wordpress/block-editor'; // wp block editor
import { PanelBody } from '@wordpress/components';

//wp is registered in the browser global scop by WordPress
export default wp.blocks.registerBlockType(
    "storagepress/storage-unit-business-detail-block", // Unique name of the block
    {
        title: "Storage Unit Business Detail", // title of the block
        icon: "vault", // dashicon to show in the admin panel
        category: "storagepress", // category of the block 
        attributes: {
            key: { type: "string", default: 'none' },    //once published, don't change the name of an attribute, could lead to errors with previously created blocks
        },  // attributes of the block
        edit: function (props) {
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

                    <span>
                        { storagepress_options[props.attributes.key] ? storagepress_options[props.attributes.key] : 'No detail selected!'}
                    </span>
                </>
            )
        }, // function to render the block in the editor (admin appearance)
        save: function (props) {
            return null // return null to dynomically render the block content with php
        }, // function to save the block content (front-end appearance)
    },  // configuration object for the block
)
//
////TODO: finish
//