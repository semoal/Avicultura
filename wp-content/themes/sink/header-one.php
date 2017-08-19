<?php
	defined( 'ABSPATH' ) or die( 'Keep Silent' );

?>
	<header class="header header-style-one clearfix">
		<div class="site-logo text-center">

			<?php hippo_custom_logo() ?>

			<?php if ( hippo_option( 'show-tagline', FALSE, TRUE ) ) : ?>
				<div class="tagline">
					<span><?php echo esc_html( get_bloginfo( 'description' ) ); ?></span>
				</div>
			<?php endif; ?>
		</div>

		<div class="header-left">

			<div class="mobile-menu-trigger visible-sm visible-xs">
				<a class="navbar-toggle" href="#mobile_menu"><i></i></a>
			</div>

			<div class="navbar-vertical-wrapper">
				<a class="menu-trigger hidden-sm hidden-xs"><i></i></a>
				<nav class="navbar navbar-vertical">
					<div class="mainnav">
						<!-- Collect the nav links, forms, and other content for toggling -->
						<div class="collapse navbar-collapse">

							<?php wp_nav_menu( apply_filters( 'hippo_wp_nav_menu_header_one', array(
								'container'      => FALSE,
								'theme_location' => 'primary',
								'items_wrap'     => '<ul id="%1$s" class="%2$s nav navbar-nav">%3$s</ul>',
								'walker'         => new Hippo_Menu_Walker(),
								'fallback_cb'    => 'Hippo_Menu_Walker::fallback'
							) ) );
							?>
						</div>
						<!-- /navbar-collapse -->
					</div>
					<!-- /.container -->
				</nav>
			</div>

			<div class="user-login">
				<?php if ( ! is_user_logged_in() ) : ?>
					<a data-toggle="modal" href="#login"><?php esc_html_e( 'Sign in ', 'sink' ) ?><i
							class="zmdi zmdi-sign-in zmdi-hc-fw"></i></a>
				<?php else : ?>
					<a class="sing-out" data-toggle="modal"
					   href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>"><?php esc_html_e( 'Sign Out ', 'sink' ) ?>
						<i class="zmdi zmdi-sign-in zmdi-hc-fw"></i></a>
				<?php endif; ?>
			</div>
		</div>

		<div class="header-right">
			<div class="search-btn">
				<div class="control" tabindex="1">
					<div class='search-btn'></div>
					<i class="zmdi zmdi-search zmdi-hc-fw"></i>
				</div>
			</div>
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
		</div>
	</header>
<?php get_template_part( 'template-parts/modal', 'search-form' );