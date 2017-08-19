<?php
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    //----------------------------------------------------------------------
    // Select2 enqueue
    //----------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_enqueue_select2' ) ) {
        function hippo_enqueue_select2() {
            wp_enqueue_script( 'select2' );
            wp_enqueue_style( 'select2' );
        }
    }
    
    //----------------------------------------------------------------------
    // Owl Carousel
    //----------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_enqueue_owl_carousel' ) ) {
        function hippo_enqueue_owl_carousel() {
            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
            
            wp_register_script( 'owl.carousel', get_template_directory_uri() . "/js/owl.carousel{$suffix}.js", array( 'jquery' ), '2.2.1', TRUE );
            
            wp_register_style( 'owl.carousel', get_template_directory_uri() . "/css/owl.carousel{$suffix}.css", array(), '2.2.1' );
            
            wp_register_style( 'owl.theme.default', get_template_directory_uri() . "/css/owl.theme.default{$suffix}.css", array( 'owl.carousel' ), '2.2.1' );
            
            
            wp_enqueue_script( 'owl.carousel' );
            wp_enqueue_style( 'owl.theme.default' );
        }
    }
    
    //----------------------------------------------------------------------
    // Magnific Popup
    //----------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_enqueue_magnific_popup' ) ):
        function hippo_enqueue_magnific_popup() {
            
            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
            
            wp_register_script( 'magnific-popup', get_template_directory_uri() . "/js/jquery.magnific-popup{$suffix}.js", array( 'jquery' ), '1.1.0', TRUE );
            wp_register_style( 'magnific-popup', get_template_directory_uri() . "/css/magnific-popup.css", array(), '1.1.0' );
            
            wp_enqueue_script( 'magnific-popup' );
            wp_enqueue_style( 'magnific-popup' );
        }
    endif;
    
    //----------------------------------------------------------------------
    // Twitter Bootstrap
    //----------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_enqueue_twitter_bootstrap' ) ):
        function hippo_enqueue_twitter_bootstrap() {
            
            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
            
            wp_register_script( 'bootstrap', get_template_directory_uri() . "/js/bootstrap{$suffix}.js", array( 'jquery' ), '3.3.5', TRUE );
            wp_register_style( 'bootstrap', get_template_directory_uri() . "/css/bootstrap{$suffix}.css", array(), '3.3.5' );
            
            wp_enqueue_script( 'bootstrap' );
            wp_enqueue_style( 'bootstrap' );
        }
    endif;
    
    
    //----------------------------------------------------------------------
    // Flickr Feed
    //----------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_enqueue_flickr_feed' ) ):
        function hippo_enqueue_flickr_feed() {
            
            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
            
            wp_register_script( 'jflickrfeed', get_template_directory_uri() . "/js/jflickrfeed{$suffix}.js", array( 'jquery' ), '1.0.0', TRUE );
            
            wp_enqueue_script( 'jflickrfeed' );
        }
    endif;
    
    
    //----------------------------------------------------------------------
    // Get list of available home page templates
    //----------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_home_page_templates' ) ):
        function hippo_home_page_templates() {
            
            // page template file name which use for only home page,
            // because we did not show breadcrumb and others on home page;
            return apply_filters( 'hippo_home_page_templates', array(
                'template-home.php',
                'template-home-fullwidth.php',
                'template-noshadow-noheader-bg.php',
            ) );
        }
    endif;
    
    //----------------------------------------------------------------------
    // Page templates body classes
    //----------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_page_template_body_classes' ) ):
        
        function hippo_page_template_body_classes() {
            
            // Index for page template file name and value css class name(s), name separate by space;
            return apply_filters( 'hippo_page_template_body_classes', array(
                'template-home.php'                 => 'home-template-style',
                'template-home-fullwidth.php'       => 'home-fullwidth-template-style',
                'template-noshadow-noheader-bg.php' => 'home-noshadow-noheader-background-style',
                'template-vc-woocommerce.php'       => 'woocommerce woocommerce-page',
            ) );
        }
    
    endif;
    
    //----------------------------------------------------------------------
    // Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
    //----------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_page_menu_args' ) ) :
        
        function hippo_page_menu_args( $args ) {
            
            $args[ 'show_home' ] = TRUE;
            
            return apply_filters( 'hippo_page_menu_args', $args );
        }
        
        add_filter( 'wp_page_menu_args', 'hippo_page_menu_args', 9999 );
    endif;
    
    //----------------------------------------------------------------------
    // Adds custom classes to the array of body classes.
    //----------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_body_classes' ) ) :
        
        function hippo_body_classes( $classes ) {
            // Adds a class of group-blog to blogs with more than 1 published author.
            if ( is_multi_author() ) {
                $classes[] = 'group-blog';
            }
            
            $classes[] = hippo_option_get_preset();
            if ( hippo_option( 'shadow-less-layout', FALSE, FALSE ) ) {
                $classes[] = 'shadow-less-noheader-background-style';
            }
            
            
            $current_page_template = basename( get_page_template_slug() );
            
            foreach ( hippo_page_template_body_classes() as $filename => $class_name ) {
                if ( trim( $filename ) == $current_page_template ) {
                    $classes[] = $class_name;
                }
            }
            
            //$classes[] = hippo_option( 'layout-type', FALSE, 'full-width' );
            
            return apply_filters( 'hippo_body_classes', array_unique( $classes ) );
        }
        
        add_filter( 'body_class', 'hippo_body_classes', 9999 );
    endif;
    
    //----------------------------------------------------------------------
    // Adds custom classes to the array of post classes.
    //----------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_post_classes' ) ) :
        
        function hippo_post_classes( $classes ) {
            
            if ( ! is_home() && ! is_paged() && is_sticky() ) {
                $classes[] = 'sticky';
            }
            
            if ( hippo_has_post_thumbnail() ) {
                $classes[] = 'has-post-thumbnail';
            }
            
            return apply_filters( 'hippo_post_classes', $classes );
        }
        
        add_filter( 'post_class', 'hippo_post_classes', 9999 );
    endif;
    
    //----------------------------------------------------------------------
    // Sets the authordata global when viewing an author archive.
    // This provides backwards compatibility with
    // http://core.trac.wordpress.org/changeset/25574
    // It removes the need to call the_post() and rewind_posts() in an author
    // template to print information about the author.
    //----------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_setup_author' ) ) :
        function hippo_setup_author() {
            global $wp_query;
            
            if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
                $GLOBALS[ 'authordata' ] = get_userdata( $wp_query->post->post_author );
            }
        }
        
        add_action( 'wp', 'hippo_setup_author', 9999 );
    endif;
    
    //-------------------------------------------------------------------------------
    // Add Author Contact
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_add_author_contact' ) ) :
        function hippo_add_author_contact( $contactmethods ) {
            
            $contactmethods[ 'google_profile' ]   = esc_html__( 'Google Plus Profile URL', 'sink' );
            $contactmethods[ 'twitter_profile' ]  = esc_html__( 'Twitter Profile URL', 'sink' );
            $contactmethods[ 'facebook_profile' ] = esc_html__( 'Facebook Profile URL', 'sink' );
            $contactmethods[ 'linkedin_profile' ] = esc_html__( 'Linkedin Profile URL', 'sink' );
            $contactmethods[ 'github_profile' ]   = esc_html__( 'Github Profile URL', 'sink' );
            
            return apply_filters( 'hippo_add_author_contact', $contactmethods );
        }
        
        add_filter( 'user_contactmethods', 'hippo_add_author_contact', 9999 );
    endif;
    
    //----------------------------------------------------------------------
    // Display page break button in editor
    //----------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_wp_page_paging' ) ) :
        
        function hippo_wp_page_paging( $mce_buttons ) {
            if ( get_post_type() == 'post' or get_post_type() == 'page' ) {
                $pos = array_search( 'wp_more', $mce_buttons, TRUE );
                if ( $pos !== FALSE ) {
                    $buttons     = array_slice( $mce_buttons, 0, $pos + 1 );
                    $buttons[]   = 'wp_page';
                    $mce_buttons = array_merge( $buttons, array_slice( $mce_buttons, $pos + 1 ) );
                }
            }
            
            return apply_filters( 'hippo_mce_buttons', $mce_buttons );
        }
        
        add_filter( 'mce_buttons', 'hippo_wp_page_paging', 9999 );
    endif;
    
    //----------------------------------------------------------------------
    // Set post view on single page display
    //----------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_call_post_views_set_fn' ) ) :
        
        function hippo_call_post_views_set_fn( $contents ) {
            if ( function_exists( 'hippo_set_post_views' ) and is_single() ) {
                hippo_set_post_views();
            }
            
            return $contents;
        }
        
        add_filter( 'the_content', 'hippo_call_post_views_set_fn', 9999 );
    
    endif;
    
    //----------------------------------------------------------------------
    // Post excerpt length, Post excerpt more
    //----------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_custom_excerpt_length' ) ) :
        
        function hippo_custom_excerpt_length( $wp_default ) {
            return apply_filters( 'hippo_custom_excerpt_length', 10, $wp_default );
        }
        
        add_filter( 'excerpt_length', 'hippo_custom_excerpt_length', 9999 );
    endif;
    
    //----------------------------------------------------------------------
    // Post excerpt more
    //----------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_custom_excerpt_more' ) ) :
        function hippo_custom_excerpt_more( $more ) {
            return ' ';
        }
        
        add_filter( 'excerpt_more', 'hippo_custom_excerpt_more', 9999 );
    endif;
    
    //----------------------------------------------------------------------
    // Login register modal
    //----------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_login_modal' ) ) :
        
        function hippo_login_modal() {
            
            get_template_part( 'template-parts/modal', 'login' );
        }
        
        add_action( 'wp_footer', 'hippo_login_modal', 9999 );
    endif;
    
    //----------------------------------------------------------------------
    // Mini cart modal
    //----------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_mini_cart_modal' ) ) :
        function hippo_mini_cart_modal() {
            get_template_part( 'template-parts/modal', 'minicart' );
        }
        
        add_action( 'wp_footer', 'hippo_mini_cart_modal', 9999 );
    endif;
    
    //----------------------------------------------------------------------
    // Favicon Fallback
    //----------------------------------------------------------------------
    
    if ( ! function_exists( 'has_site_icon' ) or ! has_site_icon() ):
        
        function hippo_wp_site_icon_fallback() {
            
            echo implode( "\n", array(
                sprintf( '<link rel="icon" href="%s" sizes="32x32" />', esc_url( hippo_locate_template_uri( 'img/favicon.png' ) ) ),
                sprintf( '<link rel="icon" href="%s" sizes="192x192" />', esc_url( hippo_locate_template_uri( 'img/favicon.png' ) ) )
            ) );
        }
        
        add_action( 'wp_head', 'hippo_wp_site_icon_fallback' );
    
    endif;
    
    //----------------------------------------------------------------------
    // Switcher Request
    //----------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_set_on_fly_preset' ) ):
        function hippo_set_on_fly_preset() {
            
            $session_name      = '_hippo_preset';
            $method_name       = 'hippo-preset';
            $reset_method_name = 'reset-hippo-preset';
            
            $requested  = ( isset( $_GET[ $method_name ] ) && ! empty( $_GET[ $method_name ] ) ) ? esc_html( trim( $_GET[ $method_name ] ) ) : FALSE;
            $valid_list = apply_filters( 'hippo_available_preset', array(
                'preset1',
                'preset2',
                'preset3',
                'preset4',
                'preset5'
            ) );
            
            
            // Set preset
            if ( $requested && in_array( $requested, $valid_list ) ) {
                hippo_set_session( $session_name, $requested );
            }
            
            // reset preset
            if ( isset( $_GET[ $reset_method_name ] ) ) {
                hippo_delete_session( $session_name );
            }
        }
        
        add_action( 'template_redirect', 'hippo_set_on_fly_preset', 30 );
    
    endif;
    
    if ( ! function_exists( 'hippo_set_on_fly_header_style' ) ):
        function hippo_set_on_fly_header_style() {
            
            $session_name      = '_hippo_header_style';
            $method_name       = 'hippo-header-style';
            $reset_method_name = 'reset-hippo-header-style';
            
            $requested  = ( isset( $_GET[ $method_name ] ) && ! empty( $_GET[ $method_name ] ) ) ? esc_html( trim( $_GET[ $method_name ] ) ) : FALSE;
            $valid_list = apply_filters( 'hippo_available_header_style', array(
                'header-style-one',
                'header-style-two',
                'header-style-three',
                'header-style-four'
            ) );
            
            
            // Set preset
            if ( $requested && in_array( $requested, $valid_list ) ) {
                hippo_set_session( $session_name, $requested );
            }
            
            // reset preset
            if ( isset( $_GET[ $reset_method_name ] ) ) {
                hippo_delete_session( $session_name );
            }
        }
        
        add_action( 'template_redirect', 'hippo_set_on_fly_header_style', 30 );
    
    endif;
    
    if ( ! function_exists( 'hippo_set_on_fly_background_style' ) ):
        function hippo_set_on_fly_background_style() {
            
            $session_name      = '_hippo_header_bg_style';
            $method_name       = 'hippo-header-background-style';
            $reset_method_name = 'reset-hippo-header-background-style';
            
            $requested  = ( isset( $_GET[ $method_name ] ) && ! empty( $_GET[ $method_name ] ) ) ? esc_html( trim( $_GET[ $method_name ] ) ) : FALSE;
            $valid_list = apply_filters( 'hippo_available_header_background_style', array(
                'bg-style-one',
                'bg-style-two',
                'bg-style-three',
                'bg-style-four',
                'bg-style-five',
                'bg-style-custom',
            ) );
            
            
            // Set preset
            if ( $requested && in_array( $requested, $valid_list ) ) {
                hippo_set_session( $session_name, $requested );
            }
            
            // reset preset
            if ( isset( $_GET[ $reset_method_name ] ) ) {
                hippo_delete_session( $session_name );
            }
        }
        
        add_action( 'template_redirect', 'hippo_set_on_fly_background_style', 30 );
    
    endif;
