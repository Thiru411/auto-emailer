"use strict";

// Class definition
var KTUsersAddUser = function () {
    // Shared variables
    const element = document.getElementById('kt_modal_add_user');
    const form = element.querySelector('#kt_modal_add_user_form');
    const modal = new bootstrap.Modal(element);

    // Init add schedule modal
    var initAddUser = () => {

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'user_name': {
                        validators: {
                            notEmpty: {
                                message: 'Full name is required'
                            }
                        }
                    },
                    'user_mobile': {
                        validators: {
                            notEmpty: {
                                message: 'Mobile Number is required'
                            }
                        }
                    },
                    'user_address': {
                        validators: {
                            notEmpty: {
                                message: 'Address is required'
                            }
                        }
                    },
                    // 'user_country': {
                    //     validators: {
                    //         notEmpty: {
                    //             message: 'Select County is required'
                    //         }
                    //     }
                    // },
                    'user_email': {
                        validators: {
                            notEmpty: {
                                message: 'Valid email address is required'
                            }
                        }
                    },
                },

                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );

        // Submit button handler
        const submitButton = element.querySelector('[data-kt-users-modal-action="submit"]');
        submitButton.addEventListener('click', e => {
            e.preventDefault();

            // Validate form before submit
            if (validator) {
                validator.validate().then(function (status) {
                    console.log('validated!');

                    if (status == 'Valid') {
                        // Show loading indication
                        submitButton.setAttribute('data-kt-indicator', 'on');

                        // Disable button to avoid multiple click 
                        submitButton.disabled = true;

                        // Simulate form submission. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                        setTimeout(function () {
                            // Remove loading indication
                            submitButton.removeAttribute('data-kt-indicator');

                            // Enable button
                            submitButton.disabled = false;
                            var wwww='';
                            axios.post(AJAX_URL+'users/add_user/', {
                                user_name: form.querySelector('[name="user_name"]').value, 
                                avatar: wwww,
                                user_name: form.querySelector('[name="user_name"]').value, 
                                user_email: form.querySelector('[name="user_email"]').value,
                                user_mobile: form.querySelector('[name="user_mobile"]').value,
                                user_address: form.querySelector('[name="user_address"]').value, 
                                user_country: form.querySelector('[name="user_country"]').value,
                                role: form.querySelector('[name="roles"]').value,
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
                                    submitButton.disabled = false;

									// Redirect to customers list page
									window.location = form.getAttribute("data-kt-redirect");
                                }
                            });

                            //form.submit(); // Submit form
                        }
                    } )}, 2000);
                    } 
                });
            }
        });

        // Cancel button handler
        const cancelButton = element.querySelector('[data-kt-users-modal-action="cancel"]');
        cancelButton.addEventListener('click', e => {
            e.preventDefault();

                    form.reset(); // Reset form			
                    modal.hide();	
                
            });

        // Close button handler
        const closeButton = element.querySelector('[data-kt-users-modal-action="close"]');
        closeButton.addEventListener('click', e => {
            e.preventDefault();

           
                    form.reset(); // Reset form			
                    modal.hide();	
               
            });
    }

    return {
        // Public functions
        init: function () {
            initAddUser();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersAddUser.init();
});