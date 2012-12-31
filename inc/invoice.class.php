<?php

class DX_Invoice_Class {
	
	public static $fields;
	public static $row_fields;
	
	public function __construct() {
		self::$fields = array(
				'invoice_number' => array(
						'label' => __('Invoice Number', 'dxinvoice'),
						'type' => 'text'
				),
				'client' => array( // would be with suggestion and 'add new'
						'label' => __('Client', 'dxinvoice'),
						'type' => 'dx_customer_field'
				),
				'amount'  => array(
						'label' => __('Amount', 'dxinvoice'),
						'type' => 'text'
				),
				'amount_text' => array(
						'label' => __('Amount (in words)', 'dxinvoice'),
						'type' => 'text'
				),
				'currency' => array(
						'label' => __('Currency', 'dxinvoice'),
						'type' => 'select',
						'options' => array(
								'bgn' => __('BGN', 'dxinvoice'),
								'eur' => __('EUR', 'dxinvoice'),
								'usd' => __('USD', 'dxinvoice')
						)
				),
				'description' => array(
						'label' => __('Details of the payment', 'dxinvoice'),
						'type' => 'textarea'
				),
				'date_of_execution' => array(
						'label' => __('Date of execution', 'dxinvoice'),
						'type' => 'date'
				)
		);
		
		self::$row_fields = array(
				'items_table' => array(
						'label' => __('Items Table', 'dxinvoice'),
						'type' => 'dx_invoicer_form_field'
				)
			);
	}
	
	/**
	 * Register Invoice custom post type
	 */
	public static function register_invoice_cpt() {
		register_post_type( 'dx_invoice', array(
			'labels' => array(
				'name' => __('Invoices', 'dxinvoice'),
				'singular_name' => __('Invoice', 'dxinvoice'),
				'add_new' => _x('Add New', 'pluginbase', 'dxinvoice' ),
				'add_new_item' => __('Add New Invoice', 'dxinvoice' ),
				'edit_item' => __('Edit Invoice', 'dxinvoice' ),
				'new_item' => __('New Invoice', 'dxinvoice' ),
				'view_item' => __('View Invoice', 'dxinvoice' ),
				'search_items' => __('Search Invoices', 'dxinvoice' ),
				'not_found' =>  __('No Invoices found', 'dxinvoice' ),
				'not_found_in_trash' => __('No Invoices found in Trash', 'dxinvoice' ),
			),
			'description' => __('Invoices for the demo', 'dxinvoice'),
			'public' => false,
			'publicly_queryable' => false,
			'query_var' => true,
			'rewrite' => true,
			'exclude_from_search' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 46,
			'supports' => array(
				'title',
				'thumbnail',
				'custom-fields',
				'page-attributes',
			),
		));
	}
	
	/**
	 * Register function for the meta box for Invoice CPT
	 */
	public static function register_invoice_custom_meta( ) {
		add_meta_box(
			'dx_item_rows_box',
			__( 'DX Item Rows Box', 'dxinvoice' ),
			array( __CLASS__, 'top_item_rows_meta_box' ),
			'dx_invoice'
		);
		
		add_meta_box(
	    	'dx_invoice_box',
	    	__( 'DX Invoice Box', 'dxinvoice' ), 
	    	array( __CLASS__, 'bottom_invoice_meta_box' ),
	    	'dx_invoice'
	    );
	}
	
	
	/**
	 * Implement rows for item insertion 
	 * @param $post post object
	 * @param $metabox metabox object
	 */
	public static function top_item_rows_meta_box( $post, $metabox ) {
		$custom = get_post_custom( $post->ID );
		
		// Try to fetch existing items values
		$items_values = array();
		if( ! empty( $custom['dx_invoice_items'] ) && is_array( $custom['dx_invoice_items'] ) ) {
			$items_values = @unserialize($custom['dx_invoice_items'][0]);
		}
		
	?>
		<div id="dx-items-table-meta-wrapper">
		<?php 
			foreach( self::$row_fields as $item => $attributes ) {
				$attributes['value'] = $items_values;
				echo DX_Form_Helper::html_element($item, $attributes, 'POST');
			}
		?>
		</div>
	<?php 
	}
	
	/**
	 * Implementation for the arguments meta box
	 */
	public static function bottom_invoice_meta_box( $post, $metabox )  {
		$custom = get_post_custom( $post->ID );
		
		// fill a new array with the existing values
		// very annoying conversions, but we could live with that.
		$meta_array = array();
		foreach( self::$fields as $key => $args ) {
			if( ! empty( $custom[$key] ) && is_array( $custom[$key] ) ) {
				$custom_value = $custom[$key][0];
				if( is_serialized( $custom_value ) ) {
					// I don't like the @ either, but sometimes it's just making the output safe.
					$custom_value = @unserialize( $custom_value );
					if( is_array( $custom_value ) ) {
						$custom_value = $custom_value[0];
					}
				}

				$meta_array[$key] = $custom_value;
			}
		}   
			
		$_POST = array_merge($_POST, $meta_array );
		
		wp_nonce_field( 'invoice_nonce_save', 'invoice_nonce' );
	?>	
		<div id="dx-invoice-meta-wrapper">
		<?php 
			foreach( self::$fields as $item => $attributes ) {
				echo DX_Form_Helper::html_element($item, $attributes, 'POST');
			}
		?>
		</div>
	<?php 
	}
	
	/**
	 * Manage post save
	 * @param $post_id integer. ID of current post
	 * @param $post post object
	 */
	public static function save_invoice_post( $post_id ) {
		// Avoid autosaves
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
			
		// Verify nonces for ineffective calls
		if( !isset( $_POST['invoice_nonce'] ) || !wp_verify_nonce( $_POST['invoice_nonce'], 'invoice_nonce_save' ) ) return;
		
		// if our current user can't edit this post, bail
		if( !current_user_can( 'edit_post' ) ) return;

		$rows = !empty( $_POST['dx_invoice_rows_number'] ) ? (int) $_POST['dx_invoice_rows_number'] : 0;

		if( is_numeric( $rows ) ) {
			$form_filters = DX_Form_Filters::instance();
			$cols = $form_filters->cols;
			
			// Handle table
			$results = self::handle_columns_table( $cols, $rows );
			
			update_post_meta( $post_id, 'dx_invoice_items', $results ); 
		}
		
		foreach( self::$fields as $key => $args ) {
			if( ! empty( $_POST[$key] ) ) {
				update_post_meta( $post_id, $key, $_POST[$key] );
			}
		}
		
	}
	
	/**
	 * Store columns table (rows by cols)
	 * 
	 * @param $cols
	 * @param $rows
	 */
	public static function handle_columns_table( $cols, $rows ) {
		$results = array();	
		
		for( $i = 0; $i < $rows; $i++ ) {
			$row = array();
			
			foreach( $cols as $key => $col ) {
				// if we have the row value, get the current one
				if( isset( $_POST[$key][$i] ) )	{
					// store the current one in the row array
					$row[$key] = $_POST[$key][$i];
				}
			}
			$results[] = $row;
		}
		
		return $results;
	}
}