<?php

/**
 * Very simple and straight-forward form helper
 * 
 * @author nofearinc
 *
 */
class DX_Form_Helper {
	public static function html_element($item, $attributes, $method, $section_prefix = 'section_', $id_prefix = '' ) {
		
		extract( array_merge ($attributes, self::get_element_attributes( $item, $attributes, $method ) ) );
		$label = !empty($label)	? $label	:"";
	    $type  = !empty($type)	? $type		:"";
	    $name  = !empty($name)	? $name		:"";
	    $value = !empty($value)	? $value	:"";
	    $text  = !empty($text)	? $text		:"";
	    $id    = !empty($id)	? $id		:"";
	    $class = !empty($class)	? $class	:"";
	    $style = !empty($style)	? $style	:"";
	    $desc  = !empty($desc)	? $desc	:"";
	   
		ob_start();
		if ($type == 'checkbox') {
			?>
				<tr>
					<th scope="row">
						<label for="<?php echo $id_prefix . $id ?>"><?php echo $text ?></label>
					</th>
					<td><input id="<?php echo $id_prefix . $id ?>" type="<?php echo $type ?>" name="<?php echo $name ?>" value="true" <?php echo $value ? 'checked' : '' ?>/><br />
						<span class="description"><?php echo $desc; ?></span>
					</td>
				</tr>
				<?php	
			} elseif ($type == 'textarea') {
				?>
				<tr>
					<th scope="row">
						<label for="<?php echo $id_prefix . $id ?>"><?php echo $text ?></label>	
					</th>
					<td><textarea id="<?php echo $id_prefix . $id ?>" name="<?php echo $name ?>" rows="5" cols="20"><?php echo $value ?></textarea><br />
						<span class="description"><?php echo $desc; ?></span>
					</td>
				</tr>
				<?php
			} elseif ($type == 'select') {
				?>
				<tr>
					<th scope="row">
						<label for="<?php echo $id_prefix . $id ?>"><?php echo $text ?></label>	
					</th>
					<td><select name="<?php echo $name ?>" id="<?php echo $id_prefix . $id ?>" <?php echo ($type == 'multiselect' ? 'multiple="multiple"' : '') ?> >
							<?php foreach ($options as $key => $text): ?>
								<option id="<?php echo $key ?>" value="<?php echo $key ?>" <?php echo ($key == $value ? 'selected' : '' ) ?>><?php echo $text ?></option>
							<?php endforeach ?>
						</select><br />
						<span class="description"><?php echo $desc; ?></span>
					</td>
				</tr>
					
				<?php	
			} elseif ($type == 'multiselect') {
				?>
				<tr>
					<th scope="row">
						<label for="<?php echo $id_prefix . $id ?>"><?php echo $text ?></label>	
					</th>
					<td><select name="<?php echo $name ?>[]" id="<?php echo $id_prefix . $id ?>" <?php echo ($type == 'multiselect' ? 'multiple="multiple"' : '') ?> >
							<?php foreach ($options as $key => $text): ?>
								<option id="<?php echo $key ?>" value="<?php echo $key ?>" <?php echo ((is_array($value) && in_array($key, $value) ) ? 'selected' : '' ) ?>><?php echo $text ?></option>
							<?php endforeach ?>
						</select><br />
						<span class="description"><?php echo $desc; ?></span>
					</td>
				</tr>
					
				<?php	
			} elseif( $type == 'text' ) {
				?>
				<tr>
					<th scope="row">
						<label for="<?php echo $id_prefix . $id ?>"><?php echo $text ?></label>	
					</th>
					<td><input id="<?php echo $id_prefix . $id ?>" type="<?php echo $type ?>" name="<?php echo $name ?>" value="<?php echo $value ?>" /><br />
						<span class="description"><?php echo $desc; ?></span>
					</td>
				</tr>
				<?php
			} elseif ( $type == 'date') {
				?>
				<tr>
					<th scope="row">
						<label for="<?php echo $id_prefix . $id ?>"><?php echo $text ?></label>	
					</th>
					<td><input id="<?php echo $id_prefix . $id ?>_picker" class="trigger_datepicker" type="text" name="<?php echo $name ?>" <?php echo !empty($value) ? 'value="'.$value.'"' : ''; ?> /><br />
						<span class="description"><?php echo $desc; ?></span>
					</td>
				</tr>
					
				<?php
			} elseif( $type == 'image' ) {
				?>
				<tr>
					<th scope="row">
						<label for="<?php echo $id_prefix . $id ?>"><?php echo $text ?></label>	
					</th>
					<td><input type="text" class="uploader-text" id="<?php echo $section_prefix . $id ?>" name="<?php echo $id_prefix . $id ?>" value="<?php echo $value; ?>" />
						<input type="button" class="button-secondary dx-img-uploader" id="dx-img-btn-stamp" name="dx_img_stamp" value="<?php echo __( 'Choose image', 'dxinvoice' ) ?>"><br />
						<span class="description"><?php echo $desc; ?></span>
						<?php
							if(!empty($value)) { //check connect button image
								$showvalue = ' <img src="'.$value.'" alt="'.__('Image','dxinvoice').'" width="150" height="150"/>';
							} else {
								$showvalue = '';
							}
						?>	
						<div id="image-view-<?php echo $name ?>"><?php echo $showvalue; ?></div><br />
					</td>
				</tr>
				<?php
			} elseif ($type == 'radio') {
				?>
				<tr>
				<th scope="row">
					<label for="<?php echo $id_prefix . $id ?>"><?php echo $text ?></label>
				</th>
				<td><?php foreach( $options as $key => $text ): ?>
						<div class="<?php echo $name . '_radios'; ?>">
							<label for="<?php echo $name . '_' . $key; ?>"><?php echo $text ?></label>
							<input id="<?php echo $name . '_' . $key; ?>" type="radio" value="<?php echo $text; ?>" name="<?php echo $name; ?>" />
						</div>
						<?php endforeach; ?><br />
					<span class="description"><?php echo $desc; ?></span>
				</td>
			</tr>
				<?php	
			}
			
			do_action( 'dx_invoicer_form_fields_action', $type, $item, $attributes, $method, $section_prefix, $id_prefix );
			
			return trim( ob_get_clean() );
		}
		
