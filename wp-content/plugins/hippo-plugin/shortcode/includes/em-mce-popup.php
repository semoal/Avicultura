<?php

	//$wp_content_dir = trim($_GET['content_dir']);
	//$base_path = trim( $_GET[ 'abspath' ] );
	//$base_path = explode('wp-content', __FILE__);
	//$base_path = explode($wp_content_dir, __FILE__);

	//$base_path = $base_path[ 0 ];

	//require_once( $base_path . 'wp-load.php' );
?>


<div id="wp-shortcode-body-content">
	<form method="post" action="" id="em-shortcode-form">
		<!-- start -->
		<div id="shortcodes">
			<ul class="shortcode-selector">
				<li>
					<label>
						<div class="label">
							<?php _e( 'Select Shortcode:', 'hippo-plugin' ) ?>
						</div>

						<div class="input-field">
							<select name="shortcode" id="shortcode-list">
								<option
									value="0"
									data-description="<?php _e( 'Select a shortcode from list', 'hippo-plugin' ) ?>">
									-- Select --
								</option>
								<?php
									$shortcode     = em_Shortcodes_Engine::getInstance();
									$get_shortcode = $shortcode->get_shortcodes();


									foreach ( $get_shortcode as $name => $codes ) {

										?>
										<option data-parent=""
										        value="<?php echo $name ?>"
										        data-description="<?php echo isset( $codes[ 'description' ] ) ? $codes[ 'description' ] : '' ?>"><?php echo $codes[ 'title' ]; ?></option>
										<?php

										if ( isset( $codes[ 'child' ] ) and is_array( $codes[ 'child' ] ) ) {
											foreach ( $codes[ 'child' ] as $cname => $child ) {
												$parent = implode( ',', $child[ 'child_of' ] )
												?>

												<option data-parent="<?php echo $parent ?>"
												        value="<?php echo $cname ?>"
												        data-description="<?php echo isset( $child[ 'description' ] ) ? $child[ 'description' ] : '' ?>">
													-- <?php echo $child[ 'title' ]; ?></option>
												<?php

											}
										}


									} ?>
							</select>
						</div>
						<span id="shortcode-loader" class="spinner"></span>
					</label>

					<div id="shortcode-description" class="description">
						<?php _e( 'Select a shortcode from list.', 'hippo-plugin' ) ?>
					</div>
				</li>
			</ul>
		</div>
		<!-- end -->

		<!-- Attributes -->


		<!-- end Attributes -->
		<ul id="shortcode-attributes" class="shortcode-wrapper"></ul>

	</form>

	<p>
		<input type="button" id="insert-shortcode" class="button button-primary"
		       value="<?php esc_attr_e( 'Insert', 'hippo-plugin' ); ?>"
		       title="<?php esc_attr_e( 'Insert', 'hippo-plugin' ); ?>">
		<span id="insert-shortcode-loader" class="spinner"></span>
		<input type="button" class="button" id="cancel-shortcode"
		       value="<?php esc_attr_e( 'Close', 'hippo-plugin' ); ?>"
		       title="<?php esc_attr_e( 'Close', 'hippo-plugin' ); ?>" onclick="tb_remove();"/>
		<br class="clear">
	</p>
</div>


