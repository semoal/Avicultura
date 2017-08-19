<?php
	defined( 'ABSPATH' ) or die( 'Keep Silent' );

?>
	<header class="header header-style-two clearfix">
		<div class="row">
			<div class="col-md-10 col-xs-7">
				<nav class="navbar navbar-horizontal">
					<div class="mainnav">
						<div class="navbar-header">
							<div class="mobile-menu-trigger visible-xs-inline-block visible-sm-inline-block">
								<a class="navbar-toggle" href="#mobile_menu"><i class="zmdi zmdi-menu"></i></a>
							</div>

							<div class="site-logo">

								<?php if ( ! hippo_option( 'header-two-logo', 'url', FALSE ) ): ?>
									<?php hippo_custom_logo() ?>
								<?php else: ?>
									<a href="<?php echo esc_url( home_url( '/' ) ) ?>"
									   title="<?php echo esc_attr( get_bloginfo( 'name' ) ) ?>">
										<img src="<?php echo esc_url( hippo_option( 'header-two-logo', 'url', get_template_directory_uri() . '/img/logo-small.png' ) ) ?>"
										     data-at2x="<?php echo esc_url( hippo_option( 'header-two-retina-logo', 'url', get_template_directory_uri() . '/img/logo-small@2x.png' ) ) ?>"
										     alt="<?php echo esc_attr( get_bloginfo( 'name' ) ) ?>"/>
									</a>
								<?php endif; ?>
							</div>
						</div>
						<!-- /.navbar - header -->

						<!-- Collect the nav links, forms, and other content for toggling -->
						<div class="collapse navbar-collapse">
							<?php wp_nav_menu( apply_filters( 'hippo_wp_nav_menu_header_two', array(
								                   'container'      => FALSE,
								                   'theme_location' => 'primary',
								                   'items_wrap'     => '<ul id="%1$s" class="%2$s nav navbar-nav">%3$s</ul>',
								                   'walker'         => new Hippo_Menu_Walker(),
								                   'fallback_cb'    => 'Hippo_Menu_Walker::fallback'
							                   ) )
							);
							?>
						</div>
						<!-- /navbar-collapse -->
					</div>
					<!-- /.mainnav -->
				</nav>
			</div>

			<div class="col-md-2 col-xs-5">
				<div class="header-right">
					<?php if ( hippo_option( 'cart-icon', FALSE, TRUE ) and class_exists( 'WooCommerce' ) ) : ?>
						<div class="cart-notify">
							<a class="" data-toggle="modal" href="#mini-cart">
								<?php global $woocommerce;
									if ( $woocommerce->cart->cart_contents_count == 0 ) : ?>
										<i class="zmdi zmdi-shopping-cart"></i>
									<?php else : ?>
										<i class="zmdi zmdi-shopping-cart"></i>
									<?php endif; ?>
							</a>
			            <span id="mini-cart-total" class="cart-contents">
			              <?php echo number_format_i18n( $woocommerce->cart->cart_contents_count ); ?>
			            </span>
						</div> <!-- .cart-notify -->
					<?php endif; ?>

					<div class="user-login">
						<?php if ( ! is_user_logged_in() ) : ?>
							<a data-toggle="modal" href="#login"><i class="zmdi zmdi-sign-in"></i></a>
						<?php else : ?>
							<a class="sing-out" data-toggle="tooltip"
							   href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>"
							   title="<?php esc_html_e( 'Sign Out', 'sink' ) ?>"><i class="zmdi zmdi-sign-in"></i></a>
						<?php endif; ?>
					</div>

					<div class="search-btn">
						<div class="control" tabindex="1">
							<div class="search-btn" data-toggle="tooltip"
							     title="<?php esc_html_e( 'Search', 'sink' ) ?>"></div>
							<i class="zmdi zmdi-search zmdi-hc-fw"></i>
						</div>
					</div>
				</div>
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</header>

<?php get_template_part( 'template-parts/modal', 'search-form' );