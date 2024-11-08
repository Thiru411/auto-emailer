"use strict";

// Class definition
var KTModalProjectAdd = function () {
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
                    'name': {
						validators: {
							notEmpty: {
								message: 'Customer name is required'
							}
						}
					},
                    'email': {
						validators: {
							notEmpty: {
								message: 'Customer email is required'
							}
						}
					},
					'first-name': {
						validators: {
							notEmpty: {
								message: 'First name is required'
							}
						}
					},
					'last-name': {
						validators: {
							notEmpty: {
								message: 'Last name is required'
							}
						}
					},
					'country': {
						validators: {
							notEmpty: {
								message: 'Country is required'
							}
						}
					},
					'address1': {
						validators: {
							notEmpty: {
								message: 'Address 1 is required'
							}
						}
					},
					'city': {
						validators: {
							notEmpty: {
								message: 'City is required'
							}
						}
					},
					'state': {
						validators: {
							notEmpty: {
								message: 'State is required'
							}
						}
					},
					'postcode': {
						validators: {
							notEmpty: {
								message: 'Postcode is required'
							}
						}
					}
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
        $(form.querySelector('[name="country"]')).on('change', function() {
            // Revalidate the field when an option is chosen
            validator.revalidateField('country');
        });

		// Action buttons
		submitButton.addEventListener('click', function (e) {
			e.preventDefault();

			// Validate form before submit
			if (validator) {
				validator.validate().then(function (status) {
					console.log('validated!');

					if (status == 'Valid') {
						submitButton.setAttribute('data-kt-indicator', 'on');

						// Disable submit button whilst loading
						submitButton.disabled = true;
						var myArray=[];
						for(var i=1;i<count;i++) {
							var cust='project_email'+i;
							var custtemail=form.querySelector("[name="+cust+"]").value;
							if(custtemail!=undefined){
								myArray[i]=custtemail;
							}
						}
						const results = myArray.filter(element => {
							return element !== null;
						  });
						  var www='';
						setTimeout(function() {
							submitButton.removeAttribute('data-kt-indicator');
							axios.post(AJAX_URL+'organization/add_projects/', {
                                project_name: form.querySelector('[name="project_name"]').value, 
                                code: form.querySelector('[name="code"]').value, 
                                project_email: results,
                                format: form.querySelector('[name="format"]').value,
                                country: form.querySelector('[name="country"]').value, 
                                project_contact_num: www,
								pocontact_num: form.querySelector('[name="pocontact_num"]').value, 
								client: form.querySelector('[name="client"]').value, 
								owner: form.querySelector('[name="owner"]').value, 
								pname: form.querySelector('[name="pname"]').value, 
                                project_contac_alternative: form.querySelector('[name="project_contac_alternative"]').value,
                               
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
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_add_customer_form1'));
            form = document.querySelector('#kt_modal_add_user_form1');
            submitButton = form.querySelector('#kt_modal_add_customer_submit1');
			closeButton = form.querySelector('#kt_modal_add_customer_close1');
			cancelButton = form.querySelector('#kt_modal_add_customer_cancel111');
            handleForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
	KTModalProjectAdd.init();
});