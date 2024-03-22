//wp is registered in the browser global scop by WordPress
wp.blocks.registerBlockType(
    "storagepress/storage-unit-meta-block", // Unique name of the block
    {
        title: "Storage Unit Meta Block", // title of the block
        icon: "smiley", // dashicon to show in the admin panel
        category: "typography", // category of the block
        edit: function () {
            return wp.element.createElement(
                "p",
                null,
                "Hello Editor"
            );
        }, // function to render the block in the editor (admin appearance)
        save: function () {
            return wp.element.createElement(
                "p",
                null,
                "Hello Saved Content"
            );
        }, // function to save the block content (front-end appearance)
    },  // configuration object for the block
)