<?php
/**
 * Global Desclaimer/Rules page settings
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

?>
<div class="wrap">
	
	<h2><?php _e('Invoice Settings', 'postr'); ?></h2>
	
		
	<form  method="post" action="options.php">		
		<?php
			settings_fields( 'invoice_plugin_options' );
			$dx_invoice_options = get_option( 'dx_invoice_options' );
			$invoice_current = isset($dx_invoice_options['invoice_num'])?$dx_invoice_options['invoice_num']:"";
			$invoice_increment = isset($dx_invoice_options['increment'])?$dx_invoice_options['increment']:"";
			$invoice_stamp = isset($dx_invoice_options['stamp'])?$dx_invoice_options['stamp']:"";
			$invoice_signature = isset($dx_invoice_options['signature'])?$dx_invoice_options['signature']:"";
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
												<td colspan="2">
													<input type="submit" class="button-primary dx-invoice-settings-save" name="dx_invoice_settings_save" class="" value="<?php echo __( 'Save Changes', 'dxinvoice' ) ?>" />
												</td>
											</tr>
									
											<tr>
												<th scope="row">
													<label><strong><?php echo __( 'Current Invoice Number', 'dxinvoice' ) ?></strong></label>
												</th>
												<td><input type="text" id="dx-invoice-settings-invoice"  name="dx_invoice_options[invoice_num]" value="<?php echo $invoice_current; ?>" size="63" /><br />
													<span class="description"><?php echo __( 'Current Invoice Number', 'dxinvoice' ) ?></span>
												</td>
											 </tr>
											 <tr>
												<th scope="row">
													<label><strong><?php echo __( 'Invoice Increment By', 'dxinvoice' ) ?></strong></label>
												</th>
												<td><input type="text" id="dx-invoice-settings-increment" name="dx_invoice_options[increment]" value="<?php echo $invoice_increment; ?>" size="63" /><br />
													<span class="description"><?php echo __( 'Increment Cycle of invoice number', 'dxinvoice' ) ?></span>
												</td>
											 </tr>
											 <tr>
												<th scope="row">
													<label><strong><?php echo __( 'Invoice Stamp', 'dxinvoice' ) ?></strong></label>
												</th>
												<td>
												<?php
												if(!empty($invoice_stamp)) { //check connect button image
													$show_img_connect_stamp = ' <img src="'.$invoice_stamp.'" alt="'.__('Image','dxinvoice').'" />';
												} else {
													$show_img_connect_stamp = '';
												}
												?>	
													<input type="text" id="dx-invoice-settings-stamp" name="dx_invoice_options[stamp]" value="<?php echo $invoice_stamp; ?>" size="63" />
													<input type="button" class="button-secondary dx-img-uploader" id="dx-img-btn-stamp" name="dx_img_stamp" value="<?php echo __( 'Choose image.', 'dxinvoice' ) ?>"><br />
													<span class="description"><?php echo __( 'Choose image.', 'dxinvoice' ) ?></span>
													<div id="dx-invoice-setting-image-view-stamp"><?php echo $show_img_connect_stamp ?></div>
												</td>
											 </tr>
											 <tr>
												<th scope="row">
													<label><strong><?php echo __( 'Invoice Signature', 'dxinvoice' ) ?></strong></label>
												</th>
												<td>
													<?php
												if(!empty($invoice_signature)) { //check connect button image
													$show_img_connect_signature = ' <img src="'.$invoice_signature.'" alt="'.__('Image','dxinvoice').'" />';
												} else {
													$show_img_connect_signature = '';
												}
												?>	
													<input type="text" id="dx-invoice-settings-signature" name="dx_invoice_options[signature]" value="<?php echo $invoice_signature; ?>" size="63" />
													<input type="button" class="button-secondary dx-img-uploader" id="dx-img-btn" name="dx_img_signature" value="<?php echo __( 'Choose image.', 'dxinvoice' ) ?>"><br />
													<span class="description"><?php echo __( 'Choose image.', 'dxinvoice' ) ?></span>
													<div id="dx-invoice-setting-image-view-signature"><?php echo $show_img_connect_signature ?></div>
												</td>
											 </tr>
											<tr>
												<td colspan="2">
													<input type="submit" class="button-primary dx-invoice-settings-save" name="dx_invoice_settings_save" class="" value="<?php echo __( 'Save Changes', 'dxinvoice' ) ?>" />
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