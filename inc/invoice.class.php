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
				'_vat_text' => array(
						'label' => __('VAT (in &#37;)', 'dxinvoice'),
						'type' => 'text',
						'desc' => 'Enter VAT In &#37;'
				),
				'_currency' => array(
						'label' => __('Currency', 'dxinvoice'),
						'type' => 'select',
						'desc' => 'Select Currency',
						'options' => apply_filters('dx_invoice_setting_currency',array())
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
				'_stamp_position' => array(
						'label' => __('Stamp Position', 'dxinvoice'),
						'type' => 'stamp_position',
						'desc' => 'Select Stamp Position'
				),
				'_invoice_signature_img' => array(
						'label' => __('Invoice Signature', 'dxinvoice'),
						'type' => 'image',
						'desc' => 'Upload Signature'
				),
				'_dx_status_invoice' => array(
						'label' => __('Status Invoice', 'dxinvoice'),
						'type' => 'status_invoice',
						'desc' => 'Select Status Invooce'
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
				'labels' 			=> array(
				'name' 				=> __('Invoices', 'dxinvoice'),
				'singular_name' 	=> __('Invoice', 'dxinvoice'),
				'add_new' 			=> _x('Add New', 'pluginbase', 'dxinvoice' ),
				'add_new_item' 		=> __('Add New Invoice', 'dxinvoice' ),
				'edit_item' 		=> __('Edit Invoice', 'dxinvoice' ),
				'new_item' 			=> __('New Invoice', 'dxinvoice' ),
				'view_item' 		=> __('View Invoice', 'dxinvoice' ),
				'search_items' 		=> __('Search Invoices', 'dxinvoice' ),
				'not_found' 		=>  __('No Invoices found', 'dxinvoice' ),
				'not_found_in_trash'=> __('No Invoices found in Trash', 'dxinvoice' ),
			),
				'description' 		=> __('Invoices for the demo', 'dxinvoice'),
				'public' 			=> true,
				'publicly_queryable'=> true,
				'query_var'			=> true,
				'map_meta_cap'      => true,
				'capability_type' 	=> DX_INV_POST_TYPE,
				'exclude_from_search' => true,
				'rewrite' 			=> array( 'slug' => 'dxinvoice'),
				'show_ui' 			=> true,
				'show_in_admin_bar'     => true,
				'show_in_menu' 		=> true,
				'menu_position' 	=> 46,
				'supports' 			=> array(
										'title',
										'thumbnail',
										'custom-fields',
										'page-attributes',
									),
			));
			flush_rewrite_rules();
	}


	/**
 * Message Filter
 *
 * Add filter to ensure the text Review, or review, 
 * is displayed when a user updates a custom post type.
 *
 * @package Poll
 * @since 1.0.0
 */  
