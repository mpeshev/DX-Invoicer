<?php

class DX_Customer_Class {
	
	public static $fields;
	
	public function __construct() {
		self::$fields = array(
			'company_name' => array(
					'label' => __('Company name', 'dxinvoice'),
					'type' => 'text'
				),
			'company_address' => array(
					'label' => __('Company address', 'dxinvoice'),
					'type' => 'textarea'	
				),
			'company_number' => array(
					'label' => __('Company unique number', 'dxinvoice'),
					'type' => 'text'	
				),
			'client_name' => array(
					'label' => __('Responsible person', 'dxinvoice'),
					'type' => 'text'
				),
			'bank_account' => array(
					'label' => __('Bank Account Number', 'dxinvoice'),
					'type' => 'text'
				),
		);
	}
	
	public static function register_customer_cpt() {
		register_post_type( 'dx_customer', array(
			'labels' => array(
				'name' => __('Customers', 'dxinvoice'),
				'singular_name' => __('Customer', 'dxinvoice'),
				'add_new' => _x('Add New', 'pluginbase', 'dxinvoice' ),
				'add_new_item' => __('Add New Customer', 'dxinvoice' ),
				'edit_item' => __('Edit Customer', 'dxinvoice' ),
				'new_item' => __('New Customer', 'dxinvoice' ),
				'view_item' => __('View Customer', 'dxinvoice' ),
				'search_items' => __('Search Customers', 'dxinvoice' ),
				'not_found' =>  __('No Customers found', 'dxinvoice' ),
				'not_found_in_trash' => __('No Customers found in Trash', 'dxinvoice' ),
			),
			'description' => __('Customers for the demo', 'dxinvoice'),
			'public' => false,
			'publicly_queryable' => false,
			'query_var' => true,
			'rewrite' => true,
			'exclude_from_search' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 47, // probably have to change, many plugins use this
			'supports' => array(
				'title',
// 				'editor',
				'thumbnail',
				'custom-fields',
				'page-attributes',
			),
		));
	}
	
	/**
	 * Register function for the meta box for Customer CPT
	 */
	public static function register_customer_custom_meta( ) {
		add_meta_box(
			'dx_customer_box',
			__( 'DX Customer Box', 'dxinvoice' ),
			array( __CLASS__, 'bottom_customer_meta_box' ),
			'dx_customer'
		);
	}
	
	/**
	 * Implementation for the customer meta box
	 */
	public static function bottom_customer_meta_box( $post, $metabox )  {
		
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
		
		wp_nonce_field( 'customer_nonce_save', 'customer_nonce' );
		
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
	public static function save_customer_post( $post_id ) {
		// Avoid autosaves
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	
		// Verify nonces for ineffective calls
		//if( !isset( $_POST['customer_nonce'] ) || !wp_verify_nonce( $_POST['customer_nonce'], 'customer_nonce' ) ) return;
		
		// if our current user can't edit this post, bail
		//if( !current_user_can( 'editor' ) ) return;
		
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