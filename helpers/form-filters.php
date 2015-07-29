<?php

class DX_Form_Filters {
	
	public $cols;
	
	public static $instance = NULL;
	
	private function __construct() {
		$this->cols  = array(
				'number' 	  => _x('Number', 'invoice item number', 'dxinvoice'),
				'invoice_description' => _x('Description', 'invoice description type', 'dxinvoice'),
				'rate'		  => _x('Rate', 'invoice description type', 'dxinvoice'),
				'quantity' 	  => _x('Quantity', 'invoice description type', 'dxinvoice'),
				'net' 		  => _x('Net', 'invoice description type', 'dxinvoice'),
			  	'discount' 	  => _x('Discount', 'invoice description type', 'dxinvoice'),
				'total' 	  => _x('Total', 'invoice description type', 'dxinvoice'),
		);
		// Apply filters to update the columns
		$this->cols = apply_filters( 'dx_invoice_item_cols', $this->cols );
		add_action( 'admin_head' , array( $this, 'prepare_columns_for_script' ) );
		
		add_action( 'dx_invoicer_form_fields_action', array( $this, 'add_invoice_row_field' ), 10, 6 );
		add_action( 'dx_invoicer_form_fields_action', array( $this, 'add_customer_field' ), 10, 6 );
		add_action( 'dx_invoicer_form_fields_action', array( $this, 'add_custom_templates' ), 10, 6 );
		add_action( 'dx_invoicer_form_fields_action', array( $this, 'add_stamp_position' ), 10, 6 );
		add_action( 'dx_invoicer_form_fields_action', array( $this, 'add_status_invoices' ), 10, 6 );
		add_action( 'do_meta_boxes', array( $this, 'dx_invoice_remove_image_box' ), 10, 6 );
		add_action( 'do_meta_boxes', array( $this, 'dx_customer_remove_image_box' ), 10, 6 );
		add_action( 'admin_init', array( $this, 'dx_customer_set_user_metaboxes' ), 10, 6 );
		add_action( 'admin_init', array( $this, 'dx_invoice_set_user_metaboxes' ), 10, 6 );
		add_action( 'admin_init', array( $this, 'save_options' ), 10, 6 );
		add_filter( 'dx_invoice_setting_currency' ,array( $this, 'set_currency' ));
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
			
			$label = !empty($label)	? $label	:"";
		    $type  = !empty($type)	? $type		:"";
		    $name  = !empty($name)	? $name		:"";
		    $value = !empty($value)	? $value	:"";
		    $text  = !empty($text)	? $text		:"";
		    $id    = !empty($id)	? $id		:"";
		    $class = !empty($class)	? $class	:"";
		    $style = !empty($style)	? $style	:"";
			$initial_rows = 0;
			ob_start();
		?>
			<section id="<?php $section_prefix; ?>dx_invoicer_form_field" class="<?php echo $class ?>" <?php echo $style; ?> >
				<table class="dx_invoice_field_table">
					<thead>
						<tr>
							<?php foreach( $this->cols as $key => $col ): ?>
								<th class="<?php echo esc_attr( DX_Invoicer::get_default_table_header_classes( $key ) ); ?>"><?php _e( $col, 'dxinvoice' ); ?></th>
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
									if($key != 'net' && $key != 'total')
									echo '<td><input autocomplete="off" type="text" name="' . $key . '[]" value="' . esc_attr( $value ) . '" class="' . esc_attr( DX_Invoicer::get_default_table_header_classes( $key ) ) . '" /></td>';								
									else 
									echo '<td><input type="text" name="' . $key . '[]" value="' . esc_attr( $value ) . '" class="' . esc_attr( DX_Invoicer::get_default_table_header_classes( $key ) ) . '" readonly /></td>';								
								}
								echo '<td><a href="#" class="dx_invoice_delete_row">Delete</a></td>';
								echo "</tr>";
							}
						} 
						?>
					</tbody>
					<tfoot>
					<tr>
					<?php 
						foreach( $this->cols as $key => $col ) {
							$value = ''; 
							$span_class = '';
							
							if( $key == 'net' ) {
								$span_class = 'dx_invoice_net_all';
							}
							else if( $key == 'total' ) {
								$span_class = 'dx_invoice_total_all';
							}
							
							echo '<td class="' . esc_attr( DX_Invoicer::get_default_table_header_classes( $key ) ) . '"><span class="' . $span_class . '"></span></td>';								
						}
					?>
					</tr>
						<tr>
							<td colspan="4"></td>
							<td>Vat (&#37;)</td>
							<td><input type="text" name="tax_value" class="dx_invoice_vat_field" value=""></td>
							<td><input type="text" name="tax_amount" class="dx_invoice_vat_amount" readonly></td>
						</tr>
						<tr>
					<?php 
						foreach( $this->cols as $key => $col ) {
							$value = ''; 
							$span_class = '';
							
							if( $key == 'total' ) {
								$span_class = 'dx_invoice_total_all_final';
							}
							
							echo '<td class="' . esc_attr( DX_Invoicer::get_default_table_header_classes( $key ) ) . '"><span class="' . $span_class . '"></span></td>';								
						}
					?>
					</tr>
					</tfoot>
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
	 * Add field for displaying customers on the Invoice form 
	 * 
	 * @param $type field type (text, dx_invoicer_form_field, select, textarea...)
	 * @param $item the item name
	 * @param $attributes array with attributes
	 * @param $method HTTP method where data is stored
	 * @param $section_prefix a prefix for the section, if any
	 * @param $id_prefix a prefix for IDs, if any
	 */
	public function add_customer_field( $type, $item, $attributes, $method, $section_prefix, $id_prefix ) {
		if( $type == 'dx_customer_field' ) {
			//extract( $attributes );
			extract( array_merge ($attributes, DX_Form_Helper::get_element_attributes( $item, $attributes, $method ) ) );
			$label = !empty($label)	? $label	:"";
		    $type  = !empty($type)	? $type		:"";
		    $name  = !empty($name)	? $name		:"";
		    $value = !empty($value)	? $value	:"";
		    $text  = !empty($text)	? $text		:"";
		    $id    = !empty($id)	? $id		:"";
		    $class = !empty($class)	? $class	:"";
		    $style = !empty($style)	? $style	:"";
			$initial_rows = 0;
			$current_user_id = get_current_user_id();
			$customers_query = new WP_Query(array(
					'post_type' => 'dx_customer',
					'author' => $current_user_id,
					'order' => 'ASC',
					'orderby' => 'title'
			));

			ob_start();
			?>
			<tr>
				<th scope="row">
					<label for="<?php echo $id_prefix . $id ?>"><?php echo $text ?></label>	
				</th>
				<td><select name="<?php echo $name ?>" id="<?php echo $id_prefix . $id ?>" <?php echo ($type == 'multiselect' ? 'multiple="multiple"' : '') ?> >
						<option id="dx_empty_customer" value=""><?php _e('Pick an existing customer', 'dxinvoice'); ?></option>
						<?php while( $customers_query->have_posts() ):
								$customers_query->the_post(); ?>
						<option id="customer_<?php the_ID(); ?>" value="<?php the_ID(); ?>" <?php echo (get_the_ID() == $value ? 'selected' : '' ) ?>><?php echo the_title(); ?></option>
						<?php endwhile;
							wp_reset_postdata();
						?>
					</select><br />
					<span class="description"><?php echo __( 'Customer not here? Create one in the Customers admin menu first.', 'dxinvoice' ) ?></span>
				</td>
			 </tr>
			<?php 
			$output = ob_get_clean();
			echo apply_filters( 'dx_invoice_filter_invoices_table', $output );
		}
	}
	
	/**
	 * Add field for displaying Position of stamp 
	 * 
	 * @param $type field type (text, dx_invoicer_form_field, select, textarea...)
	 * @param $item the item name
	 * @param $attributes array with attributes
	 * @param $method HTTP method where data is stored
	 * @param $section_prefix a prefix for the section, if any
	 * @param $id_prefix a prefix for IDs, if any
	 */
	public function add_stamp_position( $type, $item, $attributes, $method, $section_prefix, $id_prefix ) {
		if( $type == 'stamp_position' ) {
			//extract( $attributes );
			extract( array_merge ($attributes, DX_Form_Helper::get_element_attributes( $item, $attributes, $method ) ) );
			$label = !empty($label)	? $label	:"";
		    $type  = !empty($type)	? $type		:"";
		    $name  = !empty($name)	? $name		:"";
		    $value = !empty($value)	? $value	:"";
		    $text  = !empty($text)	? $text		:"";
		    $id    = !empty($id)	? $id		:"";
		    $class = !empty($class)	? $class	:"";
		    $style = !empty($style)	? $style	:"";
			$initial_rows = 0;
			$current_user_id = get_current_user_id();

			ob_start();
			?>
			<tr>
				<th scope="row">
					<label for="<?php echo $id_prefix . $id ?>"><?php echo $text ?></label>	
				</th>
				<td>
				<div class="stamp-radio">

					<input type="radio" id="radio1" name="<?php echo $name ?>" value="30" <?php echo ($value == 30)? "checked" :""; ?> >
					<label for="radio1">Left</label>
					<input type="radio" id="radio2" name="<?php echo $name ?>" value="90" <?php echo ($value == 90)? "checked" :""; ?> >
					<label for="radio2">Center</label>
					<input type="radio" id="radio3" name="<?php echo $name ?>" value="150" <?php echo ($value == 150)? "checked" :""; ?> >
					<label for="radio3">Right</label>
				</div>
					<br />
					<span class="description"><?php echo __( 'Select Stamp position.', 'dxinvoice' ) ?></span>
				</td>
			 </tr>
			<?php 
			$output = ob_get_clean();
			echo apply_filters( 'dx_invoice_filter_invoices_table', $output );
		}
	}
	
	/**
	 * Add field for displaying invoice status 
	 * 
	 * @param $type field type (text, dx_invoicer_form_field, select, textarea...)
	 * @param $item the item name
	 * @param $attributes array with attributes
	 * @param $method HTTP method where data is stored
	 * @param $section_prefix a prefix for the section, if any
	 * @param $id_prefix a prefix for IDs, if any
	 */

	public function add_status_invoices( $type, $item, $attributes, $method, $section_prefix, $id_prefix ) {
		if( $type == 'status_invoice' ) {
			//extract( $attributes );
			extract( array_merge ($attributes, DX_Form_Helper::get_element_attributes( $item, $attributes, $method ) ) );
			$label = !empty($label)	? $label	:"";
		    $type  = !empty($type)	? $type		:"";
		    $name  = !empty($name)	? $name		:"";
		    $value = !empty($value)	? $value	:"unpaid";
		    $text  = !empty($text)	? $text		:"";
		    $id    = !empty($id)	? $id		:"";
		    $class = !empty($class)	? $class	:"";
		    $style = !empty($style)	? $style	:"";
			$initial_rows = 0;
			$current_user_id = get_current_user_id();

			$option = array(
					'unpaid' => __('UNPAID','dxinvoice'),
					'paid' => __('PAID','dxinvoice')
				);
			ob_start();

			?>
			<tr>
				<th scope="row">
					<label for="<?php echo $id_prefix . $id ?>"><?php echo $text ?></label>	
				</th>
				<td>
					<select name="<?php echo $name ?>" id="<?php echo $id_prefix . $id ?>" >
							<option id="dx_status_invoice" value=""><?php _e('Select Status Invoice', 'dxinvoice'); ?></option>						
							<?php
								foreach($option as $key => $_option){ ?>
									<option value="<?php echo $key; ?>" <?php if($key == $value) {echo 'selected="selected"';}  ?>><?php echo $_option; ?></option>
							<?php	} ?>
					</select>
					<br />
					<span class="description"><?php echo __( 'Select Stamp position.', 'dxinvoice' ) ?></span>
				</td>
			 </tr>
			<?php 
			$output = ob_get_clean();
			echo apply_filters( 'dx_invoice_filter_invoices_table', $output );
		}
	}
	
	
	/**
	 * Add field for displaying customers on the Invoice form 
	 * 
	 * @param $type field type (text, dx_invoicer_form_field, select, textarea...)
	 * @param $item the item name
	 * @param $attributes array with attributes
	 * @param $method HTTP method where data is stored
	 * @param $section_prefix a prefix for the section, if any
	 * @param $id_prefix a prefix for IDs, if any
	 */
	public function add_custom_templates ( $type, $item, $attributes, $method, $section_prefix, $id_prefix ) {
		
		if( $type == 'dx_custom_templates' ) {
			
			//extract( $attributes );
			extract( array_merge ($attributes, DX_Form_Helper::get_element_attributes( $item, $attributes, $method ) ) );
			$label = !empty($label)	? $label	:"";
		    $type  = !empty($type)	? $type		:"";
		    $name  = !empty($name)	? $name		:"";
		    $value = !empty($value)	? $value	:"";
		    $text  = !empty($text)	? $text		:"";
		    $id    = !empty($id)	? $id		:"";
		    $class = !empty($class)	? $class	:"";
		    $style = !empty($style)	? $style	:"";
		    $desc  = !empty($desc)	? $desc		:"";
			$initial_rows = 0;
			$current_user_id = get_current_user_id();
			
			$files= DX_INV_DIR."/templates";
			$dir = "";
			$pred = scandir($files);
			foreach ($pred as $key => $rowvalue){
				if (!in_array($rowvalue,array(".",".."))){
					if (is_dir($dir . DIRECTORY_SEPARATOR . $rowvalue)){
						$result[$rowvalue] = dirToArray($dir . DIRECTORY_SEPARATOR . $rowvalue);
					}else{
						$result[] = $rowvalue;
					}
				}
			} 
			ob_start();
			
			?>
			<tr>
				<th scope="row">
					<label for="<?php echo $id_prefix . $id ?>"><?php echo $text ?></label>	
				</th>
				<td><select name="<?php echo $name ?>" id="<?php echo $id_prefix . $id ?>" <?php echo ($type == 'multiselect' ? 'multiple="multiple"' : '') ?> >
						<option id="dx_empty_customer" value=""><?php _e('Pick an existing template', 'dxinvoice'); ?></option>						
						<?php
							foreach($result as $singlefile){ ?>
								<option value="<?php echo $singlefile; ?>" <?php if($singlefile == $value) {echo 'selected="selected"';}  ?>><?php echo $singlefile; ?></option>
						<?php	} ?>
					</select><br />
					<span class="description"><?php echo __( 'Add template if not exist.', 'dxinvoice' ) ?></span>
				</td>
			 </tr>
			<?php 
			$output = ob_get_clean();
			echo apply_filters( 'dx_invoice_filter_invoices_table', $output );
		}
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

	/**
	 * Remove featured image from post type dx_invoice
	 * @return type
	 */
	public function dx_invoice_remove_image_box() {
	   remove_meta_box('postimagediv','dx_invoice','side');
	}

	/**
	 * Remove featured image from post type dx_customer
	 * @return type
	 */
	public function dx_customer_remove_image_box() {
	   remove_meta_box('postimagediv','dx_customer','side');
	}

	/**
	 * Auto hide customer field in page dx_invoice for all admin  
	 * @param type $user_id 
	 * @return type
	 */
	public function dx_invoice_set_user_metaboxes($user_id = NULL) {

	    // So this can be used without hooking into user_register
	    if ( ! $user_id)
	        $user_id = get_current_user_id(); 

	    if ( get_user_meta( $user_id, 'metaboxhidden_dx_invoice', true) ) {
	        $meta_value = array('postcustom','trackbacksdiv','commentstatusdiv','commentsdiv','slugdiv','authordiv','revisionsdiv');
	        update_user_meta( $user_id, 'metaboxhidden_dx_invoice', $meta_value );
	    }else{
	        $meta_value = array('postcustom','trackbacksdiv','commentstatusdiv','commentsdiv','slugdiv','authordiv','revisionsdiv');
	    	add_user_meta( $user_id, 'metaboxhidden_dx_invoice', $meta_value );
	    }
	}
	/**
	 * Auto hide customer field in page dx_customer for all admin  
	 * @param type $user_id 
	 * @return type
	 */
	public function dx_customer_set_user_metaboxes($user_id = NULL) 
	{
	    // So this can be used without hooking into user_register
	    if ( ! $user_id)
	        $user_id = get_current_user_id(); 

	    if ( get_user_meta( $user_id, 'metaboxhidden_dx_customer', true) ) {
	        $meta_value = array('postcustom','trackbacksdiv','commentstatusdiv','commentsdiv','slugdiv','authordiv','revisionsdiv');
	        update_user_meta( $user_id, 'metaboxhidden_dx_customer', $meta_value );
	    }else{
	        $meta_value = array('postcustom','trackbacksdiv','commentstatusdiv','commentsdiv','slugdiv','authordiv','revisionsdiv');
	    	add_user_meta( $user_id, 'metaboxhidden_dx_customer', $meta_value );
	    }
	}

	/**
	 * save other bank account
	 * @return type
	 */
	public function save_options()
	{
		if(isset($_POST['dx_invoice_options'])){
			
			$option_name = 'dx_company_bank_ac_number_other' ;
			$new_value = $_POST['dx_company_bank_ac_number_other'] ;

			if ( get_option( $option_name ) !== false ) {

			    // The option already exists, so we just update it.
			    update_option( $option_name, $new_value );

			} else {

			    // The option hasn't been added yet. We'll add it with $autoload set to 'yes'.
			    $deprecated = null;
			    $autoload = 'yes';
			    add_option( $option_name, $new_value, $deprecated, $autoload );
			}

		}

	}

	/**
	 * filter to change currency
	 * @return array
	 */
	public function set_currency($currency = array()){

		$currency['bgn'] = __('BGN', 'dxinvoice');
		$currency['eur'] = __('EUR', 'dxinvoice');
		$currency['usd'] = __('USD', 'dxinvoice');
		$currency['idr'] = __('IDR', 'dxinvoice');

		return $currency;
	}
	
}

DX_Form_Filters::instance();
