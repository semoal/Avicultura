<?php
	defined( 'ABSPATH' ) or die( 'Keep Silent' );
?></div> <!-- .contents -->

<!-- copyright-section start -->
<footer class="footer text-center">
	<div class="row">
		<div class="col-sm-12">
			<div class="footer-logo">
				<a href="<?php echo esc_url( home_url( '/' ) ) ?>"
				   title="<?php echo esc_attr( get_bloginfo( 'name' ) ) ?>">
					<img
						src="<?php echo esc_url( hippo_option( 'footer-logo', 'url', get_template_directory_uri() . '/img/footer-logo.png' ) ) ?>"
						alt="<?php echo esc_attr( get_bloginfo( 'name' ) ) ?>"/>
				</a>
			</div>

			<div class="footer-menu">
				<?php
					wp_nav_menu( apply_filters( 'hippo_wp_nav_menu_footer', array(
						             'theme_location' => 'footer',
						             'items_wrap'     => '<ul>%3$s</ul>',
					             ) )
					);
				?>
			</div>

			<div class="footer-contact">
				<?php if ( hippo_option( 'footer-contact' ) ) : ?>
					<?php echo wp_kses_post( hippo_option( 'footer-contact' ) ); ?>
				<?php endif; ?>
			</div>

			<?php if ( hippo_option( 'social-section-show', FALSE, TRUE ) ) : ?>
				<div class="social-section">
					<ul class="social-shares rounded small icon-white list-inline">
						<?php if ( hippo_option( 'rss-link', FALSE, TRUE ) ) : ?>
							<li>
								<a href="<?php echo esc_url( get_bloginfo( 'rss2_url' ) ); ?>"
								   target="_blank"><i class="fa fa-rss"></i></a>
							</li>
						<?php endif; ?>

						<?php if ( hippo_option( 'facebook-link' ) ) : ?>
							<li><a href="<?php echo esc_url( hippo_option( 'facebook-link' ) ); ?>"
							       target="_blank"><i class="fa fa-facebook"></i></a></li>
						<?php endif; ?>

						<?php if ( hippo_option( 'twitter-link' ) ) : ?>
							<li><a href="<?php echo esc_url( hippo_option( 'twitter-link' ) ); ?>"
							       target="_blank"><i class="fa fa-twitter"></i></a></li>
						<?php endif; ?>

						<?php if ( hippo_option( 'google-plus-link' ) ) : ?>
							<li><a href="<?php echo esc_url( hippo_option( 'google-plus-link' ) ); ?>"
							       target="_blank"><i class="fa fa-google-plus"></i></a></li>
						<?php endif; ?>

						<?php if ( hippo_option( 'youtube-link' ) ) : ?>
							<li><a href="<?php echo esc_url( hippo_option( 'youtube-link' ) ); ?>"
							       target="_blank"><i class="fa fa-youtube"></i></a></li>
						<?php endif; ?>

						<?php if ( hippo_option( 'skype-link' ) ) : ?>
							<li><a href="<?php echo esc_url( hippo_option( 'skype-link' ) ); ?>"
							       target="_blank"><i class="fa fa-skype"></i></a></li>
						<?php endif; ?>

						<?php if ( hippo_option( 'pinterest-link' ) ) : ?>
							<li><a href="<?php echo esc_url( hippo_option( 'pinterest-link' ) ); ?>"
							       target="_blank"><i class="fa fa-pinterest"></i></a></li>
						<?php endif; ?>

						<?php if ( hippo_option( 'flickr-link' ) ) : ?>
							<li><a href="<?php echo esc_url( hippo_option( 'flickr-link' ) ); ?>"
							       target="_blank"><i class="fa fa-flickr"></i></a></li>
						<?php endif; ?>

						<?php if ( hippo_option( 'linkedin-link' ) ) : ?>
							<li><a href="<?php echo esc_url( hippo_option( 'linkedin-link' ) ); ?>"
							       target="_blank"><i class="fa fa-linkedin"></i></a></li>
						<?php endif; ?>

						<?php if ( hippo_option( 'vimeo-link' ) ) : ?>
							<li><a href="<?php echo esc_url( hippo_option( 'vimeo-link' ) ); ?>"
							       target="_blank"><i class="fa fa-vimeo-square"></i></a></li>
						<?php endif; ?>

						<?php if ( hippo_option( 'instagram-link' ) ) : ?>
							<li><a href="<?php echo esc_url( hippo_option( 'instagram-link' ) ); ?>"
							       target="_blank"><i class="fa fa-instagram"></i></a></li>
						<?php endif; ?>

						<?php if ( hippo_option( 'dribbble-link' ) ) : ?>
							<li><a href="<?php echo esc_url( hippo_option( 'dribbble-link' ) ); ?>"
							       target="_blank"><i class="fa fa-dribbble"></i></a></li>
						<?php endif; ?>

						<?php if ( hippo_option( 'tumblr-link' ) ) : ?>
							<li><a href="<?php echo esc_url( hippo_option( 'tumblr-link' ) ); ?>"
							       target="_blank"><i class="fa fa-tumblr"></i></a></li>
						<?php endif; ?>
					</ul>
				</div> <!-- /.social-section -->
			<?php endif; ?>

			<div class="copyright-info">
				<?php if ( hippo_option( 'footer-text' ) ) : ?>
					<?php echo wp_kses_post( hippo_option( 'footer-text' ) ); ?>
				<?php else : ?>
					<p>
						<?php printf(
							wp_kses_post( __( 'Copyright &copy; Avicultura. All Rights Reserved.', 'sink' ) ),
							date( 'Y' ),
							"<a href=".$_SERVER['HOST'].">ThemeHippo.com</a>"

						); ?>
					</p>
				<?php endif; ?>
			</div>
		</div>
		<!-- /.col-# -->
	</div>
	<!-- .row -->
