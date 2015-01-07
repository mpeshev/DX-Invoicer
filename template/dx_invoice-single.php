
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
	$dx_top_custom_value   			= get_post_meta($post_id,'dx_invoice_items',true);
	$dx_custom 					= get_post_custom( $post_id ); 
	
	if( is_serialized( $dx_top_custom_value ) ) {
		$dx_top_custom_value = maybe_unserialize( $dx_top_custom_value );
	}
	
	// Invoice Detail
	$current_user 				= 	wp_get_current_user();
	$current_user_firstname		=	$current_user->display_name;
	$invoice_total				= 	"";
	foreach ($dx_top_custom_value as $invoice_row){$invoice_total += $invoice_row['total'];}
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
	
?>
<html>
<head>
<meta http-equiv="Content-Language" content="en" />
<meta name="GENERATOR" content="Zend Studio" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>title</title>
<style>
body{margin:0; padding:0; font-size:20px !important;}
.container{width:70%; margin:0 auto; margin-bottom:20px;}
.editable.disable {
    background: none repeat scroll 0 0 pink;
    border-radius: 16px;
    display: inline-block;
    float: right;
    padding: 5px;
}
.disable .changable-text,.disable .changable-textarea{border:1px dotted red;}
.changable-text,.changable-textarea{cursor:pointer;}
.wrapbutton {
    margin: 0 auto;
    text-align: center;
    width: 84%;
}
#pdf-download{display:none;}
</style>

<script type='text/javascript' src='<?php echo includes_url(); ?>/js/jquery/jquery.js'></script>
<script type='text/javascript'>
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script> 
<script type="text/javascript">
	jQuery(document).ready(function($){
		$('.editable').click(function(){
			$('body').toggleClass('disable');
		});
	
		$textarea = $('.changable-textarea');
		$text = $('.changable-text');
		$body = $('body');
		
		
		$text.click(function(event){
			
			if($text.find('input').length == 0){
				$(this).html("<input type='text' value='"+$(this).html()+"' name='' >");
				$(this).find('input').focus();
			}
			event.stopPropagation();
		});
		$textarea.click(function(event){
			if($textarea.find('textarea').length == 0){
				$(this).html("<textarea type='text'>"+$(this).html()+"</textarea>");
				$(this).find('input').focus();
			}
			event.stopPropagation();
		});
		
		$('body').click(function(event){
			$inputavailable = $(this).find('input').length;
			$textareaavailable = $(this).find('textarea').length;
			
			if($inputavailable){
				$(this).find('input').each(function(e){
					$fieldval = $.trim($(this).val());
					$(this).replaceWith($fieldval);
				});
			}
			if($textareaavailable){
				$(this).find('textarea').each(function(e){
					$fieldval = $.trim($(this).val());
					$(this).replaceWith($fieldval);
				});
			}
		});
		
		
		$("body").keyup(function (e) {
		    if (e.keyCode == 13) { 
			    $inputavailable = $(this).find('input').length;
				$textareaavailable = $(this).find('textarea').length;
				
				if($inputavailable){
					$(this).find('input').each(function(e){
						$fieldval = $.trim($(this).val());
						$(this).replaceWith($fieldval);
					});
				}
				if($textareaavailable){
					$(this).find('textarea').each(function(e){
						$fieldval = $.trim($(this).val());
						$(this).replaceWith($fieldval);
					});
				}
		    }
		});
		
		$('button').click(function(){ 
			
				$inputavailable = $('body').find('input').length;
				$textareaavailable = $('body').find('textarea').length;
				
				if($inputavailable){
					$('body').find('input').each(function(e){
						$fieldval = $(this).val();
						$(this).replaceWith($fieldval);
					});
				}
				if($textareaavailable){
					$('body').find('textarea').each(function(e){
						$fieldval = $(this).val();
						$(this).replaceWith($fieldval);
					});
				}
			
			var btnevnt = $(this).attr('id');
			/*	Table 1		*/
			var clientname  			= $('[data-clientname]').html();
			var clientid  				= $('[data-clientname]').data('clientname');
			var data_clientcompany  	= $('[data-clientcompany]').html();
			var data_clientcomaddr  	= $.trim($('[data-clientcomaddr]').html());
			
			var data_clientcomnum  		= $('[data-clientcomnum]').html();
			var data_contactperson  		= $('[data-contactperson]').html();
			/*	Table 2		*/
			var data_customername  		= $('[data-customername]').html();
			var data_customercomname  	= $('[data-customercomname]').html();
			var data_customercomaddr  	= $('[data-customercomaddr]').html();
			var data_customercomidno  	= $('[data-customercomidno]').html();
			var data_customercomcontactp= $('[data-customercomcontactp]').html();
			var data_bankacc			= $('[data-bankacc]').html();
			
			var tabledata = new Array();
			var finaldata = new Array();
			var parentIndex = "";
			var childval = "";
			$('.invoice-body-wrap').each(function(index){
				parentIndex = index;
				tabledata[index]= {};
				
				$(this).find('td').each(function(index){
					childval = $(this).html();
					tabledata[parentIndex][index] = {};
					tabledata[parentIndex][index]= {index:childval};
				});
				finaldata.push(tabledata[index]);
			});
			
			var datareturn = { 
								action					:	'dx_invoice_update',
								dx_page_id				:	<?php echo $post->ID; ?>,
								dx_clientname			:	clientname,
								data_clientcompany		:	data_clientcompany,
								data_clientcomaddr		:	data_clientcomaddr,
								data_clientcomnum		:	data_clientcomnum,
								data_contactperson		:	data_contactperson,
								data_customername		:	data_customername,
								data_customercomname	:	data_customercomname,
								data_customercomaddr	:	data_customercomaddr,
								data_customercomidno	:	data_customercomidno,
								data_customercomcontactp:	data_customercomcontactp,
								data_bankacc			:	data_bankacc,
								buttonevent				:	btnevnt,
								customerid				:	clientid,
								'invoicedata[]'			: 	JSON.stringify(finaldata)
			
			};
						
			console.log(datareturn);
			jQuery.post(ajaxurl,datareturn,function(response) { 
				
				if(btnevnt == 'saveandGenerate'){
					
					$('#pdf-download').attr('href',response);
					$('#pdf-download').show();
					alert('PDF Saved Click below link for download');
				}
				else{
					alert('PDF Saved');
				}
			});
		});
		
	});
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

