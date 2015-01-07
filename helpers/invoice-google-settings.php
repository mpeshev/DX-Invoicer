<?php

    function curl($url,$post="")
    {
        $curl = curl_init();
        $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
        curl_setopt($curl,CURLOPT_URL,$url);    //The URL to fetch. This can also be set when initializing a session with curl_init().
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
        curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,5);    //The number of seconds to wait while trying to connect.
        if($post!="")
        {
            curl_setopt($curl,CURLOPT_POST,5);
            curl_setopt($curl,CURLOPT_POSTFIELDS,$post);
        }
        curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);  //The contents of the "User-Agent: " header to be used in a HTTP request.
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);   //To follow any "Location: " header that the server sends as part of the HTTP header.
        curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);  //To automatically set the Referer: field in requests where it follows a Location: redirect.
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);    //The maximum number of seconds to allow cURL functions to execute.
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);  //To stop cURL from verifying the peer's certificate.

        $contents = curl_exec($curl);
        curl_close($curl);
        return $contents;
    }

    $currentUrl = $_SERVER['REQUEST_URI'];
    $currentUrl = explode('?', $currentUrl);
    
    
    $dx_invoice_options 	= get_option( 'dx_invoice_options' );
	$dx_google_client_id 	= isset($dx_invoice_options['dx_google_client_id'])?$dx_invoice_options['dx_google_client_id']:"";
	$dx_google_client_secret= isset($dx_invoice_options['dx_google_client_secret'])?$dx_invoice_options['dx_google_client_secret']:"";
	$dx_google_callback_url	= isset($dx_invoice_options['dx_google_callback_url'])?$dx_invoice_options['dx_google_callback_url']:"";
				
    $max_results = 25;

    if(isset($_GET["code"]))
    {
        $auth_code = $_GET["code"];

        $fields=array(
            'code'=>  urlencode($auth_code),
            'client_id'=>  urlencode($dx_google_client_id),
            'client_secret'=>  urlencode($dx_google_client_secret),
            'redirect_uri'=>  $dx_google_callback_url,
            'grant_type'=>  urlencode('authorization_code')
        );
        $post = '';
        foreach($fields as $key=>$value)
        {
            $post .= $key.'='.$value.'&';
        }
        $post = rtrim($post,'&');

        $result = curl('https://accounts.google.com/o/oauth2/token',$post);

        $response =  json_decode($result);
        $accesstoken = isset($response->access_token)?$response->access_token:"";

        $url = 'https://www.google.com/m8/feeds/contacts/default/full?max-results='.$max_results.'&alt=json&v=3.0&oauth_token='.$accesstoken;
        $xmlresponse =  curl($url);
		$temp = json_decode($xmlresponse,true);
    } 
?>
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
	<form  method="post" action="options.php">		
	<a class="button "href="https://accounts.google.com/o/oauth2/auth?client_id=<?php echo $dx_google_client_id; ?>&redirect_uri=<?php echo $dx_google_callback_url; ?>&scope=https://www.google.com/m8/feeds/&response_type=code">Import Contacts from google</a>
		<?php
		$msg = "";
		global $wpdb;
		$count = 1;
		?>
		<!-- beginning of the settings meta box -->	
			<div id="dx-invoice-settings" class="post-box-container">
			
				<div class="metabox-holder">	
			
					<div class="meta-box-sortables ui-sortable">
			
						<div id="settings" class="postbox">	
			
							<div class="handlediv" title="<?php echo __( 'Click to toggle', 'dxinvoice' ) ?>"><br /></div>
			
								<!-- settings box title -->					
								<h3 class="hndle">					
									<span style="vertical-align: top;"><?php echo __( 'Google Settings', 'dxinvoice' ) ?></span>					
								</h3>
								
								<div class="inside">			
									<p><?php echo $msg; ?></p>
									<table class="form-table dx-invoice-settings-box">
										<thead>
											<tr>
												<th><?php echo __( 'Contact Name', 'dxinvoice' ) ?></th>
												<th><?php echo __( 'Email Address', 'dxinvoice' ) ?></th>
												<th><?php echo __( 'Import Contact', 'dxinvoice' ) ?></th>
											</tr>
										</thead> 
										<tbody>
										
										<?php 
											
									        if( isset( $temp['feed']['entry'] ) && !empty( $temp['feed']['entry'] ) ){
									        	
									        	foreach ( $temp['feed']['entry'] as $key => $value ){
									        					$name 		= isset( $value['title']['$t'] ) ? $value['title']['$t'] : '';
												        		$birthdate	= isset( $value['gContact$birthday']['when'] ) ? $value['gContact$birthday']['when'] : '';
												        		$email		= isset( $value['gd$email'][0]['address'] ) ? $value['gd$email'][0]['address'] : '';
												        		$phone		= isset( $value['gd$phoneNumber'][0]['$t'] ) ? $value['gd$phoneNumber'][0]['$t'] : '';
												       $flag = "green";
												
												if($wpdb->get_row("SELECT post_title FROM wp_posts WHERE post_title = '" . $name . "' AND post_type='dx_customer'", 'ARRAY_A')) 												{
													$flag = "red";
												 }	
										?>	
											<tr id="<?php echo $count; ?>">
												<td scope="row" data-name="<?php echo $name ?>">
													<label for="dx-invoice-settings-invoice"><strong><?php echo $name; ?></strong></label>
												</td>
												<td><label>
															<?php echo $email;	?>
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
											 <?php $count++; }   } ?>
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
