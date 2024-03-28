export default function Edit (props) {
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
                { storagepress_options[props.attributes.key] ? storagepress_options[props.attributes.key] : 'No detail selected!'}
            </span>
        </>
    )
} // function to render the block in the editor (admin appearance)