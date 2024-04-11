/**
 * Use this file for JavaScript code that you want to run in the front-end 
 * on posts/pages that contain this block.
 *
 * When this file is defined as the value of the `viewScript` property
 * in `block.json` it will be enqueued on the front end of the site.
 *
 * Example:
 *
 * ```js
 * {
 *   "viewScript": "file:./view.js"
 * }
 * ```
 *
 * If you're not making any changes to this file because your project doesn't need any 
 * JavaScript running in the front-end, then you should delete this file and remove 
 * the `viewScript` property from `block.json`. 
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/#view-script
 */
 
/* eslint-disable no-console */
/* eslint-enable no-console */
function submit_reserve_form(event) {
    event.preventDefault();

    //get form data
    const form = event.target;
    const form_data = new FormData(form);
    const data = Object.fromEntries(form_data);
    console.log(data);

    //check if api nonce value will be defined
    if (!window['wpApiSettings']){
        console.error('wpApiSettings not defined yet!');
        return;
    }

    //send data to server
    if (window['storagepress'].reserve_unit_route){
        fetch(window['storagepress'].reserve_unit_route, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': window['wpApiSettings'].nonce,
            },
            body: JSON.stringify(data),
        })
        .then(response => response.json())
        .then(response_data => {

            //handle success
            if (!response_data.code) {
                console.log('Success:', response_data);
                form.reset();
                window.alert(`Your reservation request has been submitted!`);
                window.location.reload();
            }

            //handle errors
            if (response_data.code && response_data.code === 'rest_forbidden_context') {
                console.error('You do not have permission to perform this action.');
            }
            else if (response_data.code && response_data.code === 'unit_already_reserved'){
                window.alert(`This unit is already reserved!`);
            }
            
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    } else {
        console.error('STORAGEPRESS_RESERVE_ROUTE not defined yet!');
    }
}

document.addEventListener('DOMContentLoaded', (event) => {
    const forms = Array.from(document.querySelectorAll('.storage-unit-reserve-form'));
    forms.map((form) => {
        form.addEventListener('submit', submit_reserve_form);
    });
});