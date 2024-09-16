"use strict";

// Class definition
var KTModalCustomersAdd = function () {
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
                    'user_name': {
						validators: {
							notEmpty: {
								message: 'Customer name is required'
							}
						}
					},
                    'poc': {
						validators: {
							notEmpty: {
								message: 'POC is required'
							}
						}
					},
					'pocnumber': {
						validators: {
							notEmpty: {
								message: 'POC Number required'
							}
						}
					},
					'customer_email1': {
						validators: {
							notEmpty: {
								message: 'Email is required'
							}
						}
					},
					'customer_address': {
						validators: {
							notEmpty: {
								message: 'Address is required'
							}
						}
					},
					'country_name': {
						validators: {
							notEmpty: {
								message: 'County is required'
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
        $(form.querySelector('[name="country"]')).on('change', function() {
            // Revalidate the field when an option is chosen
            validator.revalidateField('country');
        });
		
		// Action buttons
		submitButton.addEventListener('click', function (e) {
			
			e.preventDefault();
			if (validator) {
				validator.validate().then(function (status) {
					if (status == 'Valid') {
						var myArray=[];
		for(var i=1;i<count;i++) {
			var cust='customer_email'+i;
			var custtemail=form.querySelector("[name="+cust+"]").value;
			if(custtemail!=undefined){
				myArray[i]=custtemail;
			}
		}
		const results = myArray.filter(element => {
			return element !== null;
		  });
		 //myArray1= JSON.parse(results);

						submitButton.setAttribute('data-kt-indicator', 'on');
						submitButton.disabled = true;
						setTimeout(function() {
							submitButton.removeAttribute('data-kt-indicator');
							axios.post(AJAX_URL+'customers/add_customer/', {
								customer_email:results,
                                user_name: form.querySelector('[name="user_name"]').value, 
                                poc: form.querySelector('[name="poc"]').value, 
                                pocnumber: form.querySelector('[name="pocnumber"]').value,
                                customer_num: form.querySelector('[name="customer_num"]').value, 
                                customer_address: form.querySelector('[name="customer_address"]').value,
                                country_name: form.querySelector('[name="country_name"]').value,
                                address1: form.querySelector('[name="address1"]').value,
								address2: form.querySelector('[name="address2"]').value,
								city: form.querySelector('[name="city"]').value,
								state: form.querySelector('[name="state"]').value,
								postcode: form.querySelector('[name="postcode"]').value,
								country: form.querySelector('[name="country"]').value,
								billing: form.querySelector('[name="billing"]').value,
								owner: form.querySelector('[name="owner"]').value,
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
            modal = new bootstrap.Modal(document.querySelector('#kt_modal_add_customer'));

            form = document.querySelector('#kt_modal_add_customer_form');
            submitButton = form.querySelector('#kt_modal_add_customer_submit');
            cancelButton = form.querySelector('#kt_modal_add_customer_cancel');
			closeButton = form.querySelector('#kt_modal_add_customer_close');

            handleForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
	KTModalCustomersAdd.init();
});