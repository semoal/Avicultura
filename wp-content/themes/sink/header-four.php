<?php
	defined( 'ABSPATH' ) or die( 'Keep Silent' );
?>
	<header class="header header-style-four clearfix">
		<div class="row">
			<div class="col-xs-5">
				<div class="header-left">
					<?php if ( hippo_option( 'currency-switcher', FALSE, FALSE ) ) : ?>
						<div class="currency-switcher">
							<?php
								if ( function_exists( 'Hippo_Simple_Currency_Switcher' ) ):
									global $hippo_simple_currency_switcher;

									$hippo_simple_currency_switcher->display_list();
								endif;
							?>
						</div>
					<?php endif; ?>

					<?php if ( hippo_option( 'wpml-flag-show', FALSE, FALSE ) ) : ?>
						<div class="lang-support">
							<?php
								hippo_wpml_language_selector();
							?>
						</div>
					<?php endif; ?>
				</div>

				<div class="clearfix"></div>

				<nav class="navbar navbar-horizontal navbar-horizontal-left">
					<div class="mainnav">
						<div class="collapse navbar-collapse">
							<?php wp_nav_menu( apply_filters( 'hippo_wp_nav_menu_header_four_left', array(
								                   'container'      => FALSE,
								                   'theme_location' => 'header-left-menu',
								                   'items_wrap'     => '<ul id="%1$s" class="%2$s nav navbar-nav navbar-right navbar-horizontal">%3$s</ul>',
								                   'walker'         => new Hippo_Menu_Walker(),
								                   'fallback_cb'    => 'Hippo_Menu_Walker::fallback'
							                   ) )
							);
							?>
						</div>

					</div>
					<!-- .mainnav -->
				</nav>
			</div>

			<!-- Site Logo -->
			<div class="col-xs-2">
				<div class="site-logo text-center">
					<?php hippo_custom_logo() ?>
				</div>
				<!-- .site-logo -->
			</div>

			<div class="col-xs-5">
				<div class="header-right">
					<div class="user-login">
						<?php if ( ! is_user_logged_in() ) : ?>
							<a data-toggle="modal" href="#login"><i class="zmdi zmdi-account"></i></a>
						<?php else : ?>
							<a data-toggle="tooltip"
							   href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>"
							   title="<?php esc_html_e( 'Sign Out', 'sink' ) ?>"><i
									class="zmdi zmdi-account-box-o"></i></a>
						<?php endif; ?>
					</div>

					<?php if ( function_exists( 'YITH_WCWL' ) ) :
						$wishlist_url = YITH_WCWL()->get_wishlist_url();
						?>
						<div class="wishlist-notify">
							<a href="<?php echo esc_url( $wishlist_url ); ?>">
								<i class="zmdi zmdi-favorite"></i>
									<span
										id="hippo-wishlist-total"><?php echo number_format_i18n( yith_wcwl_count_products() ); ?></span>
							</a></div>
					<?php endif; ?>

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

					<div class="search-btn">
						<div class="control" tabindex="1">
							<div class='search-btn'></div>
							<i class="zmdi zmdi-search zmdi-hc-fw"></i>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
				<nav class="navbar navbar-horizontal navbar-horizontal-right">
					<div class="mainnav">
						<!-- Collect the nav links, forms, and other content for toggling -->
						<div class="collapse navbar-collapse">
							<?php wp_nav_menu( apply_filters( 'hippo_wp_nav_menu_header_four_right', array(
								                   'container'      => FALSE,
								                   'theme_location' => 'header-right-menu',
								                   'items_wrap'     => '<ul id="%1$s" class="%2$s nav navbar-nav">%3$s</ul>',
								                   'walker'         => new Hippo_Menu_Walker(),
								                   'fallback_cb'    => 'Hippo_Menu_Walker::fallback'
							                   ) )
							);
							?>
						</div>
						<!-- /navbar-collapse -->
					</div>
				</nav>
			</div>
		</div>

		<!-- Mobile Menu Trigger -->
		<div class="navbar-header visible-xs visible-sm">
			<div class="mobile-menu-trigger">
				<a class="navbar-toggle" href="#mobile_menu"><i class="zmdi zmdi-menu"></i></a>
			</div>
		</div>
	</header>

<?php get_template_part( 'template-parts/modal', 'search-form' );