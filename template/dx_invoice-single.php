
<?php

global $post;

	$post_id = $post->ID;
	$post_status = get_post_status( $post_id ) ;
	 if("auto-draft" == $post_status){
	 	echo "Please save post before Generate Invoice.";
	 	$dx_listinvoice = add_query_arg( array( 'post_type' => DX_INV_POST_TYPE),admin_url('edit.php'));
	 	wp_die('<a href="'.$dx_listinvoice.'">List Invoice</a>');
	 }
	$postdata = get_post($post_id);
	// Get Invoice Detail
	$dx_invoice_number 			= get_post_meta($post_id,'_invoice_number',true);
	$dx_client		   			= get_post_meta($post_id,'_client',true);
	$dx_amount		   			= get_post_meta($post_id,'_amount',true);
	$dx_amount_text	   			= get_post_meta($post_id,'_amount_text',true);
	$dx_currency	   			= get_post_meta($post_id,'_currency',true);
	$dx_description	   			= get_post_meta($post_id,'_description',true);
	$dx_date_of_execution	    = get_post_meta($post_id,'_date_of_execution',true);
	$dx_stamp_position   		= get_post_meta($post_id,'_stamp_position',true);
	$dx_invoice_stamp_img	    = get_post_meta($post_id,'_invoice_stamp_img',true);
	$dx_invoice_signature_img   = get_post_meta($post_id,'_invoice_signature_img',true);
	$dx_templates   			= get_post_meta($post_id,'_page_templates',true);
	$dx_top_custom_value   		= get_post_meta($post_id,'dx_invoice_items',true);
	$dx_vat_text		  		= get_post_meta($post_id,'_vat_text',true);
	
	if( is_serialized( $dx_top_custom_value ) ) {
		$dx_top_custom_value = maybe_unserialize( $dx_top_custom_value );
	}
	
	// Invoice Detail
	$current_user 				= 	wp_get_current_user();
	$current_user_firstname		=	$current_user->display_name;
	$invoice_net				= 	"";
	$invoice_total				= 	"";
	$invoice_discount			= 	"";
	if(count($dx_top_custom_value)){
		foreach ($dx_top_custom_value as $invoice_row)
		{	
			$invoice_net 		+= $invoice_row['net'];
			$invoice_total 		+= $invoice_row['total'];
			$invoice_discount 	+= $invoice_row['discount'];
		}
	}
	
	//Calculate VAT
	$vat_amount = $dx_vat_text/100 * $invoice_total;
	
	//Calculate VAT
	$dx_final_total = $invoice_total + $vat_amount;
	
	// Check invoice Option
	$dx_invoice_options = get_option( 'dx_invoice_options' );
	if(empty($dx_invoice_stamp_img)){
			$dx_invoice_stamp_img 		= isset($dx_invoice_options['stamp'])?$dx_invoice_options['stamp']:"";
	}
	if(empty($dx_invoice_signature_img)){
			$dx_invoice_signature_img 	= isset($dx_invoice_options['signature'])?$dx_invoice_options['signature']:"";
	}
	//include html template
	
	// Customer Detail
	$custdata					=	get_post($dx_client);
	
	$dx_customer_name			=   !empty($custdata->post_title)? $custdata->post_title	:"";
	
	$clientmetadata 			= 	get_post_custom($dx_client);
	$dx_company_name   			= 	get_post_meta($dx_client,'_company_name',true);
	$dx_company_address   		= 	get_post_meta($dx_client,'_company_address',true);
	$dx_company_number   		= 	get_post_meta($dx_client,'_company_number',true);
	$dx_client_name   			= 	get_post_meta($dx_client,'_client_name',true);
	$dx_bank_account   			= 	get_post_meta($dx_client,'_bank_account',true);
	
	// Company Detail 
	$dx_setting_person_name 				= 	$dx_invoice_options['dx_company_person'];
	$dx_setting_company_name 				= 	$dx_invoice_options['dx_company_name'];
	$dx_setting_company_address 			= 	$dx_invoice_options['dx_company_address'];
	$dx_setting_company_unique_number 		= 	$dx_invoice_options['dx_company_unique_number'];
	$dx_setting_company_responsible_person 	= 	$dx_invoice_options['dx_company_responsible_person'];
	$dx_setting_company_bank_ac_number	 	= 	$dx_invoice_options['dx_company_bank_ac_number'];
	
?>
<html>
<head>
<meta http-equiv="Content-Language" content="en" />
<meta name="GENERATOR" content="Zend Studio" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>title</title>
<link href="<?php echo DX_INV_URL; ?>/css/single-template.css" rel="stylesheet">
<script type='text/javascript' src='<?php echo includes_url(); ?>js/jquery/jquery.js'></script>
<script type='text/javascript' src='<?php echo DX_INV_URL; ?>js/single-template.js'></script>
<script type='text/javascript'>
	var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script> 
</head>
<body bgcolor="#FFFFFF" text="#000000" link="#FF9966" vlink="#FF9966" alink="#FFCC99">

<?php	
		
		
		$dx_filepath = DX_INV_DIR.'/helpers/page-single-invoice/'.$dx_templates;
		
		if(file_exists($dx_filepath)){
			if(is_file($dx_filepath)){
				try{
					?>
					<div class="container">
						<h1 align="center">Invoice</h1>
						<p align="right" class="editable disable">Click to see editable field</p>
						<?php require_once($dx_filepath); ?>
					</div>
					<?php
				}
				catch (Exception $e)
				{
					echo "Error : ". $e;
				}
			}
		}else 
		{
			echo "Template Not Exist";
		}
?>
<div class="wrapbutton">
<button id="saveInvoice">Save Invoice</button><button id="saveandGenerate">Save & Generate PDF</button><span><a id="pdf-download" href="">Download PDF</a></span></div>
<style>body * {margin:0; padding:0; font-size:20px !important;}</style>
</body>
</html>

