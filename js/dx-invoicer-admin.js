jQuery(document).ready(function($){ 
	$('.post-type-dx_invoice #publish').click(function(){ 
		$value = $('#_invoice_number').val();
		
		if(isNaN($value)){
			$("#_invoice_number").css('border','1px solid red');
			return false;
		}
		else{
			return true;
		}
	});
	$('#publish').click(function(){
		$titleval = $('#title').val();
		if($.trim($titleval) == ''){
			$('#title').css('border','1px solid red');
			return false;
		}
		
	});
	/*$('.handlediv').click(function(){
		$('#settings').toggleClass('closed');
	});
	*/
/*	$(".if-js-closed").removeClass("if-js-closed").addClass("closed");
	
	postboxes.add_postbox_toggles( "admin_page_dx-invoice-settings" );*/
			
	
	$('.dx-invoice-settings-save').click(function(){
		$invoice = $('#dx-invoice-settings-invoice').val();
		$increment = $('#dx-invoice-settings-increment').val();
		var flag = 0;
		
		if(isNaN($invoice)){
			$("#dx-invoice-settings-invoice").css('border','1px solid red');
			flag =1;
		}
		if(isNaN($increment)){
			$("#dx-invoice-settings-increment").css('border','1px solid red');
			flag =1;
		}
		
		if(flag ==1)
		return false;
	}); 
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