function dx_updated_messages( $messages ) {
		
	global $post, $post_ID;

	$messages[DX_INV_POST_TYPE] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __( 'Invoice updated.', 'dxinvoice' )),
		2 => __( 'Custom field updated.', 'dxinvoice' ),
		3 => __( 'Custom field deleted.', 'dxinvoice' ),
		4 => __( 'Invoice updated.', 'dxinvoice' ),
	);

	return $messages;
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
			$items_values = maybe_unserialize($custom['dx_invoice_items'][0]);
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
					$custom_value = maybe_unserialize( $custom_value );
					if( is_array( $custom_value ) ) {
						$custom_value = $custom_value[0];
					}
				}

				$meta_array[$key] = $custom_value;
			} else {
				if($key =='_invoice_number'){
					$invoice_title =  get_option( 'dx_invoice_options' );
					$increment = !empty($invoice_title['increment'])?$invoice_title['increment']:1;
					$meta_array[$key] = !empty($invoice_title['invoice_num'])? $invoice_title['invoice_num'] + $increment :1;
					// Check existing		
						$my_query = new WP_Query( 
						    array(
						      'post_type' => DX_INV_POST_TYPE,
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
		if( !current_user_can( 'edit_post'  , $post_id)) return;

		$rows = !empty( $_POST['dx_invoice_rows_number'] ) ? (int) $_POST['dx_invoice_rows_number'] : 0;
		$publish 	= isset($_POST['publish'])	?	$_POST['publish']	:"";
		$save 		= isset($_POST['save'])		?	$_POST['save']		:"";
		
		// Update Invoice in setting
		if($publish == 'Publish' || $save  == 'Update'){
			$invoice_num = isset($_POST['_invoice_number'])? $_POST['_invoice_number']:"";
			// Checking post invoice number
			$my_query = new WP_Query( 
			    array(
			      'post_type' => DX_INV_POST_TYPE,
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
	 * Add Invoice Setting
	 * 
	 * @package DX Invoice
	 * @since 1.0.0
	 */
	public function dx_invoice_add_menu_page() { 
		
		$dx_invoice_settings = add_menu_page( __( 'Invoice Settings', 'dxinvoice' ), __( 'Invoice Settings', 'dxinvoice' ), 'manage_options','dx_invoice_settings', array($this, 'dx_invoice_settings') );
		add_submenu_page( 'dx_invoice_settings', __( 'Google Contact', 'dxinvoice' ), __( 'Google Contact', 'dxinvoice' ), 'manage_options','dx_invoice_google_settings', array($this, 'dx_invoice_google_settings') );
		add_submenu_page( 'dx_invoice_settings',  __( 'Outlook Contact', 'dxinvoice' ), __( 'Outlook Contact', 'dxinvoice' ), 'manage_options', 'dx_invoice_outlook_settings', array($this, 'dx_invoice_outlook_contact') );

		//add_action( "admin_head-$dx_invoice_settings", array( $this, 'dx_invoice_settings_scripts' ) );
	}
	 /**
	 * Add menu/Submenu
	 *
	 * @package DX Invoice
	 * @since 1.0.0
	 */
	public function dx_invoice_settings(){
		include_once DX_INV_DIR.'/helpers/invoice-settings.php';
	}
	/**
	 * Add menu/Submenu
	 *
	 * @package DX Invoice
	 * @since 1.0.0
	 */
	public function dx_invoice_google_settings(){
		include_once DX_INV_DIR.'/helpers/invoice-google-settings.php';
	}
	/**
	 * Add menu/Submenu
	 *
	 * @package DX Invoice
	 * @since 1.0.0
	 */
	public function dx_invoice_outlook_contact	(){
		include_once DX_INV_DIR.'/helpers/invoice-outlook-settings.php';
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
		$preview1 = "";
	    if( DX_INV_POST_TYPE == $post->post_type ){
			$dxinvoice_item_row_data = get_post_meta($post->ID,'dx_invoice_items',true);
			if($dxinvoice_item_row_data)
			{
		       	$preview  = add_query_arg( array( 'post_type' => DX_INV_POST_TYPE, 'dx_action_validate' => 'generate-pdf', 'post_ID' => $post->ID ), admin_url( 'edit.php' ) ); 
		       	$preview1 = add_query_arg( array( 'post_type' => DX_INV_POST_TYPE, 'dx_action_validate' => 'download-pdf', 'post_ID' => $post->ID ), admin_url( 'edit.php' ) ); 
				$disabled = '';
			}else{
		       	$preview  = 'javascript: void(0)'; 
		       	$preview1 = 'javascript: void(0)';
				$disabled = 'disabled';
			}
	        
	        echo '	
	        		<a type="submit" class="dx-pdf-generate button '.$disabled.'" id="" href="'.$preview.'">'.__('Preview Invoice','dxinvoice').'</a>
	        	';
	        echo '	
	        		<a type="submit" class="dx-pdf-generate button '.$disabled.'" id="" href="'.$preview1.'">'.__('Download Invoice','dxinvoice').'</a>
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
			$dxinvoice_item_row_data = get_post_meta($post_id,'dx_invoice_items',true);
			if(empty($dxinvoice_item_row_data))
			{
				echo"<div class='error'><p>Row Box item cannot be emptied to generate / preview invoice.</p></div>";
				die();
			}else{
				// I for preview
				$pdf_view_type = 'I';
				//include_once DX_INV_DIR.'/inc/pdf-library/pdf-template-generate.php';
				include_once DX_INV_DIR.'/inc/pdf-library/dx-pdf-process.php';
				//dx_invoice_to_pdf();
			}
		
		}
		if($action_validate == 'download-pdf' ){
			$dxinvoice_item_row_data = get_post_meta($post_id,'dx_invoice_items',true);
			if(empty($dxinvoice_item_row_data))
			{
				echo"<div class='error'><p>Row Box item cannot be emptied to generate / preview invoice.</p></div>";
				die();
			}else{
				// D for download
				$pdf_view_type = 'D';
				//include_once DX_INV_DIR.'/inc/pdf-library/pdf-template-generate.php';
				include_once DX_INV_DIR.'/inc/pdf-library/dx-pdf-process.php';
				//dx_invoice_to_pdf();
			}
		}
	}
	
	/**
	 * Add Column in listing invoice
	 * 
	 * @package DX Invoice
	 * @since 1.0.0
	 */
	function add_invoice_column($columns) {
		//return array_merge( $columns, 
      	//array('_customer_name' => 'Customer',
          	 //'_invoice_amount' => 'Invoice Amount'));
          	 $columns['_customer_name'] = __('Customer','dxinvoice');
          	 $columns['_invoice_amount'] = __('Invoice Amount','dxinvoice');
          	 $columns['_dx_status_invoice'] = __('Invoice Status','dxinvoice');
          	 return $columns;
	}
	
	/**
	 * Filter Column in listing invoice
	 * 
	 * @package DX Invoice
	 * @since 1.0.0
	 */

	function dx_display_posts( $column, $post_id ) {
	    switch ( $column ) {
			case '_customer_name' :
		   		 $customer_id 	= 	get_post_meta($post_id,'_client',true);
			   	if(!empty($customer_id)){
		   			echo get_the_title( $customer_id );
			   	}
		   		 break;
			case '_invoice_amount' :
			    echo get_post_meta( $post_id , '_amount' , true ); 
		    	break;
			case '_dx_status_invoice' :
			    echo get_post_meta( $post_id , '_dx_status_invoice' , true ) == 'unpaid' ? '<p style="color:red;font-weight:bold;">'.get_post_meta( $post_id , '_dx_status_invoice' , true ).'</p>': '<p style="color:green;font-weight:bold;">'.get_post_meta( $post_id , '_dx_status_invoice' , true ).'</p>' ; 
		    	break;
	    }
	}
	/**
	 * Filter By Customer
	 * 
	 * Handles to filter the data by customer
	 * 
	 * @package DX Invoice
	 * @since 1.0.0
	 **/
	public function dx_invoice_restrict_manage_posts() {
		
		global $wpdb;
		$post_type = isset($_REQUEST['post_type'])?$_REQUEST['post_type']:"";
			
		if ( $post_type == DX_INV_POST_TYPE ) {

			$html = '';
			$customer_id 	  = isset( $_GET['customer_id'] ) ? $_GET['customer_id'] : '';
			$customers_query = new WP_Query(array(
					'post_type' => 'dx_customer',
					'order' => 'ASC',
					'orderby' => 'title'
			));
			$querystr = "
			    SELECT $wpdb->posts.* 
			    FROM $wpdb->posts
			    WHERE $wpdb->posts.post_status = 'publish' 
			    AND $wpdb->posts.post_type = 'dx_customer'
			    ORDER BY $wpdb->posts.post_date DESC
			 ";

			$r_customer = $wpdb->get_results($querystr, OBJECT);
			ob_start();
			?>		
				<select name="customer_id" id="<?php echo the_ID(); ?>">
					<option id="dx_empty_customer" value=""><?php _e('Select Customer', 'dxinvoice'); ?></option>
					<?php 
					foreach ($r_customer as $key => $_customer) {
						?>
						<option id="customer_<?php echo $_customer->ID; ?>" value="<?php echo $_customer->ID; ?>" <?php selected($_customer->ID, $customer_id ); ?>><?php echo $_customer->post_title; ?></option>
						<?php
					}
					?>
				</select>
			<?php
			$html .= ob_get_clean();
			echo  $html;
	    }
	}

	/**
	 * @author Tonjoo
	 * 
	 * Handles to filter the data by status payment
	 * 
	 * @package DX Invoice
	 * @since 1.0.0
	 **/
	public function dx_invoice_restrict_manage_posts_filter_by_status() {
		
		global $wpdb;
		$post_type = isset($_REQUEST['post_type'])?$_REQUEST['post_type']:"";
			
		if ( $post_type == DX_INV_POST_TYPE ) {

			$html = '';
			$payment_status 	  = isset( $_GET['payment_status'] ) ? $_GET['payment_status'] : '';
			$r_status = array(
					'paid' => 'paid',
					'unpaid' => 'unpaid'
				);

			ob_start();
			?>		
				<select name="payment_status" id="payment_status">
					<option id="dx_empty_payment" value=""><?php _e('Select Payment Status', 'dxinvoice'); ?></option>
					<?php 
					foreach ($r_status as $key => $_status) {
						?>
						<option id="payment_status_<?php echo $_status; ?>" value="<?php echo $_status; ?>" <?php selected($_status, $payment_status ); ?>><?php echo $_status; ?></option>
						<?php
					}
					?>
				</select>
			<?php
			$html .= ob_get_clean();
			echo  $html;
	    }
	}

	/**
	 * @author Tonjoo
	 * 
	 * Filter invoice by customer
	 * @param type $query 
	 * @return type
	 */
	public function get_dx_invoice_by_customer($query) {
	    global $pagenow;

	    if (is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type']=='dx_invoice' && !isset($_GET['invoice_status']) )  {

	        if(current_user_can('manage_options')) {
	        	if(isset($_GET['payment_status']) && $_GET['payment_status'] != '')
	        	{
	        		$query_sort[] =  array(
		                    'key'       => '_dx_status_invoice',
		                    'value'     => $_GET['payment_status'],
		                    'compare'   => '='
		                );	         
	        	}
	        	if(isset($_GET['customer_id']) && $_GET['customer_id'] != '')
	        	{
		            $query_sort[] = array(
		                    'key'       => '_client',
		                    'value'     => $_GET['customer_id'],
		                    'compare'   => '='
		            );
	            }	       
	            $query->set('meta_query', $query_sort);
	        }
    	
	    }
	}
	/**
	 * @author Tonjoo
	 * 
	 * Query to unpaid invoices for each customer
	 * @param type $query 
	 * @return type
	 */
	public function get_dx_invoice_by_customer_and_invoice_status($query) {
	    global $pagenow;

	    if (is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type']=='dx_invoice' && isset($_GET['customer_id'])  && isset($_GET['invoice_status']))  {

	        if(current_user_can('manage_options')) {

	            $query->set('meta_query', array(
	                array(
	                    'key'       => '_client',
	                    'value'     => htmlspecialchars($_GET['customer_id']),
	                    'compare'   => '='
	                ),
	                array(
	                    'key'       => '_dx_status_invoice',
	                    'value'     => htmlspecialchars($_GET['invoice_status']),
	                    'compare'   => '='
	                )
	            ));

	        }
    	
	    }
	}

	/**
	 * Handle Filter By Customer
	 * 
	 * Handles to filter the data by customer
	 * 
	 * @package DX Invoice
	 * @since 1.0.0
	 **/
	public function dx_invoice_pre_get_post($query){
		global $wpdb,$current_screen,$pagenow;
		
		$cid = get_current_user_id();
		
		$udata = get_userdata($cid);
		$urole = implode(', ', $udata->roles);
		$company_list = get_the_author_meta( 'company_list', $cid );
		
		if ( isset( $_GET['customer_id'] ) && !empty( $_GET['customer_id'] ) ) {
			$customer_id = $_GET['customer_id'];
			if ( $query->is_main_query() ) {
		        //$query->set( 'meta_key', '_client' );       
		        //$query->set( 'meta_value', $customer_id );       
		    }
		}
		if(DX_CUSTOMER_ROLE == $urole ){
			$query->set( 'meta_key', '_client' );       
		    $query->set( 'meta_value', $company_list ); 
		}
		
	}
	
		
	/**
	 * Invoice PDF preview option
	 * 
	 * Handles to filter the data by customer
	 * 
	 * @package DX Invoice
	 * @since 1.0.0
	 **/
	
	public function dx_invoice_row_actions($actions) {
		global $post;
		
		if ( $post->post_type != DX_INV_POST_TYPE ) {
	        return $actions;
	    }
		$dx_view_pdf = '<a href="'.add_query_arg( array( 'post_type' => DX_INV_POST_TYPE, 'dx_action_validate' => 'generate-pdf', 'post_ID' => $post->ID ), admin_url( 'edit.php' )).'">View PDF</a>';
	    $actions['view_pdf'] = $dx_view_pdf;
	    return $actions;
	}
	/**
	 * Invoice Column sortable
	 * 
	 * Handles to filter the data by customer
	 * 
	 * @package DX Invoice
	 * @since 1.0.0
	 **/
	
	public function dx_inv_register_column_sortable($newcolumn) {
		  
		$newcolumn['_customer_name']  = 'customer';
		$newcolumn['_invoice_amount']  = 'invoiceamount';
		$newcolumn['_dx_status_invoice']  = 'invoicetotalunpaid';
		return $newcolumn;
	}
	/**
	 * Outlook Data Event receive and redirect
	 * 
	 * 
	 * @package DX Invoice
	 * @since 1.0.0
	 **/
	
	public function dx_inv_outlook_data() {
		  
			$outlook_request = isset($_GET['code'])?$_GET['code']:"";
			if(isset($_GET['code']) && !isset($_GET['page'])){
			if(!empty($outlook_request)){
				$dx_invoice_options 	= get_option( 'dx_invoice_options' );
				$dx_outlook_client_id 	= isset($dx_invoice_options['dx_outlook_client_id'])?$dx_invoice_options['dx_outlook_client_id']:"";
				$dx_outlook_client_secret= isset($dx_invoice_options['dx_outlook_client_secret'])?$dx_invoice_options['dx_outlook_client_secret']:"";
				$dx_outlook_callback_url	= isset($dx_invoice_options['dx_outlook_callback_url'])?$dx_invoice_options['dx_outlook_callback_url']:"";
				
				
				$auth_code = $_GET["code"];
				$fields=array(
					'code'=>  urlencode($auth_code),
					'client_id'=>  urlencode($dx_outlook_client_id),
					'client_secret'=>  urlencode($dx_outlook_client_secret),
					'redirect_uri'=>  urlencode($dx_outlook_callback_url),
					'grant_type'=>  urlencode('authorization_code')
				);
				$post = '';
				foreach($fields as $key=>$value) { $post .= $key.'='.$value.'&'; }
				$post = rtrim($post,'&');
				$curl = curl_init();
				curl_setopt($curl,CURLOPT_URL,'https://login.live.com/oauth20_token.srf');
				curl_setopt($curl,CURLOPT_POST,5);
				curl_setopt($curl,CURLOPT_POSTFIELDS,$post);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
				$result = curl_exec($curl);
				curl_close($curl);
				$response =  json_decode($result);
				$accesstoken = isset($response->access_token)?$response->access_token:"";
				$url = 'https://apis.live.net/v5.0/me/contacts?access_token='.$accesstoken.'&limit=100';
				$xmlresponse =  $this->curl_file_get_contents($url);
				$xmldata = json_decode($xmlresponse, true);
				$_SESSION['outlook'] = $xmldata;
				
				wp_redirect(admin_url('admin.php?page=dx_invoice_outlook_settings'));
				exit;
			}
		}
	}
	
	/**
	 * Outlook Data HTML EVENT
	 * 
	 * 
	 * @package DX Invoice
	 * @since 1.0.0
	 *
	 */
	
	function curl_file_get_contents($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	/**
	 * Get auth url for windows live
	 *
	 * @param DX Invoice
	 * @since 1.0.0
	 */	
	public function dx_get_windowslive_auth_url () {
		$dx_invoice_options 	= get_option( 'dx_invoice_options' );
		$dx_outlook_client_id 	= isset($dx_invoice_options['dx_outlook_client_id'])?$dx_invoice_options['dx_outlook_client_id']:"";
		$dx_outlook_client_secret= isset($dx_invoice_options['dx_outlook_client_secret'])?$dx_invoice_options['dx_outlook_client_secret']:"";
		$dx_outlook_callback_url	= isset($dx_invoice_options['dx_outlook_callback_url'])?$dx_invoice_options['dx_outlook_callback_url']:"";
		$dx_authurl = add_query_arg( array(	
											'client_id'		=>	$dx_outlook_client_id,
											'scope'			=>	'wl.signin+wl.basic+wl.emails+wl.contacts_emails',
											'response_type'	=>	'code',
											'redirect_uri'	=>	$dx_outlook_callback_url
										),
									'https://login.live.com/oauth20_authorize.srf' );
		return $dx_authurl;  
		
	}
	
	/**
	 * Renders custom single template for CPT
	 * @param   mixed  $template  The template chosen by WordPress
	 * @return  mixed             The overriden template if the conditions are satisfied or default one chosen by WordPress
	 */
	public function dx_invoice_render_single( $template ) {
		
		if( ! is_single() || DX_INV_POST_TYPE != get_post_type() )
			return $template;
		
			$single = DX_INV_DIR . '/helpers/template/' . DX_INV_POST_TYPE . '-single.php';
			
		if( ! file_exists( $single ) )
			return $template;

		return $single;
	}
	
	/**
	 * Customer Import outlook
	 * 
	 * Handles to filter the data by customer
	 * 
	 * @package DX Invoice
	 * @since 1.0.0
	 **/
	
	public function add_outlook_customer() {
		$customer_name = isset($_POST['customer_name'])?$_POST['customer_name']:"";
		$dx_post = array(
		  'post_title'    => $customer_name,
		  'post_type'	  => DX_CUSTOMER_POST_TYPE	
		);
		  wp_insert_post( $dx_post );
		  echo 1;
		  exit;
	}
	
	/**
	 * Google Contacts curl
	 * 
	 * Handles to filter the data by customer
	 * 
	 * @package DX Invoice
	 * @since 1.0.0
	 **/
	
	public function dx_google_curl() {
		
        $curl = curl_init();
        $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
        curl_setopt($curl,CURLOPT_URL,$url);    //The URL to fetch. This can also be set when initializing a session with curl_init().
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
        curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,5);    //The number of seconds to wait while trying to connect.
        if($post!="")
        {
            curl_setopt($curl,CURLOPT_POST,5);
            curl_setopt($curl,CURLOPT_POSTFIELDS,$post);
        }
        curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);  //The contents of the "User-Agent: " header to be used in a HTTP request.
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);   //To follow any "Location: " header that the server sends as part of the HTTP header.
        curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);  //To automatically set the Referer: field in requests where it follows a Location: redirect.
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);    //The maximum number of seconds to allow cURL functions to execute.
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);  //To stop cURL from verifying the peer's certificate.

        $contents = curl_exec($curl);
        curl_close($curl);
        return $contents;
	}
	/**
	 * Update frontend pdf
	 * @param   mixed  $template  The template chosen by WordPress
	 * @return  mixed             The overriden template if the conditions are satisfied or default one chosen by WordPress
	 */
	public function dx_invoice_update() {
				
		$arrayload = str_replace("\\", "", $_POST["invoicedata"]);
		$invoice_body  =  json_decode($arrayload[0]);
		$count = 0;
		$number = array();
		$invoice_description = array();
		$rate = array();
		$quantity = array();
		$net = array();
		$discount = array();
		$total = array();
		
		foreach($invoice_body as $invoice_row){
			
			foreach ($invoice_row as $invoice_column)
			{
				switch ($count){
					case 0:  
								array_push($number,$invoice_column->index);
					break;
					case 1:   
								array_push($invoice_description,$invoice_column->index);
					break;
					case 2:   
								array_push($rate,$invoice_column->index);
					break;
					case 3:   
								array_push($quantity,$invoice_column->index);
					break;
					case 4:   
								array_push($net,$invoice_column->index);
					break;
					case 5:   
								array_push($discount,$invoice_column->index);
					break;
					case 6:   
								array_push($total,$invoice_column->index);
					break;
				}
				$count++;
			}
			$count = 0;
		}
		$updateval = array();
		for($i = 0; $i < count($number) ; $i++){
			$updateval[$i]['number'] 				= $number[$i];
			$updateval[$i]['invoice_description'] 	= $invoice_description[$i];
			$updateval[$i]['rate'] 					= $rate[$i];
			$updateval[$i]['quantity'] 				= $quantity[$i];
			$updateval[$i]['net'] 					= $net[$i];
			$updateval[$i]['discount'] 				= $discount[$i];
			$updateval[$i]['total'] 				= $total[$i];
		}



		$action = isset($_POST['action'])?$_POST['action']:"";
		if($action == 'dx_invoice_update'){


			$post_id 				=	isset($_POST['dx_page_id'])			?$_POST['dx_page_id']		:"";
	    	$dx_clientname 			= 	isset($_POST['dx_clientname'])		?$_POST['dx_clientname']	:"" ;
		    $data_clientcompany		=	isset($_POST['data_clientcompany'])	?$_POST['data_clientcompany']:"" ;
		    $data_clientcomaddr		=	isset($_POST['data_clientcomaddr'])	?$_POST['data_clientcomaddr']:"" ;
		    $data_clientcomnum 		=	isset($_POST['data_clientcomnum'])	?$_POST['data_clientcomnum']:"" ;
		    $data_contactperson 	=	isset($_POST['data_contactperson'])	?$_POST['data_contactperson']:"";
		    $data_customername		=	isset($_POST['data_customername']) 	?$_POST['data_customername']:"";
		    $data_customercomname	=	isset($_POST['data_customercomname'])?$_POST['data_customercomname'] :"";
		    $data_customercomaddr	=	isset($_POST['data_customercomaddr']) ?$_POST['data_customercomaddr']:"";
		    $data_customercomidno	=	isset($_POST['data_customercomidno'] )?$_POST['data_customercomidno']:"" ;
		    $data_customercomcontactp=	isset($_POST['data_customercomcontactp'])?$_POST['data_customercomcontactp']:"";
		    $data_setting_account	=	isset($_POST['data_setting_account'])?$_POST['data_setting_account']:"";
		    $buttonevent			=	isset($_POST['buttonevent'])		? $_POST['buttonevent']:"";
		    $customerid				=	isset($_POST['customerid'])			? $_POST['customerid']:"";
		    $data_bankacc			=	isset($_POST['data_bankacc'])		?$_POST['data_bankacc']:"" ;
		    $vat_value				=	isset($_POST['vat_value'])		?$_POST['vat_value']:"" ;
		    $customer_post			= array(
								      'ID'           => $customerid,
								      'post_title' => $dx_clientname
			  );
			update_post_meta( $post_id, 'dx_invoice_items', $updateval );   

			update_post_meta( $post_id, '_vat_text', str_replace("%","", $vat_value) );   
		    wp_update_post( $customer_post );

/*
		    update_post_meta( $customerid, '_bank_account', $data_bankacc );
		    update_post_meta( $customerid, '_company_name', $data_clientcompany );
		    update_post_meta( $customerid, '_company_address', $data_clientcomaddr );
		    update_post_meta( $customerid, '_company_number', $data_clientcomnum );
		    update_post_meta( $customerid, '_client_name', $data_contactperson );		   
*/
/*		    $dx_invoice_options['dx_company_person'] 			= $data_customername;
		    $dx_invoice_options['dx_company_name'] 				= $data_customercomname;
		    $dx_invoice_options['dx_company_address'] 			= $data_customercomaddr;
	    	$dx_invoice_options['dx_company_unique_number'] 	= $data_customercomidno;
		    $dx_invoice_options['dx_company_responsible_person']= $data_customercomcontactp;
		    $dx_invoice_options['dx_company_bank_ac_number'] 	= $data_setting_account;

		    update_option('dx_invoice_options',$dx_invoice_options);*/
		    
		    if(isset($_POST['buttonevent']) && $_POST['buttonevent'] == 'saveandGenerate'){
		    	$preview1 = add_query_arg( array( 'post_type' => DX_INV_POST_TYPE, 'dx_action_validate' => 'download-pdf', 'post_ID' => $post_id ), admin_url( 'edit.php' ) ); 
		    	echo $preview1;
		    }
		    exit;
		}
	}
	
	
	
}