</footer> <!-- copyright-section end -->
</div> <!-- .row -->
</div> <!-- .container -->
<?php if ( offCanvas_On_InnerPusher( hippo_option( 'offcanvas-menu-effect', FALSE, 'reveal' ) ) ) : ?>
	<nav class="menu-wrapper" id="offcanvasmenu">

		<?php if ( hippo_option( 'offcanvas-menu-title', FALSE, esc_html__( 'Sidebar Menu', 'sink' ) ) ) : ?>
			<h2 class="icon icon-stack"><?php echo esc_html( hippo_option( 'offcanvas-menu-title', FALSE, esc_html__( 'Sidebar Menu', 'sink' ) ) ) ?></h2>
		<?php endif; ?>

		<button type="button" class="close close-sidebar">&times;</button>
		<div>
			<div>
				<?php dynamic_sidebar( 'offcanvas-menu' ) ?>
			</div>
		</div>
	</nav>
<?php endif; ?>

<?php do_action( 'hippo_theme_end_inner_wrapper' ); ?>
</div> <!-- .pusher -->
<?php do_action( 'hippo_theme_after_inner_wrapper' ); ?>

<?php if ( ! offCanvas_On_InnerPusher( hippo_option( 'offcanvas-menu-effect', FALSE, 'reveal' ) ) ) : ?>
	<nav class="menu-wrapper" id="offcanvasmenu">

		<?php if ( hippo_option( 'offcanvas-menu-title', FALSE, esc_html__( 'Sidebar Menu', 'sink' ) ) ) : ?>
			<h2 class="icon icon-stack"><?php echo esc_html( hippo_option( 'offcanvas-menu-title', FALSE, esc_html__( 'Sidebar Menu', 'sink' ) ) ) ?></h2>
		<?php endif; ?>

		<button type="button" class="close close-sidebar">&times;</button>
		<div>
			<div>
				<?php dynamic_sidebar( 'offcanvas-menu' ) ?>
			</div>
		</div>
	</nav>
<?php endif; ?>
</div> <!-- #wrapper -->
<?php wp_footer(); ?>
</body>
</html>