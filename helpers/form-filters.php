<?php

class DX_Form_Filters {
	
	public $cols;
	
	public static $instance = NULL;
	
	private function __construct() {
		$this->cols  = array(
				'number' 	  => _x('Number', 'invoice item number', 'dxinvoice'),
				'description' => _x('Description', 'invoice description type', 'dxinvoice'),
				'rate'		  => _x('Rate', 'invoice description type', 'dxinvoice'),
				'quantity' 	  => _x('Quantity', 'invoice description type', 'dxinvoice'),
				'net' 		  => _x('Net', 'invoice description type', 'dxinvoice'),
			  //'vat_percent' => _x('VAT Percentage', 'invoice description type', 'dxinvoice'),
				'vat' 		  => _x('VAT', 'invoice description type', 'dxinvoice'),
		);
		// Apply filters to update the columns
		$this->cols = apply_filters( 'dx_invoice_item_cols', $this->cols );
		add_action( 'admin_head' , array( $this, 'prepare_columns_for_script' ) );
		
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
			$initial_rows = 0;
			ob_start();
		?>
			<section id="<?php $section_prefix; ?>dx_invoicer_form_field" class="<?php echo $class ?>" <?php echo $style; ?> >
				<table class="dx_invoice_field_table">
					<thead>
						<tr>
							<?php foreach( $this->cols as $key => $col ): ?>
								<th><?php _e( $col, 'dxinvoice' ); ?></th>
							<?php endforeach; ?>
							<th><?php _e('Delete Row', 'dxinvoice'); ?></th>
						</tr>	
					</thead>
					<tbody class="dx_invoice_field_body">
						<?php if( ! empty( $attributes['value'] ) && is_array( $attributes['value'] ) ) {
							$initial_rows = count( $attributes['value'] );
							foreach( $attributes['value'] as $row ) {
								echo "<tr>";
								foreach( $this->cols as $key => $col ) {
									$value = ''; 
									if( isset( $row[$key] ) ) {
										$value = $row[$key];
									}
									echo '<td><input type="text" name="' . $key . '[]" value="' . $value . '" /></td>';								
								}
								echo '<td><a href="#" class="dx_invoice_delete_row">Delete</a></td>';
								echo "</tr>";
							}
						} 
						?>
					</tbody>
				</table>
				<input type="hidden" id="dx_invoice_rows_number" name="dx_invoice_rows_number" value="<?php echo $initial_rows; ?>" />
				<a class="dx_invoice_add_row"><?php _e('Add Row', 'dxinvoice'); ?></a>
			</section>		
		<?php 	
			$output = ob_get_clean();
			echo apply_filters( 'dx_invoice_filter_invoices_table', $output );
		}
	}
	
	public function prepare_columns_for_script() {
		$cols = json_encode( array_keys( $this->cols ) );
	?>
		<script type="text/javascript">
			var dxInvoiceMainCols = <?php echo $cols; ?>;
		</script>
	<?php 
	}
	
	/**
	 * Singleton.
	 * @return instance
	 */
	public static function instance() {
		if( ! is_null( self::$instance ) ) {
			return self::$instance;
		}
		
		self::$instance = new DX_Form_Filters();
	}
	
}

DX_Form_Filters::instance();