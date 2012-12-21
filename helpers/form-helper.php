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
		ob_start();
		if ($type == 'checkbox') {
			?>
					<section id="<?php echo $section_prefix . $id ?>" class="<?php echo $class ?>" <?php echo $style; ?>>
						<input id="<?php echo $id_prefix . $id ?>" type="<?php echo $type ?>" name="<?php echo $name ?>" value="true" <?php echo $value ? 'checked' : '' ?>/>
						<label for="<?php echo $id_prefix . $id ?>"><?php echo $text ?></label>	
					</section>
				<?php	
			} elseif ($type == 'textarea') {
				?>
					<section id="<?php echo $section_prefix . $id ?>" class="<?php echo $class ?>" <?php echo $style; ?>>
						<label for="<?php echo $id_prefix . $id ?>"><?php echo $text ?></label>	
						<textarea id="<?php echo $id_prefix . $id ?>" name="<?php echo $name ?>" rows="2" cols="20"><?php echo $value ?></textarea>
					</section>
				<?php
			} elseif ($type == 'select') {
				?>
					<section id="<?php echo $section_prefix . $id ?>" class="<?php echo $class ?>" <?php echo $style; ?> >
						<label for="<?php echo $id_prefix . $id ?>"><?php echo $text ?></label>	
						<select name="<?php echo $name ?>" id="<?php echo $id_prefix . $id ?>" <?php echo ($type == 'multiselect' ? 'multiple="multiple"' : '') ?> >
							<?php foreach ($options as $key => $text): ?>
								<option id="<?php echo $key ?>" value="<?php echo $key ?>" <?php echo ($key == $value ? 'selected' : '' ) ?>><?php echo $text ?></option>
							<?php endforeach ?>
						</select>
					</section>
				<?php	
			} elseif ($type == 'multiselect') {
				?>
					<section id="<?php echo $section_prefix . $id ?>" class="<?php echo $class ?>" <?php echo $style; ?> >
						<label for="<?php echo $id_prefix . $id ?>"><?php echo $text ?></label>	
						<select name="<?php echo $name ?>[]" id="<?php echo $id_prefix . $id ?>" <?php echo ($type == 'multiselect' ? 'multiple="multiple"' : '') ?> >
							<?php foreach ($options as $key => $text): ?>
								<option id="<?php echo $key ?>" value="<?php echo $key ?>" <?php echo ((is_array($value) && in_array($key, $value) ) ? 'selected' : '' ) ?>><?php echo $text ?></option>
							<?php endforeach ?>
						</select>
					</section>
				<?php	
			} elseif( $type == 'text' ) {
				?>
					<section id="<?php echo $section_prefix . $id ?>" class="<?php echo $class ?>" <?php echo $style; ?>>
						<label for="<?php echo $id_prefix . $id ?>"><?php echo $text ?></label>	
						<input id="<?php echo $id_prefix . $id ?>" type="<?php echo $type ?>" name="<?php echo $name ?>" value="<?php echo $value ?>" />
					</section>
				<?php
			} elseif ( $type == 'date') {
				?>
					<section id="<?php echo $section_prefix . $id ?>" class="<?php echo $class ?>" <?php echo $style; ?>>
						<label for="<?php echo $id_prefix . $id ?>"><?php echo $text ?></label>	
						<input id="<?php echo $id_prefix . $id ?>_picker" class="trigger_datepicker" type="text" name="<?php echo $name ?>" <?php echo !empty($value) ? 'value="'.$value.'"' : ''; ?> />
					</section>
				<?php
			} elseif( $type == 'image' ) {
				?>
					<section id="<?php echo $section_prefix . $id ?>" class="<?php echo $class ?>" <?php echo $style; ?>>
						<label for="<?php echo $id_prefix . $id ?>"><?php echo $text ?></label>	
						<input id="fileupload" type="file" name="images" name="<?php echo $name ?>" multiple /> 
					</section>
				<?php
			} elseif ($type == 'radio') {
				?>
					<section id="<?php echo $section_prefix . $id ?>" class="<?php echo $class ?>" <?php echo $style; ?> >
						<label for="<?php echo $id_prefix . $id ?>"><?php echo $text ?></label>
						<?php foreach( $options as $key => $text ): ?>
						<div class="<?php echo $name . '_radios'; ?>">
							<label for="<?php echo $name . '_' . $key; ?>"><?php echo $text ?></label>
							<input id="<?php echo $name . '_' . $key; ?>" type="radio" value="<?php echo $text; ?>" name="<?php echo $name; ?>" />
						</div>
						<?php endforeach; ?>	
					</section>
				<?php	
			}
			
			return trim( ob_get_clean() );
		}
		
		private static function get_element_attributes( $item, $attributes, $method ) {
			$text = $item;
			if (isset($attributes['label'])) { $text = $attributes['label']; }
			
			// CSS if needed
			$class = '';
			if (isset($attributes['class'])) { $class = $attributes['class']; }
			
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
			
			return array(
					'name' => $name, 
					'value' => $value, 
					'text' => $text, 
					'id' => $id, 
					'type' => $type, 
					'class' => $class,
					'style' => $style
			);
	}
}