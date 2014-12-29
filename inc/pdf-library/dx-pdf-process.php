<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

	require_once DX_INV_DIR.'/inc/pdf-library/tcpdf/tcpdf.php';
	  
	$post_id = isset($_REQUEST['post_ID'])?$_REQUEST['post_ID']:"";
	$postdata = get_post($post_id);
	// Get Invoice Detail
	$dx_invoice_number 			= get_post_meta($post_id,'_invoice_number');
	$dx_client		   			= get_post_meta($post_id,'_client');
	$dx_amount		   			= get_post_meta($post_id,'_amount');
	$dx_amount_text	   			= get_post_meta($post_id,'_amount_text');
	$dx_currency	   			= get_post_meta($post_id,'_currency');
	$dx_description	   			= get_post_meta($post_id,'_description');
	$dx_date_of_execution	    = get_post_meta($post_id,'_date_of_execution');
	$dx_invoice_stamp_img	    = get_post_meta($post_id,'_invoice_stamp_img');
	$dx_invoice_signature_img   = get_post_meta($post_id,'_invoice_signature_img');
	$dx_custom 					= get_post_custom( $post_id ); 
	
	//$customer_not_selected = add_query_arg( array( 'post' => $post_id, 'dx_action_validate' => 'generate-pdf', 'action' => 'edit', 'message' => 98), admin_url( 'edit.php' ) ); 
	
	$dx_top_custom_value = $dx_custom['dx_invoice_items'][0];
	$templates = isset($dx_custom['_page_templates'][0])?$dx_custom['_page_templates'][0]:"";
	if( is_serialized( $dx_top_custom_value ) ) {
		$dx_top_custom_value = @unserialize( $dx_top_custom_value );
	}
	// Invoice Detail
	$dx_amount  				=  isset($dx_amount[0])			?$dx_amount[0]		:"";
	$dx_client  				=  isset($dx_client[0])			?$dx_client[0]		:"";
	$dx_invoice_number  		=  isset($dx_invoice_number[0])	?$dx_invoice_number[0]:"";
	$dx_amount_text  			=  isset($dx_amount_text[0])	?$dx_amount_text[0]:"";
	$dx_currency  				=  isset($dx_currency[0])		?$dx_currency[0]:"";
	$dx_description  			=  isset($dx_description[0])	?$dx_description[0]:"";
	$dx_date_of_execution  		=  isset($dx_date_of_execution[0])?$dx_date_of_execution[0]:"";
	$dx_invoice_stamp_img  		=  isset($dx_invoice_stamp_img[0])?$dx_invoice_stamp_img[0]:"";
	$dx_invoice_signature_img  	=  isset($dx_invoice_signature_img[0])?$dx_invoice_signature_img[0]:"";
	$current_user 				= 	wp_get_current_user();
	$current_user_firstname		=	$current_user->display_name;
	$invoice_total				= 	"";
	// Check invoice Option
	$dx_invoice_options = get_option( 'dx_invoice_options' );
	if(empty($dx_invoice_stamp_img)){
			$dx_invoice_stamp_img = isset($dx_invoice_options['stamp'])?$dx_invoice_options['stamp']:"";
	}
	if(empty($dx_invoice_signature_img)){
			$dx_invoice_signature_img = isset($dx_invoice_options['signature'])?$dx_invoice_options['signature']:"";
	}
	//include html template
	
	// Customer Detail
	$custdata					=	get_post($dx_client);
	
	$dx_customer_name			=   !empty($custdata->post_title)? $custdata->post_title	:"";
	
	$clientmetadata 			= 	get_post_custom($dx_client);
	$dx_company_name			=	isset($clientmetadata['_company_name'][0])		?$clientmetadata['_company_name'][0]:"";
	$dx_company_address			=	isset($clientmetadata['_company_address'][0])	?$clientmetadata['_company_address'][0]:"";
	$dx_company_number			=	isset($clientmetadata['_company_number'][0])	?$clientmetadata['_company_number'][0]:"";
	$dx_client_name				=	isset($clientmetadata['_client_name'][0])		?$clientmetadata['_client_name'][0]:"";
	$dx_bank_account			=	isset($clientmetadata['_bank_account'][0])		?$clientmetadata['_bank_account'][0]:"";
	ob_start();
	
	if(empty($templates)){
		$templates = isset($dx_invoice_options['page_template'])?$dx_invoice_options['page_template']:"";
	}
	
	if(!empty($templates)){	
		if(file_exists(DX_INV_DIR.'/helpers/page-single-invoice/'.$templates)){
			require_once DX_INV_DIR.'/helpers/page-single-invoice/'.$templates;
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
	// Transparancy Image Stamp
	if(!empty($dx_invoice_stamp_img))
	$pdf->Image($dx_invoice_stamp_img, 30, 100, 30, '', '', 'http://Dxinvoice.com', '', false, 300);
	
	if(!empty($dx_invoice_signature_img))
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
	$pdf->Output( 'pdf-generater-' . date('Y-m-d') . '.pdf', 'D' );
	exit;


?>