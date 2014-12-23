jQuery(document).ready(function($){
	$('.post-type-dx_invoice #publish').click(function(){
		$value = $('#invoice_number').val();
		if(isNaN($value)){
			$("#invoice_number").css('border','1px solid red');
			return false;
		}
	});
	$('.handlediv').click(function(){
		$('#settings').toggleClass('closed');
	});
	$('.dx-invoice-settings-save').click(function(){ 
		$invoice = $('#dx-invoice-settings-invoice').val();
		$increment = $('#dx-invoice-settings-increment').val();
		var flag = 0;
		
		if(isNaN($invoice)){alert('sd');
			$("#dx-invoice-settings-invoice").css('border','1px solid red');
			flag =1;
		}
		if(isNaN($increment)){
			$("#dx-invoice-settings-increment").css('border','1px solid red');
			flag =1;
		}
		
		if(flag ==1)
		return false;
	});
	//Image Uploader as per wordpress version
	jQuery( document ).on('click', '.dx-img-uploader', function() {
		
		var imgfield,showimgfield;
		imgfield = jQuery(this).prev('input').attr('id');
		showimgfield = jQuery(this).next().next().next('div').attr('id'); //show uploaded image
    	
		if(typeof wp == "undefined" || DxImgSettings.new_media_ui != '1' ){// check for media uploader
				
			tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	    	
			window.original_send_to_editor = window.send_to_editor;
			window.send_to_editor = function(html) {
				
				if(imgfield)  {
					
					var mediaurl = jQuery('img',html).attr('src');
					jQuery('#'+imgfield).val(mediaurl);
					jQuery('#'+showimgfield).html('<img src="'+mediaurl+'" alt="Image" />');
					tb_remove();
					imgfield = '';
					
				} else {
					
					window.original_send_to_editor(html);
					
				}
			};
	    	return false;
			
		      
		} else {
			
			var file_frame;
			
			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				file_frame.open();
			  return;
			}
	
			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				frame: 'post',
				state: 'insert',
				multiple: false  // Set to true to allow multiple files to be selected
			});
	
			file_frame.on( 'menu:render:default', function(view) {
		        // Store our views in an object.
		        var views = {};
	
		        // Unset default menu items
		        view.unset('library-separator');
		        view.unset('gallery');
		        view.unset('featured-image');
		        view.unset('embed');
	
		        // Initialize the views in our view object.
		        view.set(views);
		    });
	
			// When an image is selected, run a callback.
			file_frame.on( 'insert', function() {
	
				// Get selected size from media uploader
				var selected_size = jQuery('.attachment-display-settings .size').val();
				
				var selection = file_frame.state().get('selection');
				selection.each( function( attachment, index ) {
					attachment = attachment.toJSON();
					
					// Selected attachment url from media uploader
					var attachment_url = attachment.sizes[selected_size].url;
					
					if(index == 0){ 
						// place first attachment in field
						jQuery('#'+imgfield).val(attachment_url);
						jQuery('#'+showimgfield).html('<img src="'+attachment_url+'" alt="Image" />');
					} else{
						jQuery('#'+imgfield).val(attachment_url);
						jQuery('#'+showimgfield).html('<img src="'+attachment_url+'" alt="Image" />');
					}
				});
			});
	
			// Finally, open the modal
			file_frame.open();
			
		}
		
	});
});
