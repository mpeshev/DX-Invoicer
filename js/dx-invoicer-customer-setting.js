$ = jQuery.noConflict();
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
			
	$('.button.green').click(function(){ 
			var $id = $(this).attr('data-id');
			var customer_name = $('#'+$id).find('[data-name]').data('name');
			console.log(customer_name);
			var data = {
				customer_name : customer_name,
				action		  : 'add_outlook_customer'
			};
			jQuery.post(DXINVOICE.ajaxurl,data,function(response) { 
				
				if(response){
					$(this).removeClass('green');
					$(this).addClass('red');
					
				}
			});  
	});
	
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
