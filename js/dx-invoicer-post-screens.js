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
			row_content += '<td><input type="text" name="' + fields[i] + '[]" class="' + field_class +  '" value="' + field_value + '" /></td>';
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
		total: 'dx_invoice_total_field',
};

jQuery(document).ready(function($) {
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
		
	});
	
	// datepicker for dates
	$('.trigger_datepicker').datepicker();
	
	// calculate totals for net and total fields in invoice rows
	$('.dx_invoice_field_table').on('change', '.dx_invoice_net_field', function(e) {
		var total = 0;
		
		$('.dx_invoice_net_field').each(function() {
	        total += Number( $(this).val() );
	    });
		
		$('.dx_invoice_net_all').html( total );
	});
	
	$('.dx_invoice_field_table').on('change', '.dx_invoice_total_field', function(e) {
		var total = 0;
		
		$('.dx_invoice_total_field').each(function() {
	        total += Number( $(this).val() );
	    });
		
		$('.dx_invoice_total_all').html( total );
	});
});
