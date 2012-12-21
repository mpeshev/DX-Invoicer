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
			$this->enqueue_scripts_styles();
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
		 * Prepare scripts and styles, yo!
		 */
		public function enqueue_scripts_styles() {
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_styles' ) );
		}
		
		public function admin_enqueue_styles( $hook ) {
			if( $hook == 'edit.php' || $hook == 'post-new.php' ) {
				wp_enqueue_style( 'dx-invoicer-post-screens', plugins_url( '/css/dx-invoicer-post-screens.css', __FILE__ ), array(), '1.0', 'screen' );
			} else if( $hook == 'dx-invoicer' ) { // TODO: is this a valid hook?
				wp_enqueue_style( 'dx-invoicer-admin', plugins_url( '/css/dx-invoicer-admin.css', __FILE__ ), array(), '1.0', 'screen' );
			}
		}

		public function wp_enqueue_styles() {
			wp_enqueue_style( 'dx-invoicer', plugins_url( '/css/dx-invoicer.css',_FILE_), array(), '1.0', 'screen' );
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