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
	
	/*$('.handlediv').click(function(){
		$('#settings').toggleClass('closed');
	});
	*/
	//$(".if-js-closed").removeClass("if-js-closed").addClass("closed");
	
	//postboxes.add_postbox_toggles( "dx-invoice-settings" );
			
	
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
		
	$(".if-js-closed").removeClass("if-js-closed").addClass("closed");
	
	$("#invoice-page-template").chosen();
	
	postboxes.add_postbox_toggles( "dx-customer-settings" );			
});
