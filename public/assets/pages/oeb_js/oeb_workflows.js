//function for the admin to reject a agent
function rejectAgent(id) {
    var urlJSON = "applib/oeb_blocksAPI.php";

    if(confirm("Do you want to reject the workflow?")) {
        $.ajax({
            type: 'POST',
            url: urlJSON,
            data: {'action': 'reject_workflow', 'id': id}
        }).done(function(data) {
            //reload the table
            reload();
        });
    };
}

//function for the admin to register a agent
function registerAgent(id) {
	var urlJSON = "applib/oeb_blocksAPI.php";
	
	if(confirm("Do you want to create a agent?")) {
        $("#general").hide();
        //loading spinner
        $("#loading-datatable").show();
        
        //create the agent
		$.ajax({
			type: 'POST',
			url: urlJSON,
			data: {'action': 'createAgent_fromWFs', 'id': id}
		}).done(function(data) {
            $("#general").show();
            $("#loading-datatable").hide();

            //reload the table
            reload();
            //no errors
			if (data["code"] == 200) {
				$("#errorsAgent").removeClass("alert alert-danger");
				$("#errorsAgent").addClass("alert alert-info");
				$("#errorsAgent").text("VRE agent created successfully!");
                $("#errorsAgent").show();
            //errors
			} else {
				$("#errorsAgent").removeClass("alert alert-info");
				$("#errorsAgent").addClass("alert alert-danger");
				$("#errorsAgent").text("Sorry... There has been an error.");
				$("#errorsAgent").show();
			}

		}).fail(function(data) {
            $("#loading-datatable").hide();
            $("#general").show();
            //function to show the errors of the validator
            if(data["responseJSON"]["message"] != "NO_EXIST") {
                $("#errorsAgent").text("ERRORS in the validation of the agent: ");
                for(let x = 0; x < data["responseJSON"]["message"].length; x++) {
                    document.getElementById("errorsAgent").innerHTML += "<br>" + data["responseJSON"]["message"][x];
                }
                
                reload();
            //if there are an error that no corresponds to the validator is that.
            } else {
                $("#errorsAgent").text("The validation block used in WF does not exist in the DDBB.");
            }
            $("#errorsAgent").removeClass("alert alert-info");
            $("#errorsAgent").addClass("alert alert-danger");
            $("#errorsAgent").show();

        });
    };
};

//for the View JSON option
function callShowWorkflowJson(id) {
    var urlJSON = "applib/oeb_blocksAPI.php";
    $('#modalAnalysis').modal('show');
	$('#modalAnalysis .modal-body').html('Loading data...');

	$.ajax({
		type: "POST",
		url: urlJSON,
		data: {'action': 'showWorkflowJSON', 'id': id}
	}).success(function(data) {
        //return error because is not a JSON but means that work propertly.
    }).fail(function(data) {
        $('#modalAnalysis .modal-body').html(data["responseText"]);
    });
}

$(document).ready(function() {

	$("#errorsAgent").hide();

    var urlJSON = "applib/oeb_blocksAPI.php";
	//the id has to be current in the petition. If not, returns information about the owner with the id given
    $.ajax({
		type: 'POST',
		url: urlJSON,
		data: {'action': 'getUser', 'id': 'current'}
	}).done(function(data) {
        var currentUser = data;
        var columns = [{ "data" : "data.title"},
        { "data" : "date" },
        { "data" : "data.owner.author" },
        { "data" : "data.owner.oeb_community", 'defaultContent': '' },
        { "data" : null, 'defaultContent': '' },
        { "data" : "request_status" }];

        //FOR THE ADMINS
        if(currentUser["Type"] == 0){
            columns.push({"data": null,  'defaultContent': '', "title": "Actions", "targets": 6, render: function(data, type, row) {
                if (data["request_status"] == "submitted") {
                    return '<div class="btn-group"><button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Actions <i class="fa fa-angle-down"></i></button>' +
                        '<ul class="dropdown-menu pull-right" role="menu">' +
                            '<li>' +
                                '<a onclick="registerAgent(name);" name="'+row._id+'" id="s'+row._id+'"><i class="fa fa-check-square-o"></i> Create VRE agent</a>' +
                            '</li>' +
                            '<li>' +
                                '<a onclick="rejectAgent(name);" name="'+row._id+'" id="r'+row._id+'"><i class="fa fa-ban"></i> Reject workflow</a>' +
                            '</li>' +
                        '</ul></div>';
                } else {
                    return '';
                }
            }, "targets": 6});
         }

        //GENERAL DATATABLE
        $('#workflowsTable').DataTable( {
            "ajax": {
                url: 'applib/oeb_blocksAPI.php?action=getWorkflows',
                dataSrc: ''
            },
            autoWidth: false,
            "columns" : columns,
            "columnDefs": [
                //targets are the number of corresponding columns
                { "title": "Title", "targets": 0 },
                { "title": "Date", "targets": 1 },
                { "title": "Owner", "targets": 2 },
                { "title": "Community Owner", "targets": 3 },
                { "title": "View JSON", "targets": 4},
                { "title": "Status", "targets": 5 },
                { render: function(data, type, row) {
                    //FOR ADMINS
                    //Submitted => the agent has been submitted by the community manager and the administrator has to accepted it
                    //Registered => the administrator has admit the data and the VRE agent is automatically generated
                    //Rejected => the administrator does not admit the data and the VRE agent is not created
                    switch(data) {
                        case "submitted":
                            return '<div class="note note-success" style="background-color:rgba(109, 91, 142,0.7);border-color:rgb(109, 91,142)"><p class="font-white"><b>SUBMITTED</b>:<br> Waiting for VRE team response.</p></div>';
                            break;
                        case "registered": 
                            return '<div><div class="note bg-green-jungle"><p class="font-white"><b>ACCEPTED</b>:<br/>Agent successfully registed!</p></div>';
                            break;
                        case "rejected":
                            return '<div><div class="note note-danger"><p class="font-red"><b>REJECTED</b>:<br/>Code not accepted</p></div>';
                            break;
                        default: 
                            return "";
                    } 

                }, "targets": 5},
                { render: function(data, type, row) {
                    return '<a onclick="callShowWorkflowJson(name)"; name="'+row._id+'" id="c'+row._id+'"">View JSON</a>'
                }, "targets": 4}
            ]
        });
    });
	
	$("#workflowsReload").click(function() {
		reload();
    });
});

//for reload the table of workflows
function reload() {
	$.getJSON('applib/oeb_blocksAPI.php?action=getWorkflows', function() {
		var oTblReport;

		if ($.fn.dataTable.isDataTable('#workflowsTable')) {
			oTblReport = $('#workflowsTable').DataTable();
			oTblReport.ajax.reload();
		}

	});
}

