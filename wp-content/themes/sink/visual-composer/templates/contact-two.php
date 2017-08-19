<?php

	add_filter( 'vc_load_default_templates', 'hippo_vc_template_contact_two' );
	function hippo_vc_template_contact_two( $data ) {

		$template                   = array();
		$template[ 'name' ]         = esc_html__( 'Contact Two', 'sink' );
		$template[ 'image_path' ]   = get_template_directory_uri() . '/visual-composer/assets/images/thumbs/contact-two.png'; // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'hippo_vc_template_contact_two';

		ob_start();
		?>[vc_row css=".vc_custom_1442213219497{margin-bottom: 100px !important;}"][vc_column el_class="contact-form-wrapper"][contact-form-7 id="730"][/vc_column][/vc_row][vc_row][vc_column width="1/3"][vc_column_text]
		<h2>Address</h2>
		[/vc_column_text][vc_column_text]

		<address>384 Maple Circle
			Simi Valley, Nevada 47424
			Phone : +12 30 456789
			Email : yourname@transport.com</address>[/vc_column_text][/vc_column][vc_column width="2/3"][vc_column_text]
		<h2>How to reach</h2>
		[/vc_column_text][vc_column_text]Holisticly promote competitive convergence after just in time methodologies. Globally transform functional models and B2C total linkage. Professionally embrace turnkey solutions vis-a-vis out-of-the-box leadership skills. Synergistically benchmark team building systems whereas front-end schemas.[/vc_column_text][/vc_column][/vc_row][vc_row css=".vc_custom_1442213312259{margin-bottom: 0px !important;}"][vc_column][vc_gmaps link="#E-8_JTNDaWZyYW1lJTIwc3JjJTNEJTIyaHR0cHMlM0ElMkYlMkZ3d3cuZ29vZ2xlLmNvbSUyRm1hcHMlMkZlbWJlZCUzRnBiJTNEJTIxMW0xOCUyMTFtMTIlMjExbTMlMjExZDYzMDQuODI5OTg2MTMxMjcxJTIxMmQtMTIyLjQ3NDY5NjgwMzMwOTIlMjEzZDM3LjgwMzc0NzUyMTYwNDQzJTIxMm0zJTIxMWYwJTIxMmYwJTIxM2YwJTIxM20yJTIxMWkxMDI0JTIxMmk3NjglMjE0ZjEzLjElMjEzbTMlMjExbTIlMjExczB4ODA4NTg2ZTYzMDI2MTVhMSUyNTNBMHg4NmJkMTMwMjUxNzU3YzAwJTIxMnNTdG9yZXklMkJBdmUlMjUyQyUyQlNhbiUyQkZyYW5jaXNjbyUyNTJDJTJCQ0ElMkI5NDEyOSUyMTVlMCUyMTNtMiUyMTFzZW4lMjEyc3VzJTIxNHYxNDM1ODI2NDMyMDUxJTIyJTIwd2lkdGglM0QlMjIxMTcwJTIyJTIwaGVpZ2h0JTNEJTIyNDAwJTIyJTIwZnJhbWVib3JkZXIlM0QlMjIwJTIyJTIwc3R5bGUlM0QlMjJib3JkZXIlM0EwJTIyJTIwYWxsb3dmdWxsc2NyZWVuJTNFJTNDJTJGaWZyYW1lJTNF" css=".vc_custom_1442213304080{margin-bottom: 0px !important;}"][/vc_column][/vc_row]<?php
		$template[ 'content' ] = ob_get_clean();
		array_unshift( $data, $template );

		return $data;
	}