<?php

/**
 * html_basic_setting short summary.
 *
 * html_basic_setting description.
 *
 * @version 1.0
 * @author wolf
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
wp_enqueue_media();
$sow_rest_api_slider=get_option('sow_rest_api_slider',false);

?>
<div class="wrap">
	<!-- If we have any error by submiting the form, they will appear here -->
	<?php settings_errors( 'sow-api-settings-errors' ); ?>

	<form action="" method="post" enctype="multipart/form-data">

		<h2>
			<?php _e( 'HomePage Slider Options', 'sow_api' ); ?>
		</h2>
		<a href="javascript:void(0);" id="btn_add_slider" class="button-primary">Add Slide</a>
		<br />
		<table class="form-table" id="table_shipping">
			<?php
			if(isset($sow_rest_api_slider))
			{	
				$slider_array_string='';
				for ($i = 0; $i < count($sow_rest_api_slider); $i++)
				{
					$slider_array_string.=$i.',';
            ?>
			<tr>
				<th scope="row">
					<label for="slider1">Slide Image</label><br />
					<a class="button-secondary" onclick="remove_slider(this,<?php echo $i;?>)">Delete</a>
				</th>
				<td>
					<input type="text" value="<?php echo $sow_rest_api_slider[$i];?>" id="slider_<?php echo $i;?>" name="slider_<?php echo $i;?>" class="large-text" readonly="readonly" />
					<p>
						<div>
							<img  src="<?php echo $sow_rest_api_slider[$i];?>" alt="" title="" />
						</div>
						<a onclick="renderMediaUploader(this)" class="button-primary">Upload</a>
						
					</p>
				</td>
			</tr>
			<?php
				}
				if(strlen($slider_array_string)>0){
					$slider_array_string=substr($slider_array_string,0,strlen($slider_array_string)-1);
				}
            ?>
			<input type="hidden" id="slider_last_id" name="slider_last_id" value="<?php echo count($sow_rest_api_slider);?>" />
			<input type="hidden" id="slider_array" name="slider_array" value="<?php echo $slider_array_string;?>" />
			<?php
			}else{
            ?>
			<input type="hidden" id="slider_last_id" name="slider_last_id" value="0" />
			<input type="hidden" id="slider_array" name="slider_array" value="0" />
			<?php
			}

            ?>
		</table>
		<p class="submit">
			<input name="sow_slider_options_action" id="submit_options_form" type="submit" class="button-primary" value="<?php esc_attr_e('Save Settings', 'sow-rest-api'); ?>" />
		</p>

	</form>
</div>
<script type="text/javascript">
	jQuery('#btn_add_slider').click(function () {
		var add_id = jQuery('#slider_last_id').val();
		var add_html =
        '<tr>' +
		'<th scope="row">' +
			'<label for="blogname">Slide Image</label><br/>' +
			'<a class="button-secondary" onclick="remove_slider(this,'+add_id+')">Delete</a>'+
			'</th>' +
			'<td><input type="text" id="slider_'+add_id+'" name="slider_'+add_id+'" class="large-text" readonly="readonly" /><br />' +
			'<p><div><img  src="" alt="" title="" /></div>'+
			'<a onclick="renderMediaUploader(this)" class="button-primary">Upload</a>'+
			'</p>'
			'</td></tr>';
			jQuery('#table_shipping').append(add_html);
		
		var slider_array = jQuery('#slider_array').val();
		if (add_id == '0') {
			jQuery('#slider_array').val(0);
		} else {
			jQuery('#slider_array').val(slider_array + ',' + add_id);
		}
	jQuery('#slider_last_id').val( Number(add_id) + 1);
		
	});

	function remove_slider(element, id) {
		jQuery(element).parents('tr').remove();
		var slider_array = jQuery('#slider_array').val();
		if (slider_array.indexOf(',') >= 0) {
			var temp_array = slider_array.split(',');
			for (var i = 0; i < temp_array.length; i++) {
				if (temp_array[i] == id) {
					temp_array.splice(i, 1);
					break;
				}
			}
			jQuery('#slider_array').val(temp_array.join(','));
		} else {
			jQuery('#slider_array').val(0);
			jQuery('#slider_last_id').val(0);
		}
	}

	function renderMediaUploader(element) {
		'use strict';
		
		var file_frame, image_data;
		if (undefined !== file_frame) {
			file_frame.open();
			return;
		}

		file_frame = wp.media.frames.file_frame = wp.media({
			frame: 'post',
			state: 'insert',
			multiple: false
		});

		file_frame.on('insert', function () {
			var json = file_frame.state().get('selection').first().toJSON();
			if (0 > jQuery.trim(json.url.length)) {
				return;
			}
		
			jQuery(element).parent('td')
				.find('img')
					.attr('src', json.url)
					.attr('alt', json.caption)
					.attr('title', json.title)
								.show()
				.removeClass('hidden');
			jQuery(element).parent('td')
				.find('input').val(json.url);
		});

		// Now display the actual file_frame
		file_frame.open();

	}
	
</script>