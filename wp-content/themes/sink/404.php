<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	get_header(); ?>
	<div id="content" class="site-content">
		<div class="row">
			<div class="col-md-12">
				<div id="primary" class="content-area">
					<main id="main" class="site-main" role="main">
						<div class="page-notfound">
							<strong><?php esc_html_e( 'Page Not Found', 'sink' ); ?></strong>

							<i><?php esc_html_e( 'Sorry the Page Could not be Found here.', 'sink' ); ?></i>

							<p><?php esc_html_e( 'Try using the button below to go to main page of the site', 'sink' ); ?></p>

							<div class="home-link clearfix">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Go Back to Home', 'sink' ); ?></a>
							</div>
						</div>
						<!-- .page-notfound -->
					</main>
					<!-- main -->
				</div>
				<!-- #primary -->
			</div>
			<!-- .col-* -->
		</div>
		<!-- .row -->
	</div> <!-- #content -->
<?php get_footer();