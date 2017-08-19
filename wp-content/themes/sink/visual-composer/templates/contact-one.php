<?php

	add_filter( 'vc_load_default_templates', 'hippo_vc_template_contact_one' );
	function hippo_vc_template_contact_one( $data ) {

		$template                   = array();
		$template[ 'name' ]         = esc_html__( 'Contact One', 'sink' );
		$template[ 'image_path' ]   = get_template_directory_uri() . '/visual-composer/assets/images/thumbs/contact-one.png'; // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'hippo_vc_template_contact_one';

		ob_start();
		?>[vc_row css=".vc_custom_1442210816001{border-bottom-width: 0px !important;padding-bottom: 100px !important;}"][vc_column width="7/12" el_class="contact-form-wrapper"][contact-form-7 id="4"][/vc_column][vc_column width="5/12"][vc_column_text]

		<address>384 Maple Circle
			Simi Valley, Nevada 47424
			Phone : +12 30 456789
			Email : yourname@transport.com</address>[/vc_column_text][vc_gmaps link="#E-8_JTNDaWZyYW1lJTIwc3JjJTNEJTIyaHR0cHMlM0ElMkYlMkZ3d3cuZ29vZ2xlLmNvbSUyRm1hcHMlMkZlbWJlZCUzRnBiJTNEJTIxMW0xOCUyMTFtMTIlMjExbTMlMjExZDYzMDQuODI5OTg2MTMxMjcxJTIxMmQtMTIyLjQ3NDY5NjgwMzMwOTIlMjEzZDM3LjgwMzc0NzUyMTYwNDQzJTIxMm0zJTIxMWYwJTIxMmYwJTIxM2YwJTIxM20yJTIxMWkxMDI0JTIxMmk3NjglMjE0ZjEzLjElMjEzbTMlMjExbTIlMjExczB4ODA4NTg2ZTYzMDI2MTVhMSUyNTNBMHg4NmJkMTMwMjUxNzU3YzAwJTIxMnNTdG9yZXklMkJBdmUlMjUyQyUyQlNhbiUyQkZyYW5jaXNjbyUyNTJDJTJCQ0ElMkI5NDEyOSUyMTVlMCUyMTNtMiUyMTFzZW4lMjEyc3VzJTIxNHYxNDM1ODI2NDMyMDUxJTIyJTIwd2lkdGglM0QlMjI0MTUlMjIlMjBoZWlnaHQlM0QlMjIzNTAlMjIlMjBmcmFtZWJvcmRlciUzRCUyMjAlMjIlMjBzdHlsZSUzRCUyMmJvcmRlciUzQTAlMjIlMjBhbGxvd2Z1bGxzY3JlZW4lM0UlM0MlMkZpZnJhbWUlM0U="][/vc_column][/vc_row][vc_row][vc_column][vc_column_text]
		<h2 style="text-align: center;">World Wide Offices</h2>
		[/vc_column_text][vc_row_inner][vc_column_inner width="1/3"][vc_column_text]
		<h3>United States Office</h3>
		[/vc_column_text][vc_column_text]

		<address>384 Maple Circle
			Simi Valley, Nevada 47424
			Phone : +12 30 456789
			Email : yourname@transport.com</address>[/vc_column_text][/vc_column_inner][vc_column_inner width="1/3"][vc_column_text]
		<h3>United Kingdom Office</h3>
		[/vc_column_text][vc_column_text]

		<address>384 Maple Circle
			Simi Valley, Nevada 47424
			Phone : +12 30 456789
			Email : yourname@transport.com</address>[/vc_column_text][/vc_column_inner][vc_column_inner width="1/3"][vc_column_text]
		<h3>Bangladesh Office</h3>
		[/vc_column_text][vc_column_text]

		<address>House# 687-689, Road# 10
			Mirpur DOHS, Dhaka 1216
			Phone : +12 30 456789
			Email : yourname@transport.com</address>[/vc_column_text][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row]<?php
		$template[ 'content' ] = ob_get_clean();
		array_unshift( $data, $template );

		return $data;
	}