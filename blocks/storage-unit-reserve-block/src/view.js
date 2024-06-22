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

//add notification message to the page

document.addEventListener('DOMContentLoaded', (event) => {

    //get the message
    message = window['storagepress']?.reserve_status_notification;

    //if message is not found, create it
    if (!message){
        message = document.createElement('div');
        message.id = 'storage_unit_reserve_notification';
        message.classList.add('storagepress_notification_hidden')
        document.body.appendChild(message);
        window['storagepress'].reserve_status_notification = message;

        //add p tag to notifiaction to hold message
        const body = document.createElement('div');
        body.id = 'storage_unit_reserve_notification_body';
        message.appendChild(body);
        body.innerText = "( ͡° ͜ʖ ͡°)"

        //add x button to the message
        const close_button = document.createElement('button');
        close_button.id = 'storage_unit_reserve_notification_close';
        close_button.innerHTML = '&times;';
        close_button.onclick = function() {
            message.classList.add('storagepress_notification_hidden');
        };
        message.appendChild(close_button);

    }

    
    
});

function storagepress_show_message(message) {
    //show the message on the message element
    const message_element = document.getElementById('storage_unit_reserve_notification');
    if (message_element){
        message_element.querySelector('#storage_unit_reserve_notification_body').innerText = message;
        message_element.classList.remove('storagepress_notification_hidden');
        return
    }

    //handle if message element not found
    console.error('Message element not found!');
}

function storagepress_submit_reserve_form(event) {
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
                storagepress_show_message('Your reservation request has been submitted! Refreshing...');
                setTimeout(function() { //TODO: this needs to be improved
                    window.location.reload();
                }, 5000);
            }

            //handle errors
            if (response_data.code && response_data.code === 'rest_forbidden_context') {
                console.error('You do not have permission to perform this action.');
                storagepress_show_message('You do not have permission to perform this action.');
            }
            else if (response_data.code && response_data.code === 'user_has_active_inquiries') {
                console.error(`You already have an active inquiry!`);
                storagepress_show_message('You already have an active inquiry!');
            }
            else if (response_data.code && response_data.code === 'unit_already_reserved'){
                console.error(`This unit is already reserved!`);
                storagepress_show_message('This unit is already reserved!');
            }
            else if (response_data.code && response_data.code === 'unit_already_rented'){
                console.error(`This unit is already rented!`);
                storagepress_show_message('This unit is already rented!');
            }

            //close modal
            event.target.parentNode.close();
            
            
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
        form.addEventListener('submit', storagepress_submit_reserve_form);
    });
});