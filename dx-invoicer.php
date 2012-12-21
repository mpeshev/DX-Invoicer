<?php
/**
 * Plugin Name: DX Invoicer
 * Description: Invoice manager for WordPress, includes user and invoice management, templating and exports
 * Version: 0.1
 * License: GPLv2
 * 
 */

// Defines
// ....


if( !class_exists( 'DX_Invoicer' ) ) {
	class DX_Invoicer {
		/**
		 * Constructor
		 */
		public function __construct() {
			$this->include_files();
			$this->register_cpts();
			$this->prepare_hooks();
		}
		
		/**
		 * Include helper files for the plugin
		 */
		public function include_files() {
 			require_once 'inc/invoice.class.php';
 			require_once 'inc/customer.class.php';
 			require_once 'helpers/form-helper.php';
 			new DX_Invoice_Class();
 			new DX_Customer_Class();
		}
		
		/**
		 * Hook to existing actions and filters 
		 */
		public function prepare_hooks() {
			
		}
		
		public function register_cpts() {
			add_action( 'init', array( DX_Invoice_Class, 'register_invoice_cpt' ), 10 );
			add_action( 'init', array( DX_Customer_Class, 'register_customer_cpt' ), 10 );
			
			add_action( 'add_meta_boxes', array( DX_Invoice_Class, 'register_invoice_custom_meta' ), 12 );
			add_action( 'add_meta_boxes', array( DX_Customer_Class, 'register_customer_custom_meta' ), 12 );
		}
		
	}
	
	new DX_Invoicer();
}