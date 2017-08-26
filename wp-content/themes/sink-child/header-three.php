<?php
defined('ABSPATH') or die('Keep Silent');

?>
    <header class="header header-style-three clearfix">
        <div class="logo-bar">
            <div class="row">
                <div class="col-xs-4">
                    <div class="header-left">
                        <?php if (hippo_option('currency-switcher', FALSE, FALSE)) : ?>
                            <div class="currency-switcher">
                                <?php

                                if (function_exists('Hippo_Simple_Currency_Switcher')):
                                    global $hippo_simple_currency_switcher;

                                    $hippo_simple_currency_switcher->display_list();
                                endif;
                                ?>
                            </div>
                        <?php endif; ?>
                        <?php if (hippo_option('wpml-flag-show', FALSE, FALSE)) : ?>
                            <div class="lang-support">
                                <?php
                                hippo_wpml_language_selector();
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-xs-4">
                    <div class="site-logo text-center">
                        <?php hippo_custom_logo() ?>
                        	<div class="breadcrumb-wrap">
								<?php hippo_breadcrumbs(); ?>
							</div>
                    </div>
                </div>
                <!-- /.col -->

                <div class="col-xs-4">
                    <div class="header-right">

                        <div class="user-login">
                            <?php if (!is_user_logged_in()) : ?>
                                <a data-toggle="modal" href="#login"><i class="zmdi zmdi-account"></i></a>
                            <?php else : ?>
                                <a data-toggle="tooltip" href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>"
                                   title="<?php 
                                    echo __('Cerrar sesión', 'es_ES');
                                //   echo get_template_directory();
                                    // if(esc_html_e('Sign out', 'es')){
                                    //     echo 'hola';
                                    // }else{
                                    //     echo 'adios';
                                    // }
                                   
                                   ?>"><i class="zmdi zmdi-square-right zmdi-hc-flip-horizontal"></i></a>
                            <?php 
                                endif; 
                            ?>
                        </div>

                        <?php if (function_exists('YITH_WCWL')) :
                            $wishlist_url = YITH_WCWL()->get_wishlist_url();
                            ?>
                            <div class="wishlist-notify">
                                <a data-toggle="tooltip" title="Deseos" href="<?php echo esc_url($wishlist_url); ?>">
                                    <i class="zmdi zmdi-favorite"></i>
									<span
                                        id="hippo-wishlist-total"><?php echo number_format_i18n(yith_wcwl_count_products()); ?></span>
                                </a></div>
                        <?php endif; ?>


                        <?php if (hippo_option('cart-icon', FALSE, TRUE) and class_exists('WooCommerce')) : ?>
                            <div class="cart-notify">
                                <a class="" data-toggle="modal" href="#mini-cart">
                                    <?php global $woocommerce;
                                    if ($woocommerce->cart->cart_contents_count == 0) : ?>
                                        <i class="zmdi zmdi-shopping-cart"></i>
                                    <?php else : ?>
                                        <i class="zmdi zmdi-shopping-cart"></i>
                                    <?php endif; ?>
                                </a>
		            <span id="mini-cart-total" class="cart-contents">
		              <?php echo number_format_i18n($woocommerce->cart->cart_contents_count); ?>
		            </span>
                            </div> <!-- .cart-notify -->
                        <?php endif; ?>

                    </div>
                </div>

            </div>
            <!-- /.row -->
        </div>
        <!-- /.logo-bar -->

        <div class="header-three-sticky">

            <nav class="navbar navbar-horizontal">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mainnav">
                            <div class="navbar-header">
                                <div class="mobile-menu-trigger visible-xs visible-sm">
                                    <a class="navbar-toggle" href="#mobile_menu"><i class="zmdi zmdi-menu"></i></a>
                                </div>
                            </div>
                            <!-- /.navbar-header -->

                            <!-- Collect the nav links, forms, and other content for toggling -->
                            <div class="">
                                <div class="collapse navbar-collapse">
                                    <?php wp_nav_menu(apply_filters('hippo_wp_nav_menu_header_three', array(
                                            'container' => FALSE,
                                            'theme_location' => 'primary',
                                            'items_wrap' => '<ul id="%1$s" class="%2$s nav navbar-nav">%3$s</ul>',
                                            'walker' => new Hippo_Menu_Walker(),
                                            'fallback_cb' => 'Hippo_Menu_Walker::fallback'
                                        ))
                                    );
                                    ?>
                                </div>
                            </div>
                            <!-- /navbar-collapse -->

                            <div class="search-btn">
                                <div class="control" tabindex="1">
                                    <div class='search-btn'></div>
                                    <i class="zmdi zmdi-search zmdi-hc-fw"></i>
                                </div>
                            </div>

                        </div>
                        <!-- /.mainnav -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </nav>
            <!-- /.navbar -->
        </div>
        <!-- /.Sticky Menu -->
    </header>

<?php get_template_part('template-parts/modal', 'search-form');