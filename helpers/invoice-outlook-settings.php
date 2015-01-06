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
		$dx_urls = $dx_invoice_instance->dx_get_windowslive_auth_url();
		if(isset($_SESSION['outlook'])){
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
											</tr>
										</thead> 
										<tbody>
										<?php 
											if(!empty($printdata)){
												foreach ($printdata as $dx_data){?>
											<tr>
												<td scope="row">
													<label for="dx-invoice-settings-invoice"><strong><?php echo $dx_data['name']; ?></strong></label>
												</td>
												<td><label><?php implode(',',$dx_data['email']); ?></label>
												</td>
											 </tr>
										<?php } } ?>
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