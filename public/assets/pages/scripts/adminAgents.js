var baseURL = $('#base-url').val();

// Open modal with agent config JSON 

callShowAgentJson = function(agent) {
	$('#modalAnalysis').modal('show');
	$('#modalAnalysis .modal-body').html('Loading data...');

	$.ajax({
		type: "POST",
		url: baseURL + "applib/showAgentJson.php",
		data: "agent=" + agent, 
		success: function(data) {
			$('#modalAnalysis .modal-body').html(data);
		}
	});

}

changeAgentStatus = function(agent, op) {
	location.href= baseURL + "applib/changeAgentStatusAdmin.php?agent=" + agent + "&status=" + op.value;

}


var DataTableMyAgents = function() {

	var handleDataTableMyAgents = function() {

		var table = $('#sample_editable_1');

		var oTable = table.dataTable({

				"lengthMenu": [
						[10, 20, -1],
						[10, 20, "All"] // change per page values here
				],

				"order": [
						[0, "asc"]
				],

				"columnDefs": [{
						'orderable': false,
						'targets': [2,3,4]
				}],

		});

	}

	return {
        //main function to initiate the module
        init: function() {
            handleDataTableMyAgents();
        }

    };


}();


$(document).ready(function() {

	DataTableMyAgents.init();

});

