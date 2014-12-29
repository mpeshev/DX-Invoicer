<?php

class DX_Invoice_Class {
	
	public static $fields;
	public static $row_fields;
	
	public function __construct() {
		self::$fields = array(
				'_invoice_number' => array(
						'label' => __('Invoice Number', 'dxinvoice'),
						'type' => 'text',
						'desc' => 'Enter Invoice Number'
				),
				'_client' => array( // would be with suggestion and 'add new'
						'label' => __('Client', 'dxinvoice'),
						'type' => 'dx_customer_field',
						'desc' => 'Select Client'
				),
				'_page_templates' => array( // would be with suggestion and 'add new'
						'label' => __('Page Templates', 'dxinvoice'),
						'type' => 'dx_custom_templates',
						'desc' => 'Select Page Template'
				),
				'_amount'  => array(
						'label' => __('Amount', 'dxinvoice'),
						'type' => 'text',
						'desc' => 'Enter Amount'
				),
				'_amount_text' => array(
						'label' => __('Amount (in words)', 'dxinvoice'),
						'type' => 'text',
						'desc' => 'Enter Amount In Word'
				),
				'_currency' => array(
						'label' => __('Currency', 'dxinvoice'),
						'type' => 'select',
						'desc' => 'Select Currency',
						'options' => array(
								'bgn' => __('BGN', 'dxinvoice'),
								'eur' => __('EUR', 'dxinvoice'),
								'usd' => __('USD', 'dxinvoice')
						)
				),
				'_description' => array(
						'label' => __('Details of the payment', 'dxinvoice'),
						'type' => 'textarea',
						'desc' => 'Enter Payment Detail'
				),
				'_date_of_execution' => array(
						'label' => __('Date of execution', 'dxinvoice'),
						'type' => 'date',
						'desc' => 'Select Date'
				),
				'_invoice_stamp_img' => array(
						'label' => __('Invoice Stamp', 'dxinvoice'),
						'type' => 'image',
						'desc' => 'Upload Stamp'
				),
				'_invoice_signature_img' => array(
						'label' => __('Invoice Signature', 'dxinvoice'),
						'type' => 'image',
						'desc' => 'Upload Signature'
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
			} else {
				if($key =='_invoice_number'){
					$invoice_title =  get_option( 'dx_invoice_options' );
					$increment = isset($invoice_title['increment'])?$invoice_title['increment']:0;
					$meta_array[$key] = isset($invoice_title['invoice_num'])? $invoice_title['invoice_num'] + $increment :"";
					// Check existing		
						$my_query = new WP_Query( 
						    array(
						      'post_type' => 'dx_invoice',
						      'post__not_in'=> array($post->ID),
						      'meta_query' => array(
						        array(
						          'key' => '_invoice_number',
						          'value' => $meta_array[$key]
						        )
						      ),
						    ) 
						  );
						  if(count($my_query->posts) != 0 ){
							  	global $wpdb;
							    $query = "SELECT max(meta_value) FROM wp_postmeta WHERE meta_key='_invoice_number'";
							    $the_max = $wpdb->get_var($query);
							    $meta_array[$key] =  $the_max + $increment ;
						  }
				}
			}
		}   
			
		$_POST = array_merge($_POST, $meta_array );
		
		wp_nonce_field( 'invoice_nonce_save', 'invoice_nonce' );
		
	?>	
		<div id="dx-invoice-meta-wrapper">
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
	public static function save_invoice_post( $post_id ) {
		
		// Avoid autosaves
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
			
		// Verify nonces for ineffective calls
		if( !isset( $_POST['invoice_nonce'] ) || !wp_verify_nonce( $_POST['invoice_nonce'], 'invoice_nonce_save' ) ) return;
		
		// if our current user can't edit this post, bail
		if( !current_user_can( 'edit_post' ) ) return;

		$rows = !empty( $_POST['dx_invoice_rows_number'] ) ? (int) $_POST['dx_invoice_rows_number'] : 0;
		$publish 	= isset($_POST['publish'])	?	$_POST['publish']	:"";
		$save 		= isset($_POST['save'])		?	$_POST['save']		:"";
		
		// Update Invoice in setting
		if($publish == 'Publish' || $save  == 'Update'){
			$invoice_num = isset($_POST['_invoice_number'])? $_POST['_invoice_number']:"";
			// Checking post invoice number
			$my_query = new WP_Query( 
			    array(
			      'post_type' => 'dx_invoice',
			      'post__not_in'=> array($post_id),
			      'meta_query' => array(
			        array(
			          'key' => '_invoice_number',
			          'value' => $invoice_num
			        )
			      ),
			    ) 
			  );
			  
			if(count($my_query->posts) == 0 ){
				
				$old_invoice	= get_option( 'dx_invoice_options' );
				$old_invoice["invoice_num"] = $invoice_num;
				update_option( 'dx_invoice_options', $old_invoice );
			}
			else{ 
				
				 wp_redirect(wp_get_referer()."&message=99"); exit;
			}			
		}
		
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
	
	 /**
	 * Add menu/Submenu
	 /**
	 * @package DX Invoice
	 * @since 1.0.0
	 */
	public function dx_invoice_add_menu_page() { 
		
		$dx_invoice_settings = add_menu_page( __( 'Invoice Settings', 'dxinvoice' ), __( 'Invoice Settings', 'dxinvoice' ), 'manage_options','dx_invoice_settings', array($this, 'dx_invoice_settings') );
	    //add_action( "admin_head-$dx_invoice_settings", array( $this, 'dx_invoice_settings_scripts' ) );
	}
	 /**
	 * Add menu/Submenu
	 /**
	 * @package DX Invoice
	 * @since 1.0.0
	 */
	public function dx_invoice_settings(){
		include_once DX_INV_DIR.'/helpers/invoice-settings.php';
	}
		
	/**
	 * Register setting Option
	 * 
	 * @package DX Invoice
	 * @since 1.0.0
	 */
	public function dx_invoice_admin_init() {
		
		register_setting( 'invoice_plugin_options', 'dx_invoice_options' );
		
	}
	/**
	 * Register setting Option
	 * 
	 * @package DX Invoice
	 * @since 1.0.0
	 */
	function dx_invoice_error_notice(){
     global $current_screen; 
     $message = isset($_REQUEST['message'])?$_REQUEST['message']:"";
     if ( $current_screen->parent_base == 'edit' && $message == 99 )
      	echo '<div class="error"><p>'.__('Warning - Invoice Number is already exist','dxinvoice').'</p></div>';
	 if ( $current_screen->parent_base == 'edit' && $message == 98 )
	  echo '<div class="error"><p>'.__('Warning - Please select customer before print','dxinvoice').'</p></div>';
	}
	
	/**
	 * Add button above post
	 * 
	 * @package DX Invoice
	 * @since 1.0.0
	 */
	function dx_top_form_edit( $post ) {
		$preview = "";
	    if( 'dx_invoice' == $post->post_type ){
	       $preview = add_query_arg( array( 'post_type' => $post->post_type, 'dx_action_validate' => 'generate-pdf', 'post_ID' => $post->ID ), admin_url( 'edit.php' ) ); 
	        echo '	<input type="hidden" name="dx_action_validate" value="0">
	        		<input type="hidden" name="dx_action" value="generate-pdf">
	        		<a type="submit" class="dx-pdf-generate button" id="" href="'.$preview.'">'.__('Generate PDF/Print','dxinvoice').'</a>
	        	';
	    }
	}
	/**
	 * Register setting Option
	 * 
	 * @package DX Invoice
	 * @since 1.0.0
	 */		
	function dx_pdf_form_load(){
		$post_id = isset($_REQUEST['post_ID'])?$_REQUEST['post_ID']:"";
		$action = isset($_REQUEST['dx_action'])?$_REQUEST['dx_action']:"";
		$action_validate = isset($_REQUEST['dx_action_validate'])?$_REQUEST['dx_action_validate']:"";
		
		if($action_validate == 'generate-pdf' ){
			//include_once DX_INV_DIR.'/inc/pdf-library/pdf-template-generate.php';
			include_once DX_INV_DIR.'/inc/pdf-library/dx-pdf-process.php';
			//dx_invoice_to_pdf();
		}
	}
	
}