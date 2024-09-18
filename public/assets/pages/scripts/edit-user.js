
var Select2Init = function() {

	var handleSelect2 = function() {

		$(".select2agents").select2({
			placeholder: "Select one or more agents clicking here",
			width: '100%'
		});

		$('.select2agents').on('change', function() {
			if($(this).find('option:selected').length > 0) {
				$(this).parent().removeClass('has-error');
				$(this).parent().find('.help-block').hide();
			}
		});

	}

	return {
		//main function to initiate the module
		init: function() {
			handleSelect2();
		}

	};

}();


$(document).ready(function() {  

	Select2Init.init();	

	$('#Type').change(function() {

		var type = $(this).find("option:selected").attr('value');

		if(type == 1) {

			$('#agents').attr('disabled', false);
			$('.agents_select').show();	

		} else {
			
			$('#agents').attr('disabled', true);
			$('.agents_select').hide();

		}	
	
	});

});
