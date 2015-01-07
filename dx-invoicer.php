<?php
/**
 * Plugin Name: DX Invoicer
 * Plugin URI: http://devrix.com
 * Description: Invoice manager for WordPress, includes user and invoice management, templating and exports
 * Author: DevriX
 * Author URI: http://devrix.com
 * Version: 0.1
 * Text Domain: dxinvoice
 * Domain Path: /languages
 * License: GPLv2
 * 
 */

// Defines
// ....
global $dx_customer_instance, $dx_invoice_instance,$wp_version;;
if( !defined( 'DX_INV_DIR' ) ) {
	define( 'DX_INV_DIR', dirname( __FILE__ ) ); // plugin dir
}
if( !defined( 'DX_INV_URL' ) ) {
	define( 'DX_INV_URL', plugin_dir_url( __FILE__ ) ); // plugin dir
}
if( !defined( 'DX_INV_POST_TYPE' ) ) {
	define( 'DX_INV_POST_TYPE', 'dx_invoice' ); // plugin dir
}
if( !defined( 'DX_CUSTOMER_POST_TYPE' ) ) {
	define( 'DX_CUSTOMER_POST_TYPE', 'dx_customer' ); // plugin dir
}
if( !defined( 'DX_PREFIX' ) ) {
	define( 'DX_PREFIX', 'dx_' ); // plugin dir
}

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
			global $dx_customer_instance, $dx_invoice_instance;
 			require_once DX_INV_DIR.'/inc/invoice.class.php';
 			require_once DX_INV_DIR.'/inc/customer.class.php';
 			require_once DX_INV_DIR.'/helpers/form-helper.php';
 			require_once DX_INV_DIR.'/helpers/form-filters.php';
 			
 			
 			$dx_invoice_instance = new DX_Invoice_Class();
 			$dx_customer_instance = new DX_Customer_Class();
		}
		
		/**
		 * Prepare scripts and styles, yo!
		 */
		public function enqueue_scripts_styles() {
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_styles' ) );
		}
		
		public function admin_enqueue_styles( $hook ) {
			global $wp_version;
			wp_enqueue_script('jquery');
			
			if( $hook == 'post.php' || $hook == 'post-new.php' ) {
				wp_enqueue_style( 'dx-invoicer-post-screens', plugins_url( '/css/dx-invoicer-post-screens.css', __FILE__ ), array(), '1.0', 'screen' );
				
				// style for datepicker
				global $wp_scripts;
				wp_enqueue_script('jquery-ui-datepicker');
				$ui = $wp_scripts->query('jquery-ui-core');
				$url = "http://ajax.googleapis.com/ajax/libs/jqueryui/{$ui->ver}/themes/smoothness/jquery-ui.css";
				wp_enqueue_style('jquery-ui-smoothness', $url, false, $ui->ver);
				
				wp_enqueue_script( 'dx-invoicer-post-screens', plugins_url( '/js/dx-invoicer-post-screens.js', __FILE__ ), array( 'jquery' ) );
				wp_enqueue_script( 'dx-invoicer-admin', plugins_url( '/js/dx-invoicer-admin.js', __FILE__ ), array( 'jquery' ) );
			} else if( $hook == 'dx-invoicer') { // TODO: is this a valid hook?
				wp_enqueue_style( 'dx-invoicer-admin', plugins_url( '/css/dx-invoicer-admin.css', __FILE__ ), array(), '1.0', 'screen' );
				wp_enqueue_script( 'dx-invoicer-admin', plugins_url( '/js/dx-invoicer-admin.js', __FILE__ ), array( 'jquery' ) );
				
				//wp_localize_script( 'dx-invoicer-admin','DXINVOICE',array( 'ajaxurl'=>	admin_url( 'admin-ajax.php', ( is_ssl() ? 'https' : 'http' ) ) ));
			}
			$hook_file = array('post.php','toplevel_page_dx_invoice_settings','post-new.php');
			if(in_array($hook,$hook_file)){ 
				wp_enqueue_script('postbox');
				wp_enqueue_script( 'dx-invoicer-upload', plugins_url( '/js/dx-invoicer-img.js', __FILE__ ), array( 'jquery' ) );
				
				//Chooser CSS/JS
					wp_enqueue_style( 'dx-chosen-css',plugins_url( '/js/chosen/chosen.css', __FILE__ ), array(), null );	
					wp_enqueue_style( 'dx-chosen-custom-css', plugins_url( '/js/chosen/chosen-custom.css', __FILE__ ), array(), null );	
					wp_enqueue_script( 'dx-chosen-js', plugins_url( '/js/chosen/chosen.jquery.js', __FILE__ ), array( 'jquery' ), false, true );
				
				$newui = $wp_version >= '3.5' ? '1' : '0'; //check wp version for showing media uploader
				wp_localize_script( 'dx-invoicer-upload', 'DxImgSettings', array( 'new_media_ui'	=>	$newui	));
				//for new media uploader
				wp_enqueue_media();
			}
			$dx_setting_page = array('toplevel_page_dx_customer_settings','toplevel_page_dx_invoice_settings');
			if(in_array($hook,$dx_setting_page)){
				wp_enqueue_script('postbox');
				wp_enqueue_style( 'dx-invoicer-admin', plugins_url( '/css/dx-invoicer-admin.css', __FILE__ ), array(), '1.0', 'screen' );
				wp_enqueue_script( 'dx-invoicer-customer-setting', plugins_url( '/js/dx-invoicer-customer-setting.js', __FILE__ ), array( 'jquery' ) );
			}
		}

		public function wp_enqueue_styles() {
			wp_enqueue_style( 'dx-invoicer', plugins_url( '/css/dx-invoicer.css',__FILE__), array(), '1.0', 'screen' );
		}
		
		/**
		 * Hook to existing actions and filters 
		 */
		public function prepare_hooks() { 
			
		}
		
		public function register_cpts() { 
			global $dx_customer_instance, $dx_invoice_instance;
			$post_type = DX_INV_POST_TYPE;
			add_action( 'init', array( $dx_invoice_instance, 'register_invoice_cpt' ), 10 );
			add_action( 'init', array( $dx_customer_instance, 'register_customer_cpt' ), 10 );
			
			add_action( 'add_meta_boxes', array( $dx_invoice_instance, 'register_invoice_custom_meta' ), 12 );
			add_action( 'add_meta_boxes', array( $dx_customer_instance, 'register_customer_custom_meta' ), 12 );
			
			add_action( 'save_post', array( $dx_invoice_instance, 'save_invoice_post' ) );
			add_action( 'save_post', array( $dx_customer_instance, 'save_customer_post' ) );
			
			add_action('admin_init',array($dx_invoice_instance,'dx_invoice_admin_init'));
			add_action('admin_init',array($dx_customer_instance,'dx_customer_admin_init'));
			add_action('admin_menu',array($dx_invoice_instance, 'dx_invoice_add_menu_page'));
			//add_action('admin_menu',array($dx_customer_instance, 'dx_customer_add_menu_page'));
			//add_action('admin_menu',array($dx_invoice_instance, 'invoice_detail'));
			add_action( 'admin_notices', array($dx_invoice_instance, 'dx_invoice_error_notice' ));
			add_action( 'edit_form_top', array($dx_invoice_instance,'dx_top_form_edit' ));
			add_action( 'init', array($dx_invoice_instance,'dx_pdf_form_load') );
			add_action( 'init', array($dx_invoice_instance,'dx_inv_outlook_data') );
			add_filter( 'post_updated_messages', array($dx_invoice_instance,'dx_updated_messages') );
			add_filter('manage_'.DX_INV_POST_TYPE.'_posts_columns' ,  array($dx_invoice_instance,'add_invoice_column'));
			add_filter('manage_'.DX_CUSTOMER_POST_TYPE.'_posts_columns' ,  array($dx_customer_instance,'add_customer_invoice_column'));
			add_action( 'manage_posts_custom_column' , array($dx_invoice_instance,'dx_display_posts'), 10, 2 );
			add_action( 'manage_posts_custom_column' , array($dx_customer_instance,'dx_display_customer_invoice_total'), 10, 2 );
			// Add category filter in Invoice list page
			add_action( 'restrict_manage_posts', array( $dx_invoice_instance, 'dx_invoice_restrict_manage_posts' ) );
			// Add action for display deals using deal type
			add_filter( 'pre_get_posts', array( $dx_invoice_instance, 'dx_invoice_pre_get_post' ));
			add_filter( 'post_row_actions', array( $dx_invoice_instance, 'dx_invoice_row_actions' ));
			add_filter( 'manage_edit-'.DX_INV_POST_TYPE.'_sortable_columns', array($dx_invoice_instance,'dx_inv_register_column_sortable' ));
			 add_filter( 'manage_edit-'.DX_CUSTOMER_POST_TYPE.'_sortable_columns', array($dx_customer_instance,'dx_cus_register_column_sortable' ));
			  //add action to call ajax
			add_action( 'wp_ajax_add_outlook_customer', array( $dx_customer_instance, 'add_outlook_customer' ));
			add_action( 'wp_ajax_nopriv_add_outlook_customer',array( $dx_customer_instance,  'add_outlook_customer' ));
			//add action to call ajax
			add_action( 'wp_ajax_dx_invoice_update', array( $dx_invoice_instance, 'dx_invoice_update' ));
			add_action( 'wp_ajax_nopriv_dx_invoice_update',array( $dx_invoice_instance,  'dx_invoice_update' ));
			 	add_filter( 'template_include', array( $dx_invoice_instance,'dx_invoice_render_single'), 99 );
			
		}
		
		public static function get_default_table_header_classes( $column_name ) {
			switch( $column_name ) {
				case 'number': return 'dx_invoice_number_field';
				case 'description': return 'dx_invoice_description_field';
				case 'rate': return 'dx_invoice_rate_field';
				case 'quantity': return 'dx_invoice_quantity_field';
				case 'net': return 'dx_invoice_net_field';
				case 'total': return 'dx_invoice_total_field';
				
				default: return apply_filters('dx_invoicer_default_column_class_name', '');
			}
		}
		
		
		
	}
	
	new DX_Invoicer();
}