<?php

class DX_Invoice_Class {
	
	public static $fields;
	
	public function __construct() {
		self::$fields = array(
				'invoice_number' => array(
						'label' => __('Invoice Number', 'dxinvoice'),
						'type' => 'text'
				),
				'client' => array( // would be with suggestion and 'add new'
						'label' => __('Client', 'dxinvoice'),
						'type' => 'text'
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
	    	'dx_invoice_box',
	    	__( 'DX Invoice Box', 'dxinvoice' ), 
	    	array( __CLASS__, 'bottom_invoice_meta_box' ),
	    	'dx_invoice'
	    );
	}
	
	/**
	 * Implementation for the meta box
	 */
	public static function bottom_invoice_meta_box( $post, $metabox )  {
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