<?php
/**
 * Customer settings
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

?>
<div class="wrap">
	
	<h2><?php _e('Customer Settings', 'dxinvoice'); ?></h2>
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
			$files= DX_INV_DIR."/helpers/page-single-invoice";
			$dir = "";
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
			settings_fields( 'customer_plugin_options' );
			$dx_customer_options 	= get_option( 'dx_customer_options' );
			$dx_google_client_id 	= isset($dx_customer_options['dx_google_client_id'])?$dx_customer_options['dx_google_client_id']:"";
			$dx_google_client_secret= isset($dx_customer_options['dx_google_client_secret'])?$dx_customer_options['dx_google_client_secret']:"";
			$dx_google_callback_url	= isset($dx_customer_options['dx_google_callback_url'])?$dx_customer_options['dx_google_callback_url']:"";
			
		?>
		<!-- beginning of the settings meta box -->	
			<div id="dx-customer-settings" class="post-box-container">
			
				<div class="metabox-holder">	
			
					<div class="meta-box-sortables ui-sortable">
			
						<div id="settings" class="postbox">	
			
							<div class="handlediv" title="<?php echo __( 'Click to toggle', 'dxinvoice' ) ?>"><br /></div>
			
								<!-- settings box title -->					
								<h3 class="hndle">					
									<span style="vertical-align: top;"><?php echo __( 'Customer Settings', 'dxinvoice' ) ?></span>					
								</h3>
			
								<div class="inside">			

									<table class="form-table dx-customer-settings-box"> 
										<tbody>
							
											<tr>
												<td colspan="2">
													<input type="submit" class="button-primary dx-customer-settings-save" name="dx_customer_settings_save" class="" value="<?php echo __( 'Save Changes', 'dxinvoice' ) ?>" />
												</td>
											</tr>
									
											<tr>
												<th scope="row">
													<label for="dx-customer-settings-google-id"><strong><?php echo __( 'Google Client ID', 'dxinvoice' ) ?></strong></label>
												</th>
												<td><input type="text" id="dx-customer-settings-google-id"  name="dx_customer_options[dx_google_client_id]" value="<?php echo $dx_google_client_id; ?>" size="63" /><br />
													<span class="description"><?php echo __( 'Enter Google Client ID', 'dxinvoice' ) ?></span>
												</td>
											 </tr>
											 <tr>
												<th scope="row">
													<label for="dx-customer-settings-google-secret"><strong><?php echo __( 'Google Client Secret', 'dxinvoice' ) ?></strong></label>
												</th>
												<td><input type="text" id="dx-customer-settings-google-secret" name="dx_customer_options[dx_google_client_secret]" value="<?php echo $dx_google_client_secret; ?>" size="63" /><br />
													<span class="description"><?php echo __( 'Enter Google Client Secret', 'dxinvoice' ) ?></span>
												</td>
											 </tr>
											 <tr>
												<th scope="row">
													<label for="dx-customer-settings-google-callback"><strong><?php echo __( 'Callback URL ', 'dxinvoice' ) ?></strong></label>
												</th>
												<td><input type="text" id="dx-customer-settings-google-callback" name="dx_customer_options[dx_google_callback_url]" value="<?php echo $dx_google_callback_url; ?>" size="63" /><br />
													<span class="description"><?php echo __( 'Enter Google Callback URL', 'dxinvoice' ) ?></span>
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
	