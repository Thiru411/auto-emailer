"use strict";

// Class definition
var KTModalalertAdd = function () {
    var submitButton;
    var cancelButton;
	var closeButton;
    var validator;
    var form;
    var modal;

    // Init form inputs
    var handleForm = function () {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
		validator = FormValidation.formValidation(
			form,
			{
				fields: {
                    'frequency': {
						validators: {
							notEmpty: {
								message: 'frequency is required'
							}
						}
					},
                    'start_date': {
						validators: {
							notEmpty: {
								message: 'start date  is required'
							}
						}
					},
					'end_date': {
						validators: {
							notEmpty: {
								message: 'End date is required'
							}
						}
					},
					'alert_message': {
						validators: {
							notEmpty: {
								message: 'alert message is required'
							}
						}
					},
					'pdfattach': {
						validators: {
							notEmpty: {
								message: 'pdfattach is required'
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

		// Revalidate country field. For more info, plase visit the official plugin site: https://select2.org/
        $(form.querySelector('[name="to"]')).on('change', function() {
            // Revalidate the field when an option is chosen
            validator.revalidateField('to');
        });
        $(form.querySelector('[name="cc"]')).on('change', function() {
            // Revalidate the field when an option is chosen
            validator.revalidateField('cc');
        });
        $(form.querySelector('[name="customer"]')).on('change', function() {
            // Revalidate the field when an option is chosen
            validator.revalidateField('customer');
        });
        $(form.querySelector('[name="projects"]')).on('change', function() {
            // Revalidate the field when an option is chosen
            validator.revalidateField('projects');
        });

		// Action buttons
		submitButton.addEventListener('click', function (e) {
			e.preventDefault();
            console.log(validator);
			// Validate form before submit
			if (validator) {
				validator.validate().then(function (status) {
					console.log('validated!');
					if (status == 'Valid') {
						submitButton.setAttribute('data-kt-indicator', 'on');

						// Disable submit button whilst loading
						submitButton.disabled = true;

						setTimeout(function() {
							submitButton.removeAttribute('data-kt-indicator');
                            var to = [];
                            for (var option of document.getElementById('to').options)
                                {
                                    if (option.selected) {
                                        to.push(option.value);
                                    }
                                }
                                var cc = [];
                                for (var option of document.getElementById('cc').options)
                                    {
                                        if (option.selected) {
                                            cc.push(option.value);
                                        }
                                    }
                                    var www='';
							axios.post(AJAX_URL+'client/add_alerts/', {
                                to: to, 
                                cc: cc, 
                                subject: www,
                                customer: form.querySelector('[name="customer"]').value,
                                projects: form.querySelector('[name="projetcs"]').value, 
                                frequency: www,
                                start_date: form.querySelector('[name="start_date"]').value, 
                                end_date: www, 
                                alert_message: form.querySelector('[name="alert_message"]').value,
                                pdfattach: www
                               
                            }).then(function (response) {
                                var resp=response['data'];
                                if (resp['message']==true) {
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
									// Hide modal
									modal.hide();

									// Enable submit button after loading
									submitButton.disabled = false;

									// Redirect to customers list page
									window.location = form.getAttribute("data-kt-redirect");
								}
							});							
						}
					}
					)}, 2000);   						
					}
				});
			}
		});

        cancelButton.addEventListener('click', function (e) {
            e.preventDefault();

            
                    form.reset(); // Reset form	
                    modal.hide(); // Hide modal				
        });

		closeButton.addEventListener('click', function(e){
			e.preventDefault();
          
                    form.reset(); // Reset form	
                    modal.hide(); // Hide modal				
               
		})
    }

    return {
        // Public functions
        init: function () {
            // Elements
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_add_user'));
            form = document.querySelector('#kt_modal_add_user_form1');
            submitButton = form.querySelector('#kt_modal_add_customer_submit1');
            cancelButton = form.querySelector('#kt_modal_add_customer_cancel1');
			closeButton = form.querySelector('.add__alert__CrossMark');
            handleForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
	KTModalalertAdd.init();
});