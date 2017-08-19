<?php

	add_filter( 'vc_load_default_templates', 'hippo_vc_template_shiping_information' );
	function hippo_vc_template_shiping_information( $data ) {

		$template                   = array();
		$template[ 'name' ]         = esc_html__( 'Shipping Information', 'sink' );
		$template[ 'image_path' ]   = get_template_directory_uri() . '/visual-composer/assets/images/thumbs/shiping-information.png'; // always use preg replace to be sure that "space" will not break logic
		$template[ 'custom_class' ] = 'hippo_vc_template_shiping_information';

		ob_start();
		?>[vc_row][vc_column][vc_column_text]
		<h3>Conveniently productize exceptional ideas without backward-compatible models.</h3>
		Energistically synergize viral bandwidth through market-driven mindshare. Assertively synthesize low-risk high-yield results for leveraged core competencies. Compellingly transition bleeding-edge potentialities for collaborative markets. Seamlessly impact corporate e-services for timely process improvements. Holisticly expedite intermandated niche markets via ubiquitous imperatives.

		Completely unleash fully tested meta-services vis-a-vis cutting-edge mindshare. Assertively synthesize mission-critical technology vis-a-vis adaptive internal or "organic" sources. Globally customize wireless models whereas professional initiatives. Appropriately morph timely scenarios rather than efficient functionalities. Uniquely evolve cooperative schemas rather than market-driven architectures.
		<h3>Free two-day shipping</h3>
		Seamlessly foster real-time internal or "organic" sources through standardized schemas. Compellingly repurpose one-to-one niche markets before orthogonal vortals. Dramatically deploy frictionless networks for equity invested information. <ins datetime="2015-10-20T23:58:15+00:00">Dramatically simplify virtual platforms and 24/7 collaboration and idea-sharing.</ins> Authoritatively empower an expanded array of materials rather than cost effective products.

		Energistically formulate enterprise-wide applications with market positioning best practices. <em>Globally transform team</em> building action items with cost effective vortals. Intrinsicly leverage existing real-time communities through team driven web services. Compellingly supply real-time ideas whereas team driven e-services. Continually monetize leading-edge growth strategies before empowered paradigms.
		<ul>
			<li><strong>Order before 5:00pm, Monday to Friday</strong> &rarr; Delivers in two business days</li>
			<li><strong>Order before 5:00pm on a Friday</strong> &rarr; Delivers on Tuesday</li>
			<li><strong>Order after 5:00pm, Monday to Friday</strong> &rarr; Delivers in three business days</li>
			<li><strong>Order after 5:00pm on a Friday, or any time on Saturday or Sunday</strong> &rarr; Delivers on Wednesday</li>
		</ul>
		&nbsp;
		<h3>Shipping cost</h3>
		<table>
			<thead>
			<tr>
				<th style="text-align: left;">Country</th>
				<th style="text-align: left;">FeDex Priority</th>
				<th style="text-align: left;">FeDex Economy</th>
				<th style="text-align: left;">Polish Post</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td style="text-align: left;">POLAND</td>
				<td style="text-align: left;">UNAVAILABLE</td>
				<td style="text-align: left;">UNAVAILABLE</td>
				<td style="text-align: left;">1-4 WORKING DAYS</td>
			</tr>
			<tr>
				<td style="text-align: left;">EUROPE</td>
				<td style="text-align: left;">1-2 WORKING DAYS</td>
				<td style="text-align: left;">2-3 WORKING DAYS</td>
				<td style="text-align: left;">3-8 WORKING DAYS</td>
			</tr>
			<tr>
				<td style="text-align: left;">USA</td>
				<td style="text-align: left;">1-2 WORKING DAYS</td>
				<td style="text-align: left;">3-4 WORKING DAYS</td>
				<td style="text-align: left;">5-12 WORKING DAYS</td>
			</tr>
			<tr>
				<td style="text-align: left;">CANADA</td>
				<td style="text-align: left;">1-2 WORKING DAYS</td>
				<td style="text-align: left;">3-6 WORKING DAYS</td>
				<td style="text-align: left;">5-12 WORKING DAYS</td>
			</tr>
			<tr>
				<td style="text-align: left;">SOUTH AMERICA</td>
				<td style="text-align: left;">3-4 WORKING DAYS</td>
				<td style="text-align: left;">4-6 WORKING DAYS</td>
				<td style="text-align: left;">5-12 WORKING DAYS</td>
			</tr>
			<tr>
				<td style="text-align: left;">ASIA</td>
				<td style="text-align: left;">2-3 WORKING DAYS</td>
				<td style="text-align: left;">4-6 WORKING DAYS</td>
				<td style="text-align: left;">5-12 WORKING DAYS</td>
			</tr>
			<tr>
				<td style="text-align: left;">AFRICA</td>
				<td style="text-align: left;">2-4 WORKING DAYS</td>
				<td style="text-align: left;">4-8 WORKING DAYS</td>
				<td style="text-align: left;">5-12 WORKING DAYS</td>
			</tr>
			<tr>
				<td style="text-align: left;">AUSTRALIA &amp; OCEANIA</td>
				<td style="text-align: left;">2-3 WORKING DAYS</td>
				<td style="text-align: left;">3-5 WORKING DAYS</td>
				<td style="text-align: left;">5-12 WORKING DAYS</td>
			</tr>
			</tbody>
		</table>
		&nbsp;
		<h3>Shipping Policies</h3>
		Quickly enhance mission-critical architectures vis-a-vis standards compliant potentialities. Conveniently target viral mindshare before client-based results. Intrinsicly whiteboard value-added deliverables without resource maximizing schemas. Credibly scale client-centered applications before value-added materials. Completely facilitate leveraged web-readiness vis-a-vis principle-centered systems.

		Professionally plagiarize viral information for web-enabled supply chains. Enthusiastically redefine stand-alone technology via cutting-edge imperatives. Progressively restore plug-and-play ideas and one-to-one portals. Enthusiastically aggregate 2.0 systems whereas alternative catalysts for change. Seamlessly monetize stand-alone materials vis-a-vis empowered supply chains.[/vc_column_text][/vc_column][/vc_row]<?php
		$template[ 'content' ] = ob_get_clean();
		array_unshift( $data, $template );

		return $data;
	}