//wp is registered in the browser global scop by WordPress
wp.blocks.registerBlockType(
    "storagepress/storage-unit-meta-block", // Unique name of the block
    {
        title: "Storage Unit Meta", // title of the block
        icon: "smiley", // dashicon to show in the admin panel
        category: "typography", // category of the block
        attributes: {
            key: { type: "string" },
        },  // attributes of the block
        edit: function (props) {
            return (
                <span>
                    <select
                    value={props.attributes.key}
                    onChange={(event)=>{ 
                        props.setAttributes({ key: event.target.value })
                     }}>
                        <option value="sp_size">Size</option>
                        <option value="sp_price">Price</option>
                        <option value="sp_features">Features</option>
                        <option value="sp_available">Availability</option>
                    </select>
                </span>
            )
        }, // function to render the block in the editor (admin appearance)
        save: function (props) {
            return null // return null to dynomically render the block content with php
        }, // function to save the block content (front-end appearance)
    },  // configuration object for the block
)