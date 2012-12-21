<?php

class DX_Form_Filters {
	
	public $cols;
	
	public function __construct() {
		$this->cols  = array(
				_x('Number', 'invoice item number', 'dxinvoice'),
				_x('Description', 'invoice description type', 'dxinvoice'),
				_x('Rate', 'invoice description type', 'dxinvoice'),
				_x('Quantity', 'invoice description type', 'dxinvoice'),
				_x('Net', 'invoice description type', 'dxinvoice'),
				_x('VAT Percentage', 'invoice description type', 'dxinvoice'),
				_x('VAT', 'invoice description type', 'dxinvoice'),
		);
		// Apply filters to update the columns
		$this->cols = apply_filters( 'dx_invoice_item_cols', $this->cols );
		
		add_action( 'dx_invoicer_form_fields_action', array( $this, 'add_invoice_row_field' ), 10, 6 );
	}
	
	/**
	 * Add the invoice specific row field with the columns
	 * 
	 * @param $type field type (text, dx_invoicer_form_field, select, textarea...)
	 * @param $item the item name
	 * @param $attributes array with attributes
	 * @param $method HTTP method where data is stored
	 * @param $section_prefix a prefix for the section, if any
	 * @param $id_prefix a prefix for IDs, if any
	 */
	public function add_invoice_row_field( $type, $item, $attributes, $method, $section_prefix, $id_prefix ) {
		if( $type == 'dx_invoicer_form_field' ) {
			extract( $attributes );
		?>
			<section id="<?php echo $section_prefix . $id ?>" class="<?php echo $class ?>" <?php echo $style; ?> >
				<table class="dx_invoice_field_table">
					<thead>
						<tr>
							<?php foreach( $this->cols as $col ): ?>
								<th><?php _e( $col, 'dxinvoice' ); ?></th>
							<?php endforeach; ?>
						</tr>	
					</thead>
					<tbody class="dx_invoice_field_body">
						<tr>
						</tr>
					</tbody>
				</table>
			</section>		
		<?php 	
		}
	}
}