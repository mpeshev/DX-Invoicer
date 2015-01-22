jQuery(document).ready(function($){
	
	
	$('.dx_invoice_rate_field').on('change',function(){
		
		$net = $(this).parent('tr').find('.dx_invoice_net_field');
		$rate = $(this).parent('tr').find('.dx_invoice_rate_field');
		$quantity = $(this).parent('tr').find('.dx_invoice_quantity_field');
		$net.val($rate.val() * $quantity.val());
	});
	
			// Count net column
	function count_net_column(){
		var netfield = $('.dx_invoice_net_field');
		var total = 0;
		netfield.each(function(){
			total+= Number($(this).val());
		})
		$('.dx_invoice_net_all').html(total.toFixed(2));
	}
	// Count Total column
	function count_total_column(){
		var totalfield = $('.dx_invoice_total_field');
		var total = 0;
		totalfield.each(function(){
			total+= Number($(this).val());
		})
		$('.dx_invoice_total_all').html(total.toFixed(2));
	}
	// Count Net value
	function count_net_row(){ 
		$('.table-calc').find('tr').each(function(){
			var rate = $(this).find('.dx-rate');
			var qty = $(this).find('.dx-quantity');
			var net = $(this).find('.dx-net');
			net.html((rate.html() * qty.html()).toFixed(2));
		});
	}
	
	// Count Total value
	function count_total_row(){
		$('.table-calc').find('tr').each(function(){
			var net = $(this).find('.dx-net');
			var discount = $(this).find('.dx-discount');
			var total = $(this).find('.dx-total');
			total.html((Number(net.html()) - Number(discount.html())).toFixed(2));
		});
	}
	// Calculate VAT 
	function count_vat_total(){
		var vat = $('.dx_invoice_vat_field').val();
		var total = $('.dx_invoice_total_all').html();
		var vatamount = $('.dx_invoice_vat_amount');
		vatamount.val((vat/100 * total).toFixed(2));
		$('.dx_invoice_total_all_final').html((Number(total) + Number(vatamount.val())).toFixed(2));
	}
	// Checking total Amount
	function total_amount(){
		var total = 0;
		 if(total != 0){
		 	var $vatamount = $('.dx_invoice_vat_amount').val();
		 	if($vatamount != "" && $vatamount != 0 )
			$('.dx_invoice_total_all').html( total - $vatamount );
		 }
	}
	function net_amounts(){
		var netamount = 0;
		 $('.dx-net').each(function(){
			netamount += Number($(this).html());
		});
		$('.dx-net-amount').html((netamount).toFixed(2));
	}
	function discount_amounts(){
		var discountamount = 0;
		 $('.dx-discount').each(function(){
			discountamount += Number($(this).html());
		});
		$('.dx-discount-all').html((discountamount).toFixed(2));
	}
	function final_total_pay(){
		var total_pay = 0;
		net_amount = $('.dx-net-amount').html();
		discount_amount = $('.dx-discount-all').html();
		vat_amount = $('.dx-vat-amount').html();
		
		total_pay = Number(net_amount) - Number(discount_amount) + Number(vat_amount);
		$('.dx-final-total').html(Number(total_pay).toFixed(2));
	}
	function vat_calc(){
		var str = $('.dx-vat').html();
		var percent = str.split("%");
		
		var netamount = $('.dx-net-amount').html();
		var discount  = $('.dx-discount-all').html();
		var vatamount = 0 ;
		vatamount = Number(netamount) - Number(discount);
		vatamount = Number(percent[0]/100) * Number(vatamount);
		$('.dx-vat-amount').html((vatamount).toFixed(2));
	}

	
		$('.editable').click(function(){
			$('body').toggleClass('disable');
		});
	
		$textarea = $('.changable-textarea');
		$text = $('.changable-text');
		$body = $('body');
		
		
		$text.click(function(event){
			input_to_field();
			
			if($text.find('input').length == 0){
				$(this).html("<input type='text' value='"+$(this).html()+"' name='' >");
				$(this).find('input').focus();
			}
			event.stopPropagation();
		});
		$textarea.click(function(event){
			input_to_field();
			if($textarea.find('textarea').length == 0){
				$(this).html("<textarea type='text'>"+$.trim($(this).html())+"</textarea>");
				$(this).find('textarea').focus();
			}
			event.stopPropagation();
		});
		
		$('body').click(function(event){
			input_to_field();
		});
		
		function input_to_field(){ 
			var body = $('body');
			$inputavailable = body.find('input').length;
			$textareaavailable = body.find('textarea').length;
			
			if($inputavailable){
				body.find('input').each(function(e){
					$fieldval = $.trim($(this).val());
					$(this).replaceWith($fieldval);
				});
			}
			if($textareaavailable){
				body.find('textarea').each(function(e){
					$fieldval = $.trim($(this).val());
					$(this).replaceWith($fieldval);
				});
			}
			count_net_row();
			count_total_row();
			total_amount();
			net_amounts();
			discount_amounts();
			vat_calc();
			final_total_pay();
		}
		
		
		$("body").keyup(function (e) {
		    if (e.keyCode == 13) { 
			    input_to_field();
				count_net_row();
				count_total_row();
				total_amount();
				net_amounts();
				discount_amounts();
				vat_calc();
				final_total_pay();
				
		    }
		});
		
		$('button').click(function(){ 
			
				$inputavailable = $('body').find('input').length;
				$textareaavailable = $('body').find('textarea').length;
				
				if($inputavailable){
					$('body').find('input').each(function(e){
						$fieldval = $(this).val();
						$(this).replaceWith($fieldval);
					});
				}
				if($textareaavailable){
					$('body').find('textarea').each(function(e){
						$fieldval = $(this).val();
						$(this).replaceWith($fieldval);
					});
				}
				
			var btnevnt = $(this).attr('id');
			/*	Table 1		*/
			var post_id  				= $('[data-post-id]').data('post-id');
			
			var clientname  			= $('[data-clientname]').html();
			var clientid  				= $('[data-clientname]').data('clientname');
			var data_clientcompany  	= $('[data-clientcompany]').html();
			var data_clientcomaddr  	= $.trim($('[data-clientcomaddr]').html());
			
			var data_clientcomnum  		= $('[data-clientcomnum]').html();
			var data_contactperson  		= $('[data-contactperson]').html();
			/*	Table 2		*/
			var data_customername  		= $('[data-customername]').html();
			var data_customercomname  	= $('[data-customercomname]').html();
			var data_customercomaddr  	= $('[data-customercomaddr]').html();
			var data_customercomidno  	= $('[data-customercomidno]').html();
			var data_customercomcontactp= $('[data-customercomcontactp]').html();
			var data_bankacc			= $('[data-bankacc]').html();
			var data_setting_account	= $('[data-setting-account]').html();
			var vat_value				= $('.dx-vat').html();
			
			
			var tabledata = new Array();
			var finaldata = new Array();
			var parentIndex = "";
			var childval = "";
			$('.invoice-body-wrap').each(function(index){
				parentIndex = index;
				tabledata[index]= {};
				
				$(this).find('td').each(function(index){
					childval = $(this).html();
					tabledata[parentIndex][index] = {};
					tabledata[parentIndex][index]= {index:childval};
				});
				finaldata.push(tabledata[index]);
			});
			
			var datareturn = { 
								action							:	'dx_invoice_update',
								dx_page_id						:	post_id,
								dx_clientname					:	clientname,
								data_clientcompany				:	data_clientcompany,
								data_clientcomaddr				:	data_clientcomaddr,
								data_clientcomnum				:	data_clientcomnum,
								data_contactperson				:	data_contactperson,
								data_customername				:	data_customername,
								data_customercomname			:	data_customercomname,
								data_customercomaddr			:	data_customercomaddr,
								data_customercomidno			:	data_customercomidno,
								data_customercomcontactp		:	data_customercomcontactp,
								data_bankacc					:	data_bankacc,
								data_setting_account			:	data_setting_account,
								vat_value						:	vat_value,
								buttonevent						:	btnevnt,
								customerid						:	clientid,
								'invoicedata[]'					: 	JSON.stringify(finaldata)
			
			};
						
			console.log(datareturn);
			jQuery.post(ajaxurl,datareturn,function(response) { 
				
				if(btnevnt == 'saveandGenerate'){
					
					$('#pdf-download').attr('href',response);
					$('#pdf-download').show();
					alert('PDF Saved Click below link for download');
				}
				else{
					
					alert('PDF Saved');
				}
			});
		});
		
	});