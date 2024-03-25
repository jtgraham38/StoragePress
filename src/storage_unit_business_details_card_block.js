const InnerBlocks = wp.blockEditor; // InnerBlocks is registered in the browser global scop by WordPress

//wp is registered in the browser global scop by WordPress
export default wp.blocks.registerBlockType(
    "storagepress/storage-unit-business-details-card-block", // Unique name of the block
    {
        title: "Storage Unit Business Details Card", // title of the block
        icon: "vault", // dashicon to show in the admin panel
        category: "storagepress", // category of the block 
        attributes: {
            key: { type: "string", default: 'none' },    //once published, don't change the name of an attribute, could lead to errors with previously created blocks
        },  // attributes of the block
        edit: function (props) {
            return (
                <div>
                    <h1>Unit Business Details Card (TODO)</h1>
                    <InnerBlocks />
                </div>
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