jQuery(document).ready(function($){ 
	$('.dx-user-role').hide();
	if($('#role option:selected').val() == 'dx_customer_role'){
		$('.dx-user-role').show();
	}
	$('#role').change(function(){
		console.log($('option:selected',this).val());
		if('dx_customer_role' == $('option:selected',this).val()){
			$('.dx-user-role').show();
		}else{
			$('.dx-user-role').hide();
		}
	});
});
