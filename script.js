//
//	jQuery Validate example script
//
//	Prepared by David Cochran
//
//	Free for your use -- No warranties, no guarantees!
//

$(document).ready(function(){

	// Validate
	// http://bassistance.de/jquery-plugins/jquery-plugin-validation/
	// http://docs.jquery.com/Plugins/Validation/
	// http://docs.jquery.com/Plugins/Validation/validate#toptions

		$('#contact-form').validate({
	    rules: {
	      name: {
	        minlength: 3,
	        required: true
	      },
	      email: {
	        required: true,
	        email: true
	      },
	      Password: {
			required: true,
	      	minlength: 5
	      },
		  Password_again: {
			required: true,
			minlength: 5,
			equalTo: "#Password"
	      },

	      language: {
	        selectcheck: true
	      },
		  category: {
	        categorycheck: true
	      },
		  company: {
	        required: true
	      },
		  telephone: {
			number: true,
	        required: true
	      },
		  city: {
	        required: true
	      },
		  address: {
	        required: true
	      },
		  fullname: {
	        required: true
	      },
		  agree: "required"
	    },
		messages: {
			Password_again: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long",
				equalTo: "Please enter the same password as above"
			},
			agree: "Please accept our policy"
		},
			highlight: function(element) {
				$(element).closest('.elements').removeClass('success').addClass('error');
			},
			success: function(element) {
				element
				.text('OK!').addClass('valid')
				.closest('.elements').removeClass('error').addClass('success');
			}
	  });
		jQuery.validator.addMethod('selectcheck', function (value) {
			return (value != '0');
		}, "Please choice language");
		jQuery.validator.addMethod('categorycheck', function (value) {
			return (value != '0');
		}, "Please choice Category");
		jQuery.validator.addMethod('agree', function (value) {
			return (value != '0');
		}, "Please agree");


}); // end document.ready