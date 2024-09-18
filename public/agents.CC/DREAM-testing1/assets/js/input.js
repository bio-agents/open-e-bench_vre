var baseURL = $("#base-url").val(); 
		 
var ValidateForm = function() { 
		 
 var handleForm = function() { 
		 
	$('#agent-input-form').validate({ 
		errorElement: 'span', //default input error message container 
		errorClass: 'help-block', // default input error message class 
		focusInvalid: false, // do not focus the last invalid input 
		ignore: [], 
		rules: { 
		    project: { 
			required: true, 
			nowhitespace: true 
		    }, 
		    execution: { 
			required: true, 
			nowhitespace: true 
		    } 
		}, 
		messages: { 
		    project: { 
		 	required: "Please select in which project you will execute this agent."
		     },
		     execution: {
		 	required: "The execution name is mandatory."
		     }
		},
	 	invalidHandler: function(event, validator) { //display error alert on form submit
		    $('.err-agent', $('#agent-input-form')).show();
		    $('.warn-agent', $('#agent-input-form')).hide();
	 	},
		highlight: function(element) { // hightlight error inputs
			$(element)
	 		.closest('.form-group').addClass('has-error'); // set error class to the control group
	 	},
	 	success: function(label, e) {
			$(e).parent().removeClass('has-error');
			$(e).parent().parent().parent().removeClass('has-error');
	 	},
	 	errorPlacement: function(error, element) {
		    if($(element).hasClass("select2-hidden-accessible")) {
			error.insertAfter($(element).parent().find("span.select2"));
		    } else {
			error.insertAfter(element);
		    }
	 	},
	 	submitHandler: function(form) {
		    $('button[type="submit"]', $('#agent-input-form')).prop('disabled', true);
		    $('button[type="submit"]', $('#agent-input-form')).html('<i class="fa fa-spinner fa-pulse fa-spin"></i> Launching agent, please don\'t close the tab.');
		    $('.warn-agent', $('#agent-input-form')).hide();
		    $('.err-agent', $('#agent-input-form')).hide();
		    var data = $('#agent-input-form').serialize();
		    data = data.replace(/%5B/g,"[");
		    data = data.replace(/%5D/g,"]");
		    //var data = $('#agent-input-form').serialize();
		    //console.log(data);
		    location.href = baseURL + "applib/launchAgent.php?" + data;

		}
	});

	// rules by ID instead of NAME
	$(".field_required").each(function() {
	    $(this).rules("add", { 
	    	required:true 
	    });
	});

	$('#agent-input-form').keypress(function(e) {
	    if (e.which == 13) {
		if ($('#agent-input-form').validate().form()) {
		    $('#agent-input-form').submit(); //form validation success, call ajax form submit
		}
		return false;
	    }
	});
 }

 return {
	//main function to initiate the module
	init: function() {
	 handleForm();
	}

 };

}();

jQuery(document).ready(function() {
 ValidateForm.init();
});
