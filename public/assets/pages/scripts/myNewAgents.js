var baseURL = $('#base-url').val();

var DataTableMyAgents = function() {

	var handleDataTableMyAgents = function() {

		var table = $('#my-new-agents');

		var oTable = table.dataTable({

				"lengthMenu": [
						[5, 15, 20, -1],
						[5, 15, 20, "All"] // change per page values here
				],

				"order": [
						[5, "desc"]
				],

				"columnDefs": [{
						'orderable': false,
						'targets': [0,1,2,3,4]
				}, 
				{ targets: [5], visible: false },
				],
			
		});

	}

	return {
        //main function to initiate the module
        init: function() {
            handleDataTableMyAgents();
        }

    };


}();

var SubmitAgent = function() {

	var handleSubmitAgent = function() {

		$('#submit-agent').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: [],
            rules: {
							comments:{
                    required: true
                }
            },
						messages: {
							
						},

            highlight: function(element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            success: function(label, e) {
                $(e).parent().removeClass('has-error');
                $(e).parent().parent().removeClass('has-error');
                $(e).parent().parent().parent().removeClass('has-error');
            },

            errorPlacement: function(error, element) {
            	if($(element).hasClass("select2-hidden-accessible")) {
            		console.log($(element).parent());
            		error.insertAfter($(element).parent().find("span.select2"));
							} else {
								error.insertAfter(element);
							}
						},

            submitHandler: function(form) {
                  /*var data = $('#submit-agent').serialize();
                	console.log(data);*/
							form.submit();
            }
        });

	}

	return {
        //main function to initiate the module
        init: function() {
            handleSubmitAgent();
        }

    };


}();

var UploadLogo = function() {

	var handleUploadLogo = function() {

		$("input:file").change(function (){
			var agentid = $(this).attr('id').substr(10);
			$("#uplogo_" + agentid).submit();
     });

	}

	return {
        //main function to initiate the module
        init: function() {
            handleUploadLogo();
        }

    };


}();

function submitAgent(agentid) {

	$("#agentid-modal").val(agentid);

	$('#modalSubmitAgent').modal({ show: 'true' });
	$('#modalSubmitAgent .modal-title').html('Submit Agent <strong>' + agentid + '</strong>');
	$('#modalSubmitAgent .modal-body #st-title').html('You are about to submit the <strong>' + agentid + '</strong> agent, please fill the comments to send a message to our technical team');

}

function removeAgent(agentid) {

	//$("#agentid-modal").val(agentid);

	$('#modalRemoveAgent').modal({ show: 'true' });
	$('#modalRemoveAgent .modal-title').html('Remove Agent <strong>' + agentid + '</strong>');
	$('#modalRemoveAgent .modal-body').html('Are you sure you want to remove permanently the <strong>' + agentid + '</strong> agent? This action cannot be undone');

	$("#btn-rmv-agent").attr("href", "applib/deleteAgentDev.php?agentid=" + agentid);

}

$(document).ready(function() {

	DataTableMyAgents.init();
	SubmitAgent.init();
	UploadLogo.init();

});
