<?php
/**
 * Outlook settings
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
global $dx_invoice_instance;
?>
<div class="wrap">
	<h2><?php _e('Outlook Settings', 'dxinvoice'); ?></h2>
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
		$msg = "";
		$dx_emails = array();
		$outlookdata = isset($_SESSION['outlook'])?$_SESSION['outlook']:"";
		$dx_urls = $dx_invoice_instance->dx_get_windowslive_auth_url();
		if(isset($_SESSION['outlook'])){
			$msg ='<a href="'.$dx_urls.'">Reload Data</a> to load Outlook Contact';
			$printdata = isset($outlookdata['data'])	?	$outlookdata['data']	:	"";
		}else{
			$msg ='<a href="'.$dx_urls.'">Click</a> to load Outlook Contact';
		}	
		
		?>
	<!-- beginning of the settings meta box -->	
			<div id="dx-invoice-settings" class="post-box-container">
			
				<div class="metabox-holder">	
			
					<div class="meta-box-sortables ui-sortable">
			
						<div id="settings" class="postbox">	
			
							<div class="handlediv" title="<?php echo __( 'Click to toggle', 'dxinvoice' ) ?>"><br /></div> 
			
								<!-- settings box title -->					
								<h3 class="hndle">					
									<span style="vertical-align: top;"><?php echo __( 'Outlook Settings', 'dxinvoice' ) ?></span>					
								</h3>
								
								<div class="inside">			
									<p><?php echo $msg; ?></p>
									<table class="form-table dx-invoice-settings-box">
										<thead>
											<tr>
												<th>Contact Name</th>
												<th>Email Address</th>
												<th>Import Contact</th>
											</tr>
										</thead> 
										<tbody>
										<?php 
											if(!empty($printdata)){
												$count = 1;
												foreach ($printdata as $dx_data){
												global $wpdb;
												
												$flag = "green";
												
												$dx_outlook_contact_name = isset($dx_data['name'])?$dx_data['name']:"";
												if($wpdb->get_row("SELECT post_title FROM wp_posts WHERE post_title = '" . $dx_outlook_contact_name . "' AND post_type='dx_customer'", 'ARRAY_A')) {
													$flag = "red";
												 }	
													?>
												<tr id="<?php echo $count; ?>">
													<td scope="row" data-name="<?php echo $dx_outlook_contact_name ?>">
														<label for="dx-invoice-settings-invoice"><strong><?php echo $dx_outlook_contact_name; ?></strong></label>
													</td>
													<td><label>
																<?php 
																		if(isset($dx_data['emails'])) {
																			foreach ($dx_data['emails'] as $childemail){
																				if(!empty($childemail) && !in_array($childemail,$dx_emails))
																				$dx_emails[] = $childemail;	
																			}
																			echo implode(',',$dx_emails); 
																		}
																		$dx_emails = array();
																?>
														</label>
													</td>
													<td>
														<?php if($flag == 'green'){?>
														<span class="button <?php echo $flag; ?>" data-id="<?php echo $count; ?>">Import Contact</span>
														<?php }else{ ?>
														<span class="button <?php echo $flag; ?>">Already Added</span>	
														<?php } ?>
													</td>
												 </tr>
										<?php $count++; } } ?>
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