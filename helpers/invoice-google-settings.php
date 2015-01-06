<?php
/**
 * Google settings
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

?>
<div class="wrap">
	
	<h2><?php _e('Google Settings', 'dxinvoice'); ?></h2>
	<?php 
	// Notice 
	
	//check settings updated or not
	if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') {
		
		echo '<div class="updated" id="message">
			<p><strong>'. __("Changes Saved Successfully.",'dxinvoice') .'</strong></p>
		</div>';
	}	
	
	?>
	
	
</div><!-- end .wrap -->