<?php

class DX_Customer_Class {
	
	public static $fields;
	
	public function __construct() {
		self::$fields = array(
			'_company_name' => array(
					'label' => __('Company name', 'dxinvoice'),
					'type' => 'text'
				),
			'_company_address' => array(
					'label' => __('Company address', 'dxinvoice'),
					'type' => 'textarea'	
				),
			'_company_number' => array(
					'label' => __('Company unique number', 'dxinvoice'),
					'type' => 'text'	
				),
			'_client_name' => array(
					'label' => __('Responsible person', 'dxinvoice'),
					'type' => 'text'
				),
			'_bank_account' => array(
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
			'map_meta_cap'      => true,
			'capability_type' 	=> DX_CUSTOMER_POST_TYPE,
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
					$custom_value = maybe_unserialize( $custom_value );
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
			<div id="dx-customer-meta-wrapper">
			<table class="form-table"> 
			<tbody>
			<?php 
				foreach( self::$fields as $item => $attributes ) {
					
					echo DX_Form_Helper::html_element($item, $attributes, 'POST');
				}
			?>
			</tbody>
			</table>
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
		if( !isset( $_POST['customer_nonce'] ) || !wp_verify_nonce( $_POST['customer_nonce'], 'customer_nonce_save' ) ) return;
		
		// if our current user can't edit this post, bail
		if( !current_user_can( 'edit_posts' ) ) return;
		
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
	/**
	 * Add Column in listing invoice
	 * 
	 * @package DX Invoice
	 * @since 1.0.0
	 */
	function add_customer_invoice_column($columns) {
		return array_merge( $columns, 
      	array('_total_invoice' => __('Total Invoice','dxinvoice'),
          	 '_total_invoice_amount' => __('Total Invoice Amount','dxinvoice'),
          	 '_total_invoice_unpaid' => __('Total Invoice Unpaid','dxinvoice')));
	}
	/**
	 * Get Total of Invoice
	 * 
	 * @package DX Invoice
	 * @since 1.0.0
	 */

	function dx_display_customer_invoice_total( $column, $post_id ) {
		global $wpdb ;
	    switch ( $column ) {
		case '_total_invoice' :
		   		
		   		 $querystr = "
			    SELECT $wpdb->posts.* 
			    FROM $wpdb->posts, $wpdb->postmeta
			    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
			    AND $wpdb->postmeta.meta_key = '_client' 
			    AND $wpdb->postmeta.meta_value = $post_id 
			    AND $wpdb->posts.post_status = 'publish' 
			    AND $wpdb->posts.post_type = 'dx_invoice'
			    ORDER BY $wpdb->posts.post_date DESC
			 ";
		   		 $pageposts = $wpdb->get_results($querystr, OBJECT);
		    	 echo count($pageposts);
		   		 break;
		
		case '_total_invoice_amount' :
		   		 
		   		 $querystr = "
			    SELECT $wpdb->posts.* 
			    FROM $wpdb->posts, $wpdb->postmeta
			    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
			    AND $wpdb->postmeta.meta_key = '_client' 
			    AND $wpdb->postmeta.meta_value = $post_id 
			    AND $wpdb->posts.post_status = 'publish' 
			    AND $wpdb->posts.post_type = 'dx_invoice'
			    ORDER BY $wpdb->posts.post_date DESC
			 ";
		   		 $pageposts = $wpdb->get_results($querystr, OBJECT);
		    	
			    $amount_invoice = array();
			    foreach ( $pageposts as $key => $project ){
			    	
			    	$amount_invoice[] = get_post_meta($project->ID,'_amount',true);
			    }
		   		
			   	echo array_sum($amount_invoice);
		   		 break;
		case '_total_invoice_unpaid' :
		   		 
		   		 $querystr = "
			    SELECT $wpdb->posts.* 
			    FROM $wpdb->posts, $wpdb->postmeta
			    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
			    AND $wpdb->postmeta.meta_key = '_client' 
			    AND $wpdb->postmeta.meta_value = $post_id 
			    AND $wpdb->posts.post_status = 'publish' 
			    AND $wpdb->posts.post_type = 'dx_invoice'
			    ORDER BY $wpdb->posts.post_date DESC
			 ";
		   		 $pageposts = $wpdb->get_results($querystr, OBJECT);
		    	
			    $total_unpid = 0;
			    foreach ( $pageposts as $key => $project ){
			    	if(get_post_meta($project->ID,'_dx_status_invoice',true) == 'unpaid' )
			    	{
			    		$total_unpid += 1;
			    	}
			    }
		   		
			   	echo "<a href='edit.php?orderby=customer&post_status=all&post_type=dx_invoice&customer_id={$post_id}&invoice_status=unpaid'>".$total_unpid.'</a>';
		   		break;
	    }
	}
	
	 
	/**
	 * Include setting page
	 /**
	 * @package DX Invoice
	 * @since 1.0.0
	 */
	public function dx_customer_settings(){
		include_once DX_INV_DIR.'/helpers/customer-settings.php';
	}
	
	/**
	 * Register setting Option
	 * 
	 * @package DX Invoice
	 * @since 1.0.0
	 */
	public function dx_customer_admin_init() {
		
		register_setting( 'customer_plugin_options', 'dx_customer_options' );
		
	}
	/**
	 * Customer Column sortable
	 * 
	 * Handles to filter the data by customer
	 * 
	 * @package DX Invoice
	 * @since 1.0.0
	 **/
	
	public function dx_cus_register_column_sortable($newcolumn) {
		  
		$newcolumn['_total_invoice']  = 'customer';
		$newcolumn['_total_invoice_amount']  = 'invoiceamount';
		$newcolumn['_total_invoice_unpaid']  = 'invoiceunpaid';
		return $newcolumn;
	}
}