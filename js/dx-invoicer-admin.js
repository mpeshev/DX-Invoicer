jQuery(document).ready(function($){ 
	
	// Calling Choosen JS
	$("#_client,#invoice-page-template,#_page_templates,#_currency").chosen();
	
		$('.dx-pdf-generate').click(function(){
			
			$ID = $('#post_ID').val(); 
			//$('.dx_action_validate').val(1);
			//$(this).submit();
			/*var data = { 
						action				:	'generate-pdf',
						post_id				:	$ID,
						dx_action_validate  :   1
					};
			jQuery.post(ajaxurl, data, 
		    function(response){
		        alert('The server responded: ' + response);
		    });*/
	    });
});
