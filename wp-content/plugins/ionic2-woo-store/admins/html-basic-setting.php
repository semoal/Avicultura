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
$sow_rest_api_secret=get_option('sow_rest_api_secret',false);
$sow_rest_api_shipping=get_option('sow_rest_api_shipping',false);

?>
<div class="wrap">
	<!-- If we have any error by submiting the form, they will appear here -->
	<?php settings_errors();  ?>

	<form id="form-basic-options" action="" method="post" enctype="multipart/form-data">
		<h2>
			<?php _e( 'Basic Options', 'sow-rest-api' ); ?>
		</h2>
		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="blogname">
						<?php _e('Ionic2WooStore Secret') ?>
					</label>
				</th>
				<td>
					<input type="text" id="sow_secret" name="sow_secret" value="<?php echo esc_attr( isset($sow_rest_api_secret)?$sow_rest_api_secret:'' ); ?>" readonly="readonly" class="regular-text" style="width:500px;" />
					<p>
						<a onclick="get_sow_secret_key()" class="button-primary">Generate Serect</a>
					</p>
				</td>
			</tr>
		</table>



		<h2>
			<?php _e( 'Shipping Options', 'sow_api' ); ?>
		</h2>
		<a href="javascript:void(0);" id="btn_add_shipping_method" class="button-primary">Add Shipping Method</a>
		<br />
		<table class="form-table" id="table_shipping">
			<?php
			if(isset($sow_rest_api_shipping))
			{
				$method_array=$sow_rest_api_shipping;

				$method_array_string='';
				for ($i = 0; $i < count($method_array); $i++)
				{
					$method_array_string.=$i.',';
            ?>
			<tr>
				<th scope="row">
					<label for="blogname">Shipping Method Info</label>
					<br />
					<a onclick="remove_shipping_method(this,<?php echo $i;?>);" class="button-secondary">Remove Shipping Method</a>
				</th>
				<td>
					<input type="text" value="<?php echo $method_array[$i]['title'];?>" id="sow_method_title_<?php echo $i;?>" name="sow_method_title_<?php echo $i;?>" class="regular-text" />shipping method title
					<br />
					<input type="text" value="<?php echo $method_array[$i]['id'];?>" id="sow_method_id_<?php echo $i;?>" name="sow_method_id_<?php echo $i;?>" class="regular-text" />shipping method id
					<br />
					<input type="text" value="<?php echo $method_array[$i]['cost'];?>" id="sow_method_cost_<?php echo $i;?>" name="sow_method_cost_<?php echo $i;?>" class="regular-text" />shipping method cost
					<br />

				</td>
			</tr>
			<?php
				}
				if(strlen($method_array_string)>0){
					$method_array_string=substr($method_array_string,0,strlen($method_array_string)-1);
				}
            ?>
			<input type="hidden" id="method_last_id" name="method_last_id" value="<?php echo count($method_array);?>" />
			<input type="hidden" id="method_array" name="method_array" value="<?php echo $method_array_string;?>" />
			<?php
			}else{
            ?>
			<input type="hidden" id="method_last_id" name="method_last_id" value="0" />
			<input type="hidden" id="method_array" name="method_array" value="0" />
			<?php
			}

            ?>


		</table>

		<p class="submit">
			<input name="sow_basic_options_action" id="submit_options_form" type="submit" class="button-primary" value="<?php esc_attr_e('Save Settings', 'sow-rest-api'); ?>" />
		</p>

	</form>
</div>
<script type="text/javascript">
	jQuery('#btn_add_shipping_method').click(function () {
		var add_id = jQuery('#method_last_id').val();
		var add_html =
        '<tr>' +
		'<th scope="row">' +
			'<label for="blogname">Shipping Method Info</label>' +
			'<a onclick="remove_shipping_method(this,' + add_id + ');" class="button-secondary">Remove Shipping Method</a>' +
			'</th>' +
			'<td><input type="text" id="sow_method_title_' + add_id + '" name="sow_method_title_' + add_id + '" class="regular-text"/>shipping method title<br />' +
			'<input type="text" id="sow_method_id_' + add_id + '" name="sow_method_id_' + add_id + '"  class="regular-text"/>shipping method id<br />' +
			'<input type="text" id="sow_method_cost_' + add_id + '" name="sow_method_cost_' + add_id + '"  class="regular-text"/>shipping method cost<br />' +
			'</td></tr>';
		jQuery('#table_shipping').append(add_html);

		var method_array = jQuery('#method_array').val();
		if (add_id == '0') {
			jQuery('#method_array').val(0);
		} else {
			jQuery('#method_array').val(method_array + ',' + add_id);
		}

		jQuery('#method_last_id').val(Number(add_id) + 1);
	});

	function get_sow_secret_key() {
		var data = {
			'action': 'get_sow_secret_key'
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function (response) {
			jQuery('#sow_secret').val(response);
		});
	}

	function remove_shipping_method(element, id) {

		jQuery(element).parents('tr').remove();
		var method_array = jQuery('#method_array').val();
		if (method_array.indexOf(',') >= 0) {
			var temp_array = method_array.split(',');
			for (var i = 0; i < temp_array.length; i++) {
				if (temp_array[i] == id) {
					temp_array.splice(i, 1);
					break;
				}
			}
			jQuery('#method_array').val(temp_array.join(','));
		} else {
			jQuery('#method_array').val(0);
			jQuery('#method_last_id').val(0);
		}
	}
</script>