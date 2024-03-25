import { InspectorControls, useBlockProps } from '@wordpress/block-editor'; // wp block editor
import { PanelBody } from '@wordpress/components';

//used for querying the database
import { useSelect } from '@wordpress/data';

//wp is registered in the browser global scop by WordPress
export default wp.blocks.registerBlockType(
    "storagepress/storage-unit-meta-block", // Unique name of the block
    {
        title: "Storage Unit Meta", // title of the block
        icon: "vault", // dashicon to show in the admin panel
        category: "storagepress", // category of the block
        attributes: {
            key: { type: "string", default: 'none' },    //once published, don't change the name of an attribute, could lead to errors with previously created blocks
        },  // attributes of the block
        edit: function (props) {
            const labels = {
                none: "None Selected",
                sp_size: "Size",
                sp_price: "Price",
                sp_features: "Features",
                sp_available: "Availability",
            }
            //query the database for the storage unit meta
            // TODO: fix this
            // const meta = useSelect((select) => {
            //     const { getEditedPostAttribute } = select('core/editor');
            //     const postId = getEditedPostAttribute('id');
            
            //     const meta = select('core').getEntityRecords('sp_storage_units', 'post', { include: [postId] })[0].meta;
            
            //     const unit_meta = {
            //         sp_size: meta.storage_unit_size,
            //         sp_price: meta.storage_unit_price,
            //         sp_features: meta.storage_unit_features,
            //         sp_available: meta.storage_unit_available,
            //     };
            // }, []);
            //console.log("meta", meta)
            return (
                

                <>
                    <InspectorControls>
                        <PanelBody>
                            <select
                                value={props.attributes.key}
                                onChange={(event)=>{ 
                                    props.setAttributes({ key: event.target.value })
                                }}>
                                    <option value="none">Choose a Unit Meta...</option>
                                    <option value="sp_size">Size</option>
                                    <option value="sp_price">Price</option>
                                    <option value="sp_features">Features</option>
                                    <option value="sp_available">Availability</option>
                            </select>
                        </PanelBody>
                    </InspectorControls>

                    <span>
                        { labels[props.attributes.key] } here
                    </span>
                </>
            )
        }, // function to render the block in the editor (admin appearance)
        save: function (props) {
            return null // return null to dynomically render the block content with php
        }, // function to save the block content (front-end appearance)
    },  // configuration object for the block
)