		public static function get_element_attributes( $item, $attributes, $method ) {
			
			$text = $item;
			if (isset($attributes['label'])) { $text = $attributes['label']; }
			
			// CSS if needed
			$class = '';
			if (isset($attributes['class'])) { $class = $attributes['class']; }
			
			// CSS if needed
			$desc = '';
			if (isset($attributes['desc'])) { $desc = $attributes['desc']; }
			
			$style = '';
			if (isset($attributes['style'])) { $style = 'style="' . esc_attr( $attributes['style'] ) . '"'; }
			
			$name = $item;
			$id = $item;
			
			// default type
			$type = 'text';
			if( isset( $attributes['type'] ) ) {
				$type = $attributes['type'];
			}
			
			// Handle properly both get and post requests
			// Note: array may need to be filled manually for edits to occur in the UI
			if ($method == 'GET') {
				$value = isset($_GET[$item]) ? $_GET[$item] : null;
			} else {
				$value = isset($_POST[$item]) ? $_POST[$item] : null;
			}
			
			// Form the attributes array
			$attributes = array(
					'name' => $name, 
					'value' => $value, 
					'text' => $text, 
					'id' => $id, 
					'type' => $type, 
					'class' => $class,
					'desc' => $desc,
					'style' => $style
			);

			// Filter attributes if any new form of attribute is discovered
			return apply_filters( 'dx_invoice_attributes_setup', $attributes );
	}
	/* User meta field	*/
	public function usermeta_table($user){ 
		
		?>
		<h3><?php _e("Extra profile information", "blank"); ?></h3>

		<table class="form-table">
			<tr>
				<th><label for="address"><?php _e("Select Company"); ?></label></th>
				<td>
				<?php 
					$company_list = get_the_author_meta( 'company_list', $user->ID );
				 ?>
				<select class="invoice_company" name="company_list">
				
					<?php 
					$args = array(
					'post_type' => DX_CUSTOMER_POST_TYPE,
					);
					$bulk_post = get_posts($args); 
					foreach ($bulk_post as $post){
						if(in_array($post->ID,$company_list))
						echo '<option value="'.$post->ID.'" selected>'.get_post_meta($post->ID,"_company_name",true).'</option>';
						else 
						echo '<option value="'.$post->ID.'">'.get_post_meta($post->ID,"_company_name",true).'</option>';
					}
					?>
				</select>
				<span class="description"><?php _e("Please select company."); ?></span>
				</td>
			</tr>
		</table>
		<?php
	}
	/* Add form user meta field	*/
	public function usermeta_adduser_table(){ 
			
			?>
			<h3 class="dx-user-role"><?php _e("Extra profile information", "blank"); ?></h3>
	
			<table class="form-table dx-user-role">
				<tr>
					<th><label for="address"><?php _e("Select Company"); ?></label></th>
					<td>
					<select class="invoice_company" name="company_list">
						<?php 
						$args = array(
						'post_type' => DX_CUSTOMER_POST_TYPE,
						);
						$bulk_post = get_posts($args); 
						foreach ($bulk_post as $post){
							
							echo '<option value="'.$post->ID.'">'.get_post_meta($post->ID,"_company_name",true).'</option>';
						}
						?>
					</select>
					<span class="description"><?php _e("Please select company."); ?></span>
					</td>
				</tr>
			</table>
			<?php
		}
	/* User Meta details */
	function save_extra_user_profile_fields( $user_id ) { 
	
		if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }
		update_user_meta( $user_id, 'company_list', $_POST['company_list'] );
		
	}
	/* User Meta details */
	function save_newuser_data( $user_id ) { 
		if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }
		update_user_meta( $user_id, 'company_list', $_POST['company_list'] );
		
	}
}