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
}