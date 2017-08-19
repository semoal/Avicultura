<?php

	add_filter( 'vc_load_default_templates', 'hippo_vc_template_about_us' );
	function hippo_vc_template_about_us( $data ) {

		$template                   = array();
		$template[ 'name' ]         = esc_html__( 'About Us', 'sink' );
		$template[ 'image_path' ]   = get_template_directory_uri() . '/visual-composer/assets/images/thumbs/about-us.png'; // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'hippo_vc_template_about_us';

		ob_start();
		?>[vc_row][vc_column][vc_single_image image="907" img_size="full"][/vc_column][/vc_row][vc_row][vc_column width="1/2"][vc_single_image image="900" img_size="full"][/vc_column][vc_column width="1/2"][vc_column_text]
		<h3>The Story</h3>
		Continually disintermediate empowered mindshare after adaptive niche markets. Collaboratively incentivize backend benefits after high standards in functionalities. Rapidiously architect compelling communities with process-centric e-services. Uniquely transform emerging metrics for synergistic content. Dynamically embrace cross-media leadership skills after distributed web-readiness.

		Continually fabricate front-end human capital whereas empowered innovation. Quickly enhance mission-critical materials and visionary imperatives. Proactively seize viral "outside the box" thinking through client-based information. Intrinsicly transition standards compliant interfaces after cross functional models. Holisticly envisioneer open-source results whereas long-term high-impact human capital.

		<img class="alignnone wp-image-915 " src="http://demo3.themehippo.com/wordpress/sink/wp-content/uploads/2015/10/signature.jpg" alt="signature" width="128" height="50" />
		<strong>Jacob Carter</strong>
		<em>Founder of Sink</em>[/vc_column_text][/vc_column][/vc_row][vc_row css=".vc_custom_1445220549707{margin-top: 50px !important;}"][vc_column width="1/3"][vc_column_text]
		<h3>Our Philosopy</h3>
		Appropriately synthesize extensible technologies rather than interoperable niches. Appropriately grow ethical services after unique ROI. Uniquely benchmark premier resources after turnkey infomediaries.[/vc_column_text][/vc_column][vc_column width="1/3"][vc_column_text]
		<h3>Online Store</h3>
		Conveniently communicate error-free value and excellent synergy. Phosfluorescently drive seamless experiences whereas high-quality customer service. Completely parallel task high standards.[/vc_column_text][/vc_column][vc_column width="1/3"][vc_column_text]
		<h3>Academy</h3>
		Enthusiastically strategize bricks-and-clicks growth strategies rather than multidisciplinary sources. Author formulate go forward methods of empow rather than technically sound information.[/vc_column_text][/vc_column][/vc_row]<?php
		$template[ 'content' ] = ob_get_clean();
		array_unshift( $data, $template );

		return $data;
	}