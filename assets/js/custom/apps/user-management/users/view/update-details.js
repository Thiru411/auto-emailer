"use strict";

// Class definition
var KTUsersUpdateDetails = function () {
    // Shared variables
    const element = document.getElementById('kt_modal_update_details');
    const form = element.querySelector('#kt_modal_update_user_form');
    const modal = new bootstrap.Modal(element);

    // Init add schedule modal
    var initUpdateDetails = () => {

        // Close button handler
        const closeButton = element.querySelector('[data-kt-users-modal-action="close"]');
        closeButton.addEventListener('click', e => {
            e.preventDefault();

           
                    form.reset(); // Reset form	
                    modal.hide(); // Hide modal				
                
        });

        // Cancel button handler
        const cancelButton = element.querySelector('[data-kt-users-modal-action="cancel"]');
        cancelButton.addEventListener('click', e => {
            e.preventDefault();

           
                    form.reset(); // Reset form	
                    modal.hide(); // Hide modal				
                
        });

        // Submit button handler
        const submitButton = element.querySelector('[data-kt-users-modal-action="submit"]');
        submitButton.addEventListener('click', function (e) {
            // Prevent default button action
            e.preventDefault();

            // Show loading indication
            submitButton.setAttribute('data-kt-indicator', 'on');

            // Disable button to avoid multiple click 
            submitButton.disabled = true;

            // Simulate form submission. For more info check the plugin's official documentation: https://sweetalert2.github.io/
            setTimeout(function () {
                // Remove loading indication
                submitButton.removeAttribute('data-kt-indicator');
                wwww='';
                // Enable button
                submitButton.disabled = false;
                axios.post(AJAX_URL+'users/update_user/', {
                    avatar: wwww,
                    user_name: form.querySelector('[name="name"]').value, 
                    user_email: form.querySelector('[name="email"]').value,
                    user_address: form.querySelector('[name="address1"]').value, 
                    user_country: form.querySelector('[name="country"]').value,
                    user_description: form.querySelector('[name="description"]').value, 
                    user_language: form.querySelector('[name="language"]').value, 
                    user_address2: form.querySelector('[name="address2"]').value, 
                    user_town: form.querySelector('[name="city"]').value,
                    user_state: form.querySelector('[name="state"]').value ,
                    user_post: form.querySelector('[name="postcode"]').value, 
                    user_id: form.querySelector('[name="user_id"]').value, 
                }).then(function (response) {
                    var resp=response['data'];
                    if (resp['message']==true) {
                // Show popup confirmation 
                Swal.fire({
                    text: "Form has been successfully submitted!",
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                }).then(function (result) {
                    if (result.isConfirmed) {
                        modal.hide();
                    }
                });
            }
        })
            }, 2000);
        });
    }

    return {
        // Public functions
        init: function () {
            initUpdateDetails();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersUpdateDetails.init();
});