<script type="text/javascript">
	jQuery(function ($) {


		//console.log(hippoAdminJSObject);


		function call_select2() {


			$("select.hippo-plugin-select-2, select.hippo-plugin-select-post").select2();


			function icon_format(state) {
				if (!state.id) {
					return state.text;
				}
				return $("<span><i class='" + state.id + "'></i> &nbsp; &nbsp; " + state.text + "</span>");
			}

			function old_icon_format(state) {
				if (!state.id) return state.text; // optgroup
				return "<span><i class='" + state.id + "'></i> &nbsp; &nbsp; " + state.text + "</span>";
			}


			$("select.hippo-plugin-select2-icon").select2({
				templateResult    : icon_format,
				templateSelection : icon_format,
				placeholder       : "Select Icon",
				allowClear        : true,
				formatResult      : old_icon_format,
				formatSelection   : old_icon_format
			});


			/*$("select.hippo-plugin-select2-old-icon").select2({
				formatResult    : old_icon_format,
				formatSelection : old_icon_format,
				placeholder     : "Select Icon",
				allowClear      : true,
				escapeMarkup    : function (m) {
					return m;
				}
			});*/


		}


		// define ajaxurl
		var ajaxurl = "<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>";


		var sortableSetting = {
			axis   : "y",
			handle : ".sort-shortcode-item",
			stop   : function (event, ui) {
				// IE doesn't register the blur when sorting
				// so trigger focusout handlers to remove .ui-state-focus
				// ui.item.children( "h3" ).triggerHandler( "focusout" );
			}
		};


		$.fn.shortcodeAccordion = function () {


			$(this).find('>div:not(.active)').next().hide();

			$(this).find('>div>.shortcode-header').on('click', function (e) {

				e.stopImmediatePropagation();

				$(this).parent().next().slideToggle("normal", function () {
					$(this).prev().toggleClass('active');
				});

			});

		}

		function reInitJob() {


			/**
			 * Local only
			 */

			$('.hippo-has-condition').find('>label>.input-field>select').on('change', function () {


				var $condition = $(this).closest('.hippo-has-condition').data('condition');
				var $value = $(this).val();
				var $element = $(this).closest('ul');

				if (typeof($condition[$value]) !== 'undefined') {

					if (typeof( $condition[$value]['show'] ) !== 'undefined') {
						$.each($condition[$value]['show'], function (key, el) {
							$element.find('>[data-name="' + el + '"]').show();
							$element.find('>[data-name="' + el + '"]>label>.input-field>select').removeAttr('disabled');
							$element.find('>[data-name="' + el + '"]>label>.input-field>input').removeAttr('disabled');

						});
					}

					if (typeof( $condition[$value]['hide'] ) !== 'undefined') {
						$.each($condition[$value]['hide'], function (key, el) {
							$element.find('>[data-name="' + el + '"]').hide();
							$element.find('>[data-name="' + el + '"]>label>.input-field>select').attr('disabled', true);
							$element.find('>[data-name="' + el + '"]>label>.input-field>input').attr('disabled', true);
						});
					}

				}
				//console.log($condition);
			});

			$('.hippo-has-condition').find('>label>.input-field>select').trigger('change');


			/**
			 * Child Also
			 */
			$('.hippo-has-condition.with-child').find('>label>.input-field>select').on('change', function () {


				var $condition = $(this).closest('.hippo-has-condition').data('condition');
				var $value = $(this).val();
				var $element = $(this).closest('ul');

				if (typeof($condition[$value]) !== 'undefined') {

					if (typeof( $condition[$value]['show'] ) !== 'undefined') {
						$.each($condition[$value]['show'], function (key, el) {
							$element.find('[data-name="' + el + '"]').show();
							$element.find('[data-name="' + el + '"]>label>.input-field>select').removeAttr('disabled');
							$element.find('[data-name="' + el + '"]>label>.input-field>input').removeAttr('disabled');

						});
					}

					if (typeof( $condition[$value]['hide'] ) !== 'undefined') {
						$.each($condition[$value]['hide'], function (key, el) {
							$element.find('[data-name="' + el + '"]').hide();
							$element.find('[data-name="' + el + '"]>label>.input-field>select').attr('disabled', true);
							$element.find('[data-name="' + el + '"]>label>.input-field>input').attr('disabled', true);
						});
					}

				}
				//console.log($condition);
			});

			$(' .hippo-has-condition.with-child').find('>label>.input-field>select').trigger('change');


		}


		$(document).on('shortcodeHTMLAppended', function (e) {


			$(".shortcode-wrapper").sortable(sortableSetting);
			$(".shortcode-item").shortcodeAccordion();


			/*$('body .shortcode-color-picker-field').each(function(){
			 var data = $(this).wpColorPicker();
			 console.log(data);
			 });*/

			//console.log( $.wp.wpColorPicker );

			$('.shortcode-color-picker-field').wpColorPicker();


			/*$('.shortcode-color-picker-field').iris({
			 hide    : false,
			 palettes: true
			 });*/

			setTimeout(function () {
				$('.editor-selected-contents').text(tinyMCE.activeEditor.selection.getContent());

			}, 200);

			call_select2();
			reInitJob();


		});

		// Cloning Item

		$('body').on('click', '.clone-shortcode-item', function (e) {

			e.preventDefault();
			e.stopImmediatePropagation();

			var id = 'contents' + $(this).closest('.shortcode-wrapper').attr('data-shortcode');

			var data = $('body').data(id);
			var self = this;


			$(this).closest('.shortcode-wrapper').append(data);

			$('#shortcode-attributes').find('[data-shortcode]').each(function () {
				//    $(this).data('contents', $(this).html());
			});


			//setTimeout(function () {
			$(document).trigger('shortcodeHTMLAppended', [$(self).closest('.shortcode-wrapper'), data]);
			//}, 200);
			///  re init

			/*$(".shortcode-wrapper").sortable(sortableSetting);
			 $(".shortcode-item").shortcodeAccordion();


			 setTimeout(function () {
			 $('.editor-selected-contents').text(tinyMCE.activeEditor.selection.getContent());
			 }, 200);

			 // $('.shortcode-color-picker-field').wpColorPicker();

			 $('.shortcode-color-picker-field').iris({
			 hide    : false,
			 palettes: true
			 });


			 call_select2();
			 reInitJob()();*/


		});

		$('body').on('click', '.remove-shortcode-item', function () {
			$(this).closest('.shortcode-item').detach();
			$(".shortcode-wrapper").sortable('refresh');
		});


		// on choose shortcode
		$('#shortcode-list').on('change', function (e) {

			e.stopImmediatePropagation();

			var selected_shortcode = $(this).val();
			var selected_shortcode_parent = $(this).find('option:selected').data('parent');
			$('#shortcode-description').html($(this).find('option:selected').data('description'));

			// remove previously added item form element with event
			$('#shortcode-attributes').find('li').detach();

			// donot send ajax request when empty item selected again
			if (selected_shortcode == '0') {
				return '';
			}

			// ready data to send via ajax
			var data = {
				action : 'build_shortcode',
				name   : selected_shortcode,
				parent : selected_shortcode_parent
			};

			$('#shortcode-loader').addClass('is-active');
			$('#insert-shortcode').prop('disabled', true);

			$.post(ajaxurl, data, function (data) {

				$('#shortcode-attributes').append(data);


				$('#shortcode-attributes').find('[data-shortcode]').each(function () {
					$('body').data('contents' + $(this).attr('data-shortcode'), $(this).html());
				});


				$('#shortcode-loader').removeClass('is-active');
				$('#insert-shortcode').prop('disabled', false);


				//setTimeout(function () {
				$(document).trigger('shortcodeHTMLAppended', [$('#shortcode-attributes'), data]);
				//}, 200);

				/*$(".shortcode-wrapper").sortable(sortableSetting);
				 $(".shortcode-item").shortcodeAccordion();


				 setTimeout(function () {
				 $('.editor-selected-contents').text(tinyMCE.activeEditor.selection.getContent());
				 }, 200);

				 $('.shortcode-color-picker-field').iris({
				 hide    : false,
				 palettes: true
				 });

				 //  $('.shortcode-color-picker-field').wpColorPicker();

				 call_select2();
				 reInitJob();*/
			});

		});  // end change


		// media clear

		$('body').on('click', '.hippo-media-clear', function () {
			$(this).parent().find('input').val('');
			$(this).removeClass('display').addClass('display-none');
		});


		// open media window

		$('body').on('click', '.open-media', function (e) {

			e.stopImmediatePropagation();

			var opener = $(this);
			var shortcode_media;

			if (shortcode_media) {
				shortcode_media.open();
				return;
			}
			// registering media
			shortcode_media = wp.media.frames.shortcode_media = wp.media({
				//Create our media frame
				className : 'media-frame shortcode-media-frame',
				frame     : 'select', //Allow Select Only
				multiple  : false //Disallow Mulitple selections
				//,library  : {
				//  type: 'image' //Only allow images
				//}
			});

			shortcode_media.on('open', function () {

				// Grab our attachment selection and construct a JSON representation of the model.
				var selection = shortcode_media.state().get('selection');
				attachment = wp.media.attachment(opener.next().val());
				attachment.fetch();
				console.log(attachment);
				selection.add(attachment ? [attachment] : []);
			});

			shortcode_media.on('select', function () {

				// Grab our attachment selection and construct a JSON representation of the model.
				var media_attachment = shortcode_media.state().get('selection').first().toJSON();

				// Send the attachment URL to our custom input field via jQuery.
				opener.prev().val(media_attachment.url);
				opener.next().val(media_attachment.id);
				opener.next().next().removeClass('display-none').addClass('display');
			});

			// Now that everything has been set, let's open up the frame.
			shortcode_media.open();
		});


		// on insert
		$('#insert-shortcode').on('click', function (e) {

			e.preventDefault();

			$('#insert-shortcode-loader').addClass('display');

			var $name = '[name]'

			$('.child-shortcode > ul > li').each(function (cindex) {

				$(this).find($name).attr('name', function () {
					var name = $(this).attr('name');
					if (/__0__+/.test(name)) {
						return name.replace(/__0__+/, cindex);
					}
				});

				$(this).find('>ul>.child-shortcode > ul > li').each(function (gcindex) {

					$(this).find($name).attr('name', function () {
						var name = $(this).attr('name');
						if (/__1__+/.test(name)) {
							return name.replace(/__1__+/, gcindex);
						}
					});


					$(this).find('>ul>.child-shortcode > ul > li').each(function (gccindex) {

						$(this).find($name).attr('name', function () {
							var name = $(this).attr('name');
							if (/__2__+/.test(name)) {
								return name.replace(/__2__+/, gccindex);
							}
						});
					});

				});

			});

			////

			var shortcodedata = $('#em-shortcode-form').serialize();

			// ready data to send via ajax
			var data = {
				action : 'save_shortcode',
				data   : shortcodedata
			};

			$.post(ajaxurl, data, function (response) {
				$('#insert-shortcode-loader').removeClass('display');

				tinyMCE.activeEditor.execCommand('mceInsertContent', false, response);
				tb_remove();
			});
		});
	});
</script>