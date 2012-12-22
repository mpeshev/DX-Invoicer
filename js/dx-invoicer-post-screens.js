/**
 * dxInvoice namespace declaration
 * 
 * Add functions for item table management here
 */
function dxInvoiceTable( tableClass ) {
	this.tableClass = tableClass;
	
	this.add_row = function( fields ) {
		var tbodySelector = '.' + tableClass + ' tbody:first';
		var row_content = '<tr>';
		for( var i = 0; i < fields.length; i++ ) {
			row_content += '<td><input type="text" name="' + fields[i] + '[]" /></td>';
		}
		row_content += '</tr>';
		jQuery(tbodySelector).append( row_content );
	};
	
	this.delete_row = function( element ) {
		jQuery(element).parent().parent().remove();
	};
}

jQuery(document).ready(function($) {
	$('#dx-items-table-meta-wrapper .dx_invoice_add_row').on('click', function( e ) {
		e.preventDefault();
		
		var mainTable = new dxInvoiceTable( 'dx_invoice_field_table' );
		mainTable.add_row( dxInvoiceMainCols );
		var rows = $('#dx-items-table-meta-wrapper tbody tr').size();
		$('#dx_invoice_rows_number').val(rows);
	});
	
	$('#dx-items-table-meta-wrapper .dx_invoice_delete_row').on('click', function( e ) {
		e.preventDefault();
		var mainTable = new dxInvoiceTable( 'dx_invoice_field_table' );
		mainTable.delete_row( this );
		
		var rows = $('#dx-items-table-meta-wrapper tbody tr').size();
		$('#dx_invoice_rows_number').val(rows);
		
	});
});
