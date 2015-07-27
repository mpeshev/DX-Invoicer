<?php
/**
 * Invoice settings
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
?>
<div class="wrap">
	
	<h2><?php _e('Invoice Settings', 'dxinvoice'); ?></h2>
	<?php 
	// Notice 
	
	//check settings updated or not
	if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') {
		
		echo '<div class="updated" id="message">
			<p><strong>'. __("Changes Saved Successfully.",'dxinvoice') .'</strong></p>
		</div>';
	}	
	
	?>
		
	<form  method="post" action="options.php">		
		<?php
			$files= DX_INV_DIR."/templates";
			$dir = "";
			$dx_google_callback_url = add_query_arg( array( 'page' => 'dx_invoice_google_settings'), admin_url( 'admin.php' ) ); 
			$pred = scandir($files);
			 foreach ($pred as $key => $value)
			   {
			      if (!in_array($value,array(".","..")))
			      {
			         if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
			         {
			            $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);
			         }
			         else
			         {
			            $result[] = $value;
			         }
			      }
			   } 
			settings_fields( 'invoice_plugin_options' );
			$dx_invoice_options 		= get_option( 'dx_invoice_options' );
			$invoice_current 			= isset($dx_invoice_options['invoice_num'])?$dx_invoice_options['invoice_num']:"";
			$invoice_increment 			= isset($dx_invoice_options['increment'])?$dx_invoice_options['increment']:"";
			$invoice_stamp 				= isset($dx_invoice_options['stamp'])?$dx_invoice_options['stamp']:"";
			$invoice_signature 			= isset($dx_invoice_options['signature'])?$dx_invoice_options['signature']:"";
			$invoice_page_template 		= isset($dx_invoice_options['page_template'])?$dx_invoice_options['page_template']:"";
			
			/*	Company Detail		*/
			$dx_company_person 			= isset($dx_invoice_options['dx_company_person'])?$dx_invoice_options['dx_company_person']:"";
			$dx_company_name 			= isset($dx_invoice_options['dx_company_name'])?$dx_invoice_options['dx_company_name']:"";
			$dx_company_email 			= isset($dx_invoice_options['dx_company_email'])?$dx_invoice_options['dx_company_email']:"";
			$dx_company_website			= isset($dx_invoice_options['dx_company_website'])?$dx_invoice_options['dx_company_website']:"";
			$dx_company_address 		= isset($dx_invoice_options['dx_company_address'])?$dx_invoice_options['dx_company_address']:"";
			$dx_company_unique_number	= isset($dx_invoice_options['dx_company_unique_number'])?$dx_invoice_options['dx_company_unique_number']:"";
			$dx_company_responsible_person	= isset($dx_invoice_options['dx_company_responsible_person'])?$dx_invoice_options['dx_company_responsible_person']:"";
			$dx_company_bank_ac_number	= isset($dx_invoice_options['dx_company_bank_ac_number'])?$dx_invoice_options['dx_company_bank_ac_number']:"";
			/*	Customer Google Contact get		*/
			$dx_google_client_id		= isset($dx_invoice_options['dx_google_client_id'])?$dx_invoice_options['dx_google_client_id']:"";
			$dx_google_client_secret	= isset($dx_invoice_options['dx_google_client_secret'])?$dx_invoice_options['dx_google_client_secret']:"";
			//$dx_google_callback_url		= isset($dx_invoice_options['dx_google_callback_url'])?$dx_invoice_options['dx_google_callback_url']:"";
			
			/*	Customer MSN Outlook Setting	*/
			$dx_outlook_client_id 		= isset($dx_invoice_options['dx_outlook_client_id'])?$dx_invoice_options['dx_outlook_client_id']:"";
			$dx_outlook_client_secret	= isset($dx_invoice_options['dx_outlook_client_secret'])?$dx_invoice_options['dx_outlook_client_secret']:"";
			//$dx_outlook_callback_url	= isset($dx_invoice_options['dx_outlook_callback_url'])?$dx_invoice_options['dx_outlook_callback_url']:"";
			$dx_outlook_callback_url	= get_site_url();
			
		?>
		<!-- beginning of the settings meta box -->	
			<div id="dx-invoice-settings" class="post-box-container">
			
				<div class="metabox-holder">	
			
					<div class="meta-box-sortables ui-sortable">
			
						<div id="settings" class="postbox">	
			
							<div class="handlediv" title="<?php echo __( 'Click to toggle', 'dxinvoice' ) ?>"><br /></div>
			
								<!-- settings box title -->					
								<h3 class="hndle">					
									<span style="vertical-align: top;"><?php echo __( 'Invoice Settings', 'dxinvoice' ) ?></span>					
								</h3>
			
								<div class="inside">			

									<table class="form-table dx-invoice-settings-box"> 
										<tbody>
											<tr>
												<th scope="row">
													<label for="dx-invoice-settings-invoice"><strong><?php echo __( 'Current Invoice Number', 'dxinvoice' ) ?></strong></label>
												</th>
												<td><input type="text" id="dx-invoice-settings-invoice"  name="dx_invoice_options[invoice_num]" value="<?php echo $invoice_current; ?>" size="63" /><br />
													<span class="description"><?php echo __( 'Current Invoice Number', 'dxinvoice' ) ?></span>
												</td>
											 </tr>
											 <tr>
												<th scope="row">
													<label for="dx-invoice-settings-increment"><strong><?php echo __( 'Invoice Increment By', 'dxinvoice' ) ?></strong></label>
												</th>
												<td><input type="text" id="dx-invoice-settings-increment" name="dx_invoice_options[increment]" value="<?php echo $invoice_increment; ?>" size="63" /><br />
													<span class="description"><?php echo __( 'Increment Cycle of invoice number', 'dxinvoice' ) ?></span>
												</td>
											 </tr>
											 <tr>
												<th scope="row">
													<label for="dx-invoice-settings-stamp"><strong><?php echo __( 'Invoice Stamp', 'dxinvoice' ) ?></strong></label>
												</th>
												<td>
												<?php
												if(!empty($invoice_stamp)) { //check connect button image
													$show_img_connect_stamp = ' <img src="'.$invoice_stamp.'" alt="'.__('Image','dxinvoice').'" width="150" height="150" />';
												} else {
													$show_img_connect_stamp = '';
												}
												?>	
													<input type="text" id="dx-invoice-settings-stamp" name="dx_invoice_options[stamp]" value="<?php echo $invoice_stamp; ?>" size="63" />
													<input type="button" class="button-secondary dx-img-uploader" id="dx-img-btn-stamp" name="dx_img_stamp" value="<?php echo __( 'Choose image', 'dxinvoice' ) ?>"><br />
													<span class="description"><?php echo __( 'Choose image', 'dxinvoice' ) ?></span>
													<div id="dx-invoice-setting-image-view-stamp"><?php echo $show_img_connect_stamp ?></div>
												</td>
											 </tr>
											 <tr>
												<th scope="row">
													<label for="dx-invoice-settings-signature"><strong><?php echo __( 'Invoice Signature', 'dxinvoice' ) ?></strong></label>
												</th>
												<td>
													<?php
												if(!empty($invoice_signature)) { //check connect button image
													$show_img_connect_signature = ' <img src="'.$invoice_signature.'" alt="'.__('Image','dxinvoice').'" width="150" height="150" />';
												} else {
													$show_img_connect_signature = '';
												}
												?>	
													<input type="text" id="dx-invoice-settings-signature" name="dx_invoice_options[signature]" value="<?php echo $invoice_signature; ?>" size="63" />
													<input type="button" class="button-secondary dx-img-uploader" id="dx-img-btn" name="dx_img_signature" value="<?php echo __( 'Choose image', 'dxinvoice' ) ?>"><br />
													<span class="description"><?php echo __( 'Choose image', 'dxinvoice' ) ?></span>
													<div id="dx-invoice-setting-image-view-signature"><?php echo $show_img_connect_signature ?></div>
												</td>
											 </tr>
											 <tr>
											<th scope="row">
												<label for="invoice-page-template"><?php echo __( 'Invoice Page Templates', 'dxinvoice' ) ?></label>	
											</th>
											<td><select name="dx_invoice_options[page_template]" id="invoice-page-template">
													<option id="dx_empty_customer" value=""><?php _e('Pick an existing template', 'dxinvoice'); ?></option>						
													<?php
														foreach($result as $singlefile){ ?>
															<option id="dx_template" value="<?php echo $singlefile; ?>" <?php echo ($singlefile == $invoice_page_template ? 'selected' : '' ) ?>><?php echo $singlefile; ?></option>
													<?php	} ?>
												</select><br />
												<span class="description"><?php echo __( 'Add template if not exist.', 'dxinvoice' ) ?></span>
											</td>
										 </tr>
											<tr>
												<td colspan="2">
													<input type="submit" class="button-primary dx-invoice-settings-save" name="dx_invoice_settings_save" value="<?php echo __( 'Save Changes', 'dxinvoice' ) ?>" />
												</td>
											</tr>
										</tbody>
									</table>
						
							</div><!-- .inside -->
				
						</div><!-- #settings -->
			
					</div><!-- .meta-box-sortables ui-sortable -->
			
				</div><!-- .metabox-holder -->
			
			</div><!-- #wps-settings-general -->
			
		<!-- end of the settings meta box -->		
			
		<!-- beginning of the settings meta box -->	
			<div id="dx-company-settings" class="post-box-container">
			
				<div class="metabox-holder">	
			
					<div class="meta-box-sortables ui-sortable">
			
						<div id="settings" class="postbox">	
			
							<div class="handlediv" title="<?php echo __( 'Click to toggle', 'dxinvoice' ) ?>"><br /></div>
			
								<!-- settings box title -->					
								<h3 class="hndle">					
									<span style="vertical-align: top;"><?php echo __( 'Company Settings', 'dxinvoice' ) ?></span>					
								</h3>
			
								<div class="inside">			

									<table class="form-table dx-customer-settings-box"> 
										<tbody>
											<tr>
												<th scope="row">
													<label for="dx-person-name"><strong><?php echo __( 'Person Name', 'dxinvoice' ) ?></strong></label>
												</th>
												<td><input type="text" id="dx-person-name"  name="dx_invoice_options[dx_company_person]" value="<?php echo $dx_company_person; ?>" size="63" /><br />
													<span class="description"><?php echo __( 'Enter Person Name', 'dxinvoice' ) ?></span>
												</td>
											 </tr>
											<tr>
												<th scope="row">
													<label for="dx-company-name"><strong><?php echo __( 'Company Name', 'dxinvoice' ) ?></strong></label>
												</th>
												<td><input type="text" id="dx-company-name"  name="dx_invoice_options[dx_company_name]" value="<?php echo $dx_company_name; ?>" size="63" /><br />
													<span class="description"><?php echo __( 'Enter Company Name', 'dxinvoice' ) ?></span>
												</td>
											 </tr>
											 <tr>
												<th scope="row">
													<label for="dx-company-address"><strong><?php echo __( 'Company Website Address', 'dxinvoice' ) ?></strong></label>
												</th>
												<td><input type="text" id="dx-company-name"  name="dx_invoice_options[dx_company_website]" value="<?php echo $dx_company_website; ?>" size="63" /><br />
													<span class="description"><?php echo __( 'Enter Company Website Address', 'dxinvoice' ) ?></span>
												</td>
											 </tr>

											 <tr>
												<th scope="row">
													<label for="dx-company-address"><strong><?php echo __( 'Company Email Address', 'dxinvoice' ) ?></strong></label>
												</th>
												<td><input type="text" id="dx-company-name"  name="dx_invoice_options[dx_company_email]" value="<?php echo $dx_company_email; ?>" size="63" /><br />
													<span class="description"><?php echo __( 'Enter Company Email Address', 'dxinvoice' ) ?></span>
												</td>
											 </tr>
											 <tr>
												<th scope="row">
													<label for="dx-company-address"><strong><?php echo __( 'Company Address', 'dxinvoice' ) ?></strong></label>
												</th>
												<td><textarea rows="5" cols="60" name="dx_invoice_options[dx_company_address]"><?php echo $dx_company_address; ?></textarea><br>
													<span class="description"><?php echo __( 'Enter Company Address', 'dxinvoice' ) ?></span>
												</td>
											 </tr>
											 <tr>
												<th scope="row">
													<label for="dx-company-unique-number"><strong><?php echo __( 'Company Unique Number', 'dxinvoice' ) ?></strong></label>
												</th>
												<td><input type="text" id="dx-company-unique-number" name="dx_invoice_options[dx_company_unique_number]" value="<?php echo $dx_company_unique_number; ?>" size="63" /><br />
													<span class="description"><?php echo __( 'Enter Company Unique Number', 'dxinvoice' ) ?></span>
												</td>
											 </tr>
											 <tr>
												<th scope="row">
													<label for="dx-company-responsible-person"><strong><?php echo __( 'Responsible Person', 'dxinvoice' ) ?></strong></label>
												</th>
												<td><input type="text" id="dx-company-responsible-person" name="dx_invoice_options[dx_company_responsible_person]" value="<?php echo $dx_company_responsible_person; ?>" size="63" /><br />
													<span class="description"><?php echo __( 'Enter Company Unique Number', 'dxinvoice' ) ?></span>
												</td>
											 </tr>
											 <tr>
												<th scope="row">
													<label for="dx-company-bank-ac-number"><strong><?php echo __( 'Bank Account Number', 'dxinvoice' ) ?></strong></label>
												</th>
												<td>
													<input type="text" id="dx-company-bank-ac-number" name="dx_invoice_options[dx_company_bank_ac_number]" value="<?php echo $dx_company_bank_ac_number; ?>" size="63" />
													<div class="btn btn-default btn-sm" id="add-more-bank-account"> add more</div>
													
													<div class="bank_account_append">
														<?php
														$dx_company_bank_ac_number_other = get_option('dx_company_bank_ac_number_other'); 
														if($dx_company_bank_ac_number_other)
														{
															foreach ($dx_company_bank_ac_number_other as $key => $value) 
															{
															?>
																<div><input type="text" class="new_bank_account_append" name="dx_company_bank_ac_number_other[]" value="<?php echo $value?>" size="63" /><div class="btn btn-danger btn-sm remove"> remove</div></div>
															<?php														
															}
														}
														?>
													</div>
													<br />
													<span class="description"><?php echo __( 'Enter Company Bank Account Number', 'dxinvoice' ) ?></span>
												</td>
											 </tr>
											<tr>
												<td colspan="2">
													<input type="submit" class="button-primary dx-company-settings-save" name="dx_company_settings_save" class="" value="<?php echo __( 'Save Changes', 'dxinvoice' ) ?>" />
												</td>
											</tr>
										</tbody>
									</table>
						
							</div><!-- .inside -->
				
						</div><!-- #settings -->
			
					</div><!-- .meta-box-sortables ui-sortable -->
			
				</div><!-- .metabox-holder -->
			
			</div><!-- #wps-settings-general -->
			
		<!-- end of the settings meta box -->		
		
		<!-- beginning of the settings meta box -->	
			<div id="dx-customer-settings" class="post-box-container">
			
				<div class="metabox-holder">	
			
					<div class="meta-box-sortables ui-sortable">
			
						<div id="settings" class="postbox">	
			
							<div class="handlediv" title="<?php echo __( 'Click to toggle', 'dxinvoice' ) ?>"><br /></div>
			
								<!-- settings box title -->					
								<h3 class="hndle">					
									<span style="vertical-align: top;"><?php echo __( 'Google Settings', 'dxinvoice' ) ?></span>					
								</h3>
			
								<div class="inside">			

									<table class="form-table dx-customer-settings-box"> 
										<tbody>
											<tr>
												<th scope="row">
													<label for="dx-customer-settings-google-id"><strong><?php echo __( 'Google Client ID', 'dxinvoice' ) ?></strong></label>
												</th>
												<td><input type="text" id="dx-customer-settings-google-id"  name="dx_invoice_options[dx_google_client_id]" value="<?php echo $dx_google_client_id; ?>" size="63" /><br />
													<span class="description"><?php echo __( 'Enter Google Client ID', 'dxinvoice' ) ?></span>
												</td>
											 </tr>
											 <tr>
												<th scope="row">
													<label for="dx-customer-settings-google-secret"><strong><?php echo __( 'Google Client Secret', 'dxinvoice' ) ?></strong></label>
												</th>
												<td><input type="text" id="dx-customer-settings-google-secret" name="dx_invoice_options[dx_google_client_secret]" value="<?php echo $dx_google_client_secret; ?>" size="63" /><br />
													<span class="description"><?php echo __( 'Enter Google Client Secret', 'dxinvoice' ) ?></span>
												</td>
											 </tr>
											 <tr>
												<th scope="row">
													<label for="dx-customer-settings-google-callback"><strong><?php echo __( 'Callback URL ', 'dxinvoice' ) ?></strong></label>
												</th>
												<td><input type="text" id="dx-customer-settings-google-callback" name="dx_invoice_options[dx_google_callback_url]" value="<?php echo $dx_google_callback_url; ?>"readonly size="63" /><br />
													<span class="description"><?php echo __( 'Enter Google Callback URL e.g = http://wordpress.com/wp-admin/admin.php?page=dx_invoice_settings', 'dxinvoice' ) ?></span>
												</td>
											 </tr>
											<tr>
												<td colspan="2">
													<input type="submit" class="button-primary dx-customer-settings-save" name="dx_customer_settings_save" class="" value="<?php echo __( 'Save Changes', 'dxinvoice' ) ?>" />
												</td>
											</tr>
										</tbody>
									</table>
						
							</div><!-- .inside -->
				
						</div><!-- #settings -->
			
					</div><!-- .meta-box-sortables ui-sortable -->
			
				</div><!-- .metabox-holder -->
			
			</div><!-- #wps-settings-general -->
			
		<!-- end of the settings meta box -->		
			<!-- beginning of the settings meta box -->	
			<div id="dx-customer-settings" class="post-box-container">
			
				<div class="metabox-holder">	
			
					<div class="meta-box-sortables ui-sortable">
			
						<div id="settings" class="postbox">	
			
							<div class="handlediv" title="<?php echo __( 'Click to toggle', 'dxinvoice' ) ?>"><br /></div>
			
								<!-- settings box title -->					
								<h3 class="hndle">					
									<span style="vertical-align: top;"><?php echo __( 'Outlook Settings', 'dxinvoice' ) ?></span>					
								</h3>
			
								<div class="inside">			

									<table class="form-table dx-customer-settings-box"> 
										<tbody>
											<tr>
												<th scope="row">
													<label for="dx-customer-settings-outlook-id"><strong><?php echo __( 'Outlook Client API ID', 'dxinvoice' ) ?></strong></label>
												</th>
												<td><input type="text" id="dx-customer-settings-outlook-id"  name="dx_invoice_options[dx_outlook_client_id]" value="<?php echo $dx_outlook_client_id; ?>" size="63" /><br />
													<span class="description"><?php echo __( 'Enter Outlook Client API ID', 'dxinvoice' ) ?></span>
												</td>
											 </tr>
											 <tr>
												<th scope="row">
													<label for="dx-customer-settings-outlook-secret"><strong><?php echo __( 'Outlook Client Secret', 'dxinvoice' ) ?></strong></label>
												</th>
												<td><input type="text" id="dx-customer-settings-outlook-secret" name="dx_invoice_options[dx_outlook_client_secret]" value="<?php echo $dx_outlook_client_secret; ?>" size="63" /><br />
													<span class="description"><?php echo __( 'Enter Outlook Client Secret', 'dxinvoice' ) ?></span>
												</td>
											 </tr>
											 <tr>
												<th scope="row">
													<label for="dx-customer-settings-outlook-callback"><strong><?php echo __( 'Callback URL ', 'dxinvoice' ) ?></strong></label>
												</th>
												<td><input type="text" id="dx-customer-settings-outlook-callback" name="dx_invoice_options[dx_outlook_callback_url]" value="<?php echo $dx_outlook_callback_url; ?>" readonly size="63" /><br />
													<span class="description"><?php echo __( 'Site URL', 'dxinvoice' ) ?></span>
												</td>
											 </tr>
											<tr>
												<td colspan="2">
													<input type="submit" class="button-primary dx-customer-settings-save" name="dx_customer_settings_save" class="" value="<?php echo __( 'Save Changes', 'dxinvoice' ) ?>" />
												</td>
											</tr>
										</tbody>
									</table>
						
							</div><!-- .inside -->
				
						</div><!-- #settings -->
			
					</div><!-- .meta-box-sortables ui-sortable -->
			
				</div><!-- .metabox-holder -->
			
			</div><!-- #wps-settings-general -->
			
		<!-- end of the settings meta box -->		

	
	</form>	
	
</div><!-- end .wrap -->