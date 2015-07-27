<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

	
	require_once DX_INV_DIR.'/inc/pdf-library/tcpdf/tcpdf.php';
	  
	$post_id = isset($_REQUEST['post_ID'])?$_REQUEST['post_ID']:"";
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

	$dx_status_invoice   		= get_post_meta($post_id,'_dx_status_invoice',true);

	$dx_invoice_stamp_img	    = get_post_meta($post_id,'_invoice_stamp_img',true);
	$dx_invoice_signature_img   = get_post_meta($post_id,'_invoice_signature_img',true);
	$dx_top_custom_value   		= get_post_meta($post_id,'dx_invoice_items',true);
	$templates   				= get_post_meta($post_id,'_page_templates',true);
	$dx_vat_text		  		= get_post_meta($post_id,'_vat_text',true);
	
	if( is_serialized( $dx_top_custom_value ) ) {
		$dx_top_custom_value = maybe_unserialize( $dx_top_custom_value );
	}
	// Invoice Detail
	$current_user 				= 	wp_get_current_user();
	$current_user_firstname		=	$current_user->display_name;
	$invoice_net				= 	"";
	$invoice_total				= 	"";
	$invoice_discount			=	"";
	
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
	
	//Calculate VAT with total
	$dx_final_total = $invoice_total + $vat_amount;
	
	
	// Check invoice Option
	$dx_invoice_options = get_option( 'dx_invoice_options' );
	if(empty($dx_invoice_stamp_img)){
			$dx_invoice_stamp_img = isset($dx_invoice_options['stamp'])?$dx_invoice_options['stamp']:"";
	}
	if(empty($dx_invoice_signature_img)){
			$dx_invoice_signature_img = isset($dx_invoice_options['signature'])?$dx_invoice_options['signature']:"";
	}
	
	// Company Detail 
	$dx_setting_person_name 				= 	$dx_invoice_options['dx_company_person'];
	$dx_setting_company_name 				= 	$dx_invoice_options['dx_company_name'];
	$dx_setting_company_website				= 	$dx_invoice_options['dx_company_website'];
	$dx_setting_company_email 				= 	$dx_invoice_options['dx_company_email'];
	$dx_setting_company_address 			= 	$dx_invoice_options['dx_company_address'];
	$dx_setting_company_unique_number 		= 	$dx_invoice_options['dx_company_unique_number'];
	$dx_setting_company_responsible_person 	= 	$dx_invoice_options['dx_company_responsible_person'];
	$dx_setting_company_bank_ac_number	 	= 	$dx_invoice_options['dx_company_bank_ac_number'];
	

	//Other bank Account

	$dx_company_bank_ac_number_other = get_option('dx_company_bank_ac_number_other');

	// Customer Detail
	$custdata					=	get_post($dx_client);
	
	$dx_customer_name			=   !empty($custdata->post_title)? $custdata->post_title	:"";
	
	$clientmetadata 			= 	get_post_custom($dx_client);
	
	$dx_company_name   			= 	get_post_meta($dx_client,'_company_name',true);
	$dx_company_address   		= 	get_post_meta($dx_client,'_company_address',true);
	$dx_company_number   		= 	get_post_meta($dx_client,'_company_number',true);
	$dx_client_name   			= 	get_post_meta($dx_client,'_client_name',true);
	$dx_date_of_execution		= 	get_post_meta($dx_client,'_date_of_execution',true);
	$dx_bank_account   			= 	get_post_meta($dx_client,'_bank_account',true);
	// Template Start
	ob_start();
	if(empty($templates)){
		$templates = isset($dx_invoice_options['page_template'])?$dx_invoice_options['page_template']:"";
	}
	if(!empty($templates)){	
		if(file_exists(DX_INV_DIR.'/templates/'.$templates)){
			require_once DX_INV_DIR.'/templates/'.$templates;
		}else 
		{
			echo "Template Not Exist";
		}
	}
	$html = ob_get_clean();
		//echo $html; exit;
	$pdf_margin_top = PDF_MARGIN_TOP;
	$pdf_margin_left = PDF_MARGIN_LEFT;
	$pdf_margin_right = PDF_MARGIN_RIGHT;
	$pdf_bg_image = $dx_invoice_stamp_img;
	$vou_template_pdf_view = '';
		
	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	// remove default header
	$pdf->setPrintHeader(false);


	
	// remove default footer
	$pdf->setPrintFooter(false);
		
	// Auther name and Creater name 
	$pdf->SetCreator( utf8_decode( __('DX Invoice','dxinvoice') ) );
	$pdf->SetAuthor( utf8_decode( __('DX Invoice','dxinvoice') ) );
	$pdf->SetTitle( utf8_decode(__('DX Invoice','dxinvoice') ) );

	// set default header data
	//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 021', PDF_HEADER_STRING);
	
	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	// set margins
	$pdf->SetMargins($pdf_margin_left, $pdf_margin_top, $pdf_margin_right);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	
	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	
	// set default font subsetting mode
    $pdf->setFontSubsetting(true);
    
	// ---------------------------------------------------------
	
	// set font
	$pdf->SetFont('helvetica', '', 12);
	
	// add a page
	
	
	// set cell padding
	$pdf->setCellPaddings(1, 1, 1, 1);
	
	// set cell margins
	$pdf->setCellMargins(0, 1, 0, 1);
	
	// set font color
	$pdf->SetTextColor( 50, 50, 50 );
	$pdf->SetFillColor( 238, 238, 238 );
	$pdf->AddPage();
//	 Transparancy Image Stamp
//	if(!empty($dx_invoice_stamp_img)){
//		$pdf->Image($dx_invoice_stamp_img, 18, 15, 16, '', '', 'http://Dxinvoice.com', '', false, 300);
//	}

	
	// Transparancy Image Stamp
	if(!empty($dx_invoice_stamp_img)){
	$pdf->Image($dx_invoice_stamp_img, $dx_stamp_position, 130, 30, '', '', '', '', false, 300);}
	//if(!empty($dx_invoice_signature_img))
	//$pdf->Image($dx_invoice_signature_img, 174, 241, 20, 10, '', 'http://Dxinvoice.com', '', false, 300);
	// output the HTML content
	$pdf->writeHTML($html, true, 0, true, 0);
	
	// reset pointer to the last page
	$pdf->lastPage();
	
	// ---------------------------------------------------------
	
	//Get pdf name
	//$pdf_name = isset( $pdf_args['pdf_name'] ) && !empty( $pdf_args['pdf_name'] ) ? $pdf_args['pdf_name'] : 'edd-voucher-' . date('d-m-Y');
	
	//Close and output PDF document
	//Second Parameter I that means display direct and D that means ask download or open this file
	$pdf->Output( 'pdf-generater-' . date('Y-m-d') . '.pdf', $pdf_view_type );
	exit;


?>