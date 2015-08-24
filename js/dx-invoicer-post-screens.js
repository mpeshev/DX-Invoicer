/**
 * dxInvoice namespace declaration
 * 
 * Add functions for item table management here
 */
function dxInvoiceTable( tableClass, tableId ) {
	this.tableClass = tableClass;
	this.tableId = tableId || "";
	
	this.add_row = function( fields ) {
		var tbodySelector = '.' + tableClass + ' tbody:first';
		var rows_count = jQuery(tableClass + ' tbody tr').size();
		var row_content = '<tr>';
		//TODO: hardcode default fields in order to set some defaults 
		
		for( var i = 0; i < fields.length; i++ ) {
			var field_class = '';
			var field_value = '';
			if( this.default_fields[fields[i]] !== undefined ) {
				field_class = this.default_fields[fields[i]];
				field_value = this.get_default_field_value( field_class, rows_count );
				
			}
			if(fields[i] != 'net' && fields[i] != 'total' )
			row_content += '<td><input autocomplete="off" type="text" name="' + fields[i] + '[]" class="' + field_class +  '" value="' + field_value + '" /></td>';
			else
			row_content += '<td><input type="text" name="' + fields[i] + '[]" class="' + field_class +  '" readonly value="' + field_value + '" /></td>';
		}
		row_content += '<td><a href="#" class="dx_invoice_delete_row">Delete</a></td>';
		row_content += '</tr>';
		jQuery(tbodySelector).append( row_content );
	};
	
	this.delete_row = function( element ) {
		jQuery(element).parent().parent().remove();
	};
	
	this.get_default_field_value = function( field_class, row_number ) {
		if( field_class == 'dx_invoice_number_field' ) {
			if( this.tableId.length > 0 ) {
				var rows = jQuery('#' + this.tableId + ' .' + this.tableClass + ' tbody tr').size();
				return rows + 1;
			}
		}
		return '';
	};
}

dxInvoiceTable.prototype.default_fields = {
		number: 'dx_invoice_number_field',
		description: 'dx_invoice_description_field',
		rate: 'dx_invoice_rate_field',
		quantity: 'dx_invoice_quantity_field',
		net: 'dx_invoice_net_field',
		discount: 'dx_invoice_discount_field',
		total: 'dx_invoice_total_field',
};

jQuery(document).ready(function($) {
	//On load Net and total
	var total = 0;
	$('.dx_invoice_net_field').each(function() {
	        total += Number( $(this).val() );
	 });
	 if(total != 0)
	//$('.dx_invoice_net_all').html( total );
	
	var total = 0;
	$('.dx_invoice_total_field').each(function() {
	        total += Number( $(this).val() );
	 });
	$('input[name="_vat_text"]').change(function(){ 
		$('.dx_invoice_vat_field').val($(this).val());
	});
	$('.dx_invoice_vat_field').change(function(){ 
		$('input[name="_vat_text"]').val($(this).val());
	});
	
	// add new invoice row handler
	$('#dx-items-table-meta-wrapper .dx_invoice_add_row').on('click', function( e ) {
		e.preventDefault();
		
		var mainTable = new dxInvoiceTable( 'dx_invoice_field_table', 'dx-items-table-meta-wrapper' );
		mainTable.add_row( dxInvoiceMainCols );
		var rows = $('#dx-items-table-meta-wrapper .dx_invoice_field_table tbody tr').size();
		$('#dx_invoice_rows_number').val(rows);
	});
	
	// delete event handler
	$('#dx-items-table-meta-wrapper').on('click', '.dx_invoice_delete_row', function( e ) {
		e.preventDefault();
		var mainTable = new dxInvoiceTable( 'dx_invoice_field_table' );
		mainTable.delete_row( this );

		var rows = $('#dx-items-table-meta-wrapper .dx_invoice_field_table tbody tr').size();
		$('#dx_invoice_rows_number').val(rows);

		check_row();
		count_net_row();
		count_total_row();
		total_amount();
		count_net_column();
		count_total_column();
		count_vat_total();
		
	});
	
	// datepicker for dates
	$('.trigger_datepicker').datepicker();
	window.onload = function(){

		check_row();
		count_net_row();
		count_total_row();
		total_amount();
		count_net_column();
		count_total_column();
		count_vat_total();
	}
	$('.dx_invoice_rate_field,.dx_invoice_quantity_field,.dx_invoice_discount_field,.dx_invoice_vat_field').on('change',function(){
		check_row();
		count_net_row();
		count_total_row();
		total_amount();
		count_net_column();
		count_total_column();
		count_vat_total();
	});
	$(document).on('change','.dx_invoice_rate_field,.dx_invoice_quantity_field,.dx_invoice_discount_field,.dx_invoice_vat_field',function(){
		check_row();
		count_net_row();
		count_total_row();
		total_amount();
		count_net_column();
		count_total_column();
		count_vat_total();
	});
	$('.dx_invoice_delete_row').on('click',function(){
		check_row();
		count_net_row();
		count_total_row();
		total_amount();
		count_net_column();
		count_total_column();
		count_vat_total();
	});
	// Checking Row
	function check_row(){
		var length = $('.dx_invoice_field_body').find('tr').length;
		
		if(length){
			$('.dx_invoice_field_table').find('tfoot').show();
		}
		else{
			$('.dx_invoice_field_table').find('tfoot').hide();
		}
		$('.dx_invoice_vat_field').val($('#_vat_text').val());
		
		$('.dx_invoice_discount_field').each(function(){
			if($(this).val() == "")
			{
				$(this).val(0);
			}
		});
	}
	// Checking total Amount
	function total_amount(){
		 if(total != 0){
		 	var $vatamount = $('.dx_invoice_vat_amount').val();
		 	if($vatamount != "" && $vatamount != 0 )
			$('.dx_invoice_total_all').html( total - $vatamount );
		 }
	}
	
	// calculate totals for net and total fields in invoice rows
	$('.dx_invoice_field_table').on('change', '.dx_invoice_net_field', function(e) {
		var total = 0;
		
		$('.dx_invoice_net_field').each(function() {
	        total += Number( $(this).val() );
	        total_amount();
	    });
		
		//$('.dx_invoice_net_all').html( total );
	});
	
	$('.dx_invoice_field_table').on('change', '.dx_invoice_total_field', function(e) {
		var total = 0;
		
		$('.dx_invoice_total_field').each(function() {
	       // total += Number( $(this).val() );
	       total_amount();
	    });
		
		//$('.dx_invoice_total_all').html( total );
	});
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
		$('.dx_invoice_field_body').find('tr').each(function(){
			var rate = $(this).find('.dx_invoice_rate_field');
			var qty = $(this).find('.dx_invoice_quantity_field');
			var net = $(this).find('.dx_invoice_net_field');
			net.val((rate.val() * qty.val()).toFixed(2));
		});
	}
	
	// Count Total value
	function count_total_row(){
		$('.dx_invoice_field_body').find('tr').each(function(){
			var net = $(this).find('.dx_invoice_net_field');
			var discount = $(this).find('.dx_invoice_discount_field');
			var total = $(this).find('.dx_invoice_total_field');
			total.val((net.val() - discount.val()).toFixed(2));
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
	
	
	
	
});
