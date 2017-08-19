<?php
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    //----------------------------------------------------------------------
    // Defining Constance
    //----------------------------------------------------------------------
    
    if ( ! defined( 'HIPPO_NAME' ) ) {
        define( 'HIPPO_NAME', wp_get_theme()->get( 'Name' ) );
    }
    
    
    //----------------------------------------------------------------------
    // Helper, Import Setting, NavWalker, Hippo addons
    //----------------------------------------------------------------------
    
    require get_template_directory() . '/inc/helper.php';
    
    require get_template_directory() . '/inc/import-settings.php';
    
    require get_template_directory() . '/inc/hippo-navwalker.php';
    
    require get_template_directory() . '/inc/less-init.php';
    
    if ( function_exists( 'Vc_Manager' ) ) :
        require get_template_directory() . '/visual-composer/visual-composer.php';
    endif;
    
    if ( ! function_exists( 'hippo_thumbnail_overlay' ) ) :
        
        function hippo_thumbnail_overlay( $thumbs_data ) {
            if ( $thumbs_data[ 'post_format' ] == 'standard' ) :
                if ( ! is_singular( 'post' ) ) :
                    echo '<div class="css-blog-overlay">';
                    $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $thumbs_data[ 'post_id' ] ), 'large' );
                    
                    $is_linkable = hippo_option( 'thumbnail-image-linkable', FALSE, FALSE );
                    $class       = $is_linkable ? '' : 'element-lightbox';
                    
                    echo '<a href="' . esc_url( $is_linkable ? get_permalink() : $large_image_url[ 0 ] ) . '"  class="' . esc_attr( $class ) . '"></a>';
                    echo '</div>';
                endif;
            endif;
        }
        
        add_action( 'after_hippo_post_thumbnail', 'hippo_thumbnail_overlay' );
    endif;
    
    
    if ( ! function_exists( 'hippo_theme_setup' ) ) :
        
        //------------------------------------------------------------------------------
        // Sets up theme defaults and registers support for various WordPress features.
        // Note that this function is hooked into the after_setup_theme hook, which
        // runs before the init hook. The init hook is too late for some features, such
        // as indicating support for post thumbnails.
        //-------------------------------------------------------------------------------
        
        function hippo_theme_setup() {
            
            //-------------------------------------------------------------------------------
            // Make theme available for translation.
            //-------------------------------------------------------------------------------
            
            load_theme_textdomain( 'sink', get_template_directory() . '/languages' );
            
            
            // Add default posts and comments RSS feed links to head.
            add_theme_support( 'automatic-feed-links' );
            
            // Supporting title tag
            add_theme_support( 'title-tag' );
            
            
            /*
            * Enable support for custom logo.
            *
            *  @since Sink 1.8
            */
            add_theme_support( 'custom-logo', apply_filters( 'hippo-custom-logo-attr', array(
                'height'      => 105,
                'width'       => 60,
                'flex-height' => TRUE,
                'flex-width'  => TRUE,
            ) ) );
            
            //-------------------------------------------------------------------------------
            // Enable support for Post Thumbnails on posts and pages.
            // @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
            //-------------------------------------------------------------------------------
            add_theme_support( 'post-thumbnails' );
            
            //----------------------------------------------------------------------
            // Setting Default Content Width
            //----------------------------------------------------------------------
            
            if ( ! isset( $content_width ) ) :
                $GLOBALS[ 'content_width' ] = apply_filters( 'hippo_content_width', 1170 );
            endif;
            
            
            // default post thumbnail size
            set_post_thumbnail_size( 1170, 500, array( 'center', 'center' ) );
            
            //add_image_size( 'hippo-blog-thumbnail', 1170, 600, TRUE );
            //add_image_size( 'hippo-single-blog-thumbnail', 1170, 600, TRUE );
            
            // Default Product Thumbnail
            /*add_image_size( 'hippo-product-thumbnail', 500, 745, array( 'center', 'center' ) );
            add_image_size( 'hippo-single-product-thumbnail', 500, 550, array( 'center', 'center' ) );
            add_image_size( 'hippo-product-category-thumbnail', 500, 745, array( 'center', 'center' ) );
            add_image_size( 'hippo-single-product-thumbnail-mini', 150, 150, array( 'center', 'center' ) );
            */
            add_image_size( 'hippo-mini-cart-thumb', 120, 80, array( 'center', 'center' ) );
            add_image_size( 'hippo-home-carousel', 1170, 500, TRUE );
            
            
            // Register wp_nav_menu()
            register_nav_menus( apply_filters( 'hippo_register_nav_menus', array(
                'primary'           => esc_html__( 'Primary Menu', 'sink' ),
                'header-left-menu'  => esc_html__( 'Header Split Menu Left (this menu only visible if header style four is active)', 'sink' ),
                'header-right-menu' => esc_html__( 'Header Split Menu Right (this menu only visible if header style four is active)', 'sink' ),
                'footer'            => esc_html__( 'Footer Menu', 'sink' )
            ) ) );
            
            
            //-------------------------------------------------------------------------------
            // Switch default core markup for search form, comment form, and comments
            // to output valid HTML5.
            //-------------------------------------------------------------------------------
            
            add_theme_support( 'html5', apply_filters( 'hippo_html5_theme_support', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) ) );
            
            //-------------------------------------------------------------------------------
            // Enable support for Post Formats.
            // See http://codex.wordpress.org/Post_Formats
            //-------------------------------------------------------------------------------
            
            add_theme_support( 'post-formats', apply_filters( 'hippo_post_formats_theme_support', array( 'aside', 'status', 'image', 'audio', 'video', 'gallery', 'quote', 'link', 'chat' ) ) );
            
            add_editor_style( apply_filters( 'hippo_add_editor_style', array( 'css/editor-style.css', 'css/material-design-iconic-font.min.css', hippo_fonts_url() ) ) );
            
        }
        
        add_action( 'after_setup_theme', 'hippo_theme_setup' );
    
    endif; // hippo_theme_setup
    
    
    //-------------------------------------------------------------------------------
    // Register widget area.
    // @link http://codex.wordpress.org/Function_Reference/register_sidebar
    //-------------------------------------------------------------------------------
    if ( ! function_exists( 'hippo_widgets_init' ) ) :
        
        function hippo_widgets_init() {
            
            do_action( 'hippo_before_register_sidebar' );
            
            register_sidebar( apply_filters( 'hippo_blog_sidebar', array(
                'name'          => esc_html__( 'Blog Sidebar', 'sink' ),
                'id'            => 'hippo-blog-sidebar',
                'description'   => esc_html__( 'Appears in the blog sidebar.', 'sink' ),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ) ) );
            
            register_sidebar( apply_filters( 'hippo_page_sidebar', array(
                'name'          => esc_html__( 'Page Sidebar', 'sink' ),
                'id'            => 'hippo-page-sidebar',
                'description'   => esc_html__( 'Appears in the Page sidebar.', 'sink' ),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ) ) );
            
            register_sidebar( apply_filters( 'hippo_woo_sidebar', array(
                'name'          => esc_html__( 'Shop Header Sidebar', 'sink' ),
                'id'            => 'woosidebar',
                'description'   => esc_html__( 'Appears in the Shop Archive Page. To display filters, tags, etc.', 'sink' ),
                'before_widget' => '<div id="%1$s" class="col-sm-3 widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ) ) );
            
            register_sidebar( apply_filters( 'hippo_footer_sidebar', array(
                'name'          => esc_html__( 'Footer widget', 'sink' ),
                'id'            => 'hippo-footer-widget',
                'description'   => esc_html__( 'Appears in the footer.', 'sink' ),
                'before_widget' => '<div class="col-sm-3 footer-widget widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3>',
                'after_title'   => '</h3>',
            ) ) );
            
            register_sidebar( apply_filters( 'hippo_offcanvas_menu_sidebar', array(
                'name'          => esc_html__( 'Off Canvas Manu', 'sink' ),
                'id'            => 'offcanvas-menu',
                'description'   => esc_html__( 'Off Canvas Menu', 'sink' ),
                'before_widget' => '<div class="offcanvasmenu widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ) ) );
            
            register_sidebar( apply_filters( 'hippo_mega_menu_one_sidebar', array(
                'name'          => esc_html__( 'Mega Menu Widget One', 'sink' ),
                'id'            => 'mega-menu-one',
                'description'   => esc_html__( 'Appears in the mega menu while selected from nav menu item', 'sink' ),
                'before_widget' => '<div class="col-sm-3"><div class="megamenu-widget widget %2$s">',
                'after_widget'  => '</div></div>',
                'before_title'  => '<h2>',
                'after_title'   => '</h2>',
            ) ) );
            
            register_sidebar( apply_filters( 'hippo_mega_menu_two_sidebar', array(
                'name'          => esc_html__( 'Mega Menu Widget Two', 'sink' ),
                'id'            => 'mega-menu-two',
                'description'   => esc_html__( 'Appears in the mega menu while selected from nav menu item', 'sink' ),
                'before_widget' => '<div class="col-sm-3"><div class="megamenu-widget widget %2$s">',
                'after_widget'  => '</div></div>',
                'before_title'  => '<h2>',
                'after_title'   => '</h2>',
            ) ) );
            
            register_sidebar( apply_filters( 'hippo_mega_menu_three_sidebar', array(
                'name'          => esc_html__( 'Mega Menu Widget Three', 'sink' ),
                'id'            => 'mega-menu-three',
                'description'   => esc_html__( 'Appears in the mega menu while selected from nav menu item', 'sink' ),
                'before_widget' => '<div class="col-sm-3"><div class="megamenu-widget widget %2$s">',
                'after_widget'  => '</div></div>',
                'before_title'  => '<h2>',
                'after_title'   => '</h2>',
            ) ) );
            
            register_sidebar( apply_filters( 'hippo_mega_menu_four_sidebar', array(
                'name'          => esc_html__( 'Mega Menu Widget Four', 'sink' ),
                'id'            => 'mega-menu-four',
                'description'   => esc_html__( 'Appears in the mega menu while selected from nav menu item', 'sink' ),
                'before_widget' => '<div class="col-sm-3"><div class="megamenu-widget widget %2$s">',
                'after_widget'  => '</div></div>',
                'before_title'  => '<h2>',
                'after_title'   => '</h2>',
            ) ) );
            
            register_sidebar( apply_filters( 'hippo_mega_menu_five_sidebar', array(
                'name'          => esc_html__( 'Mega Menu Widget Five', 'sink' ),
                'id'            => 'mega-menu-five',
                'description'   => esc_html__( 'Appears in the mega menu while selected from nav menu item', 'sink' ),
                'before_widget' => '<div class="col-sm-3"><div class="megamenu-widget widget %2$s">',
                'after_widget'  => '</div></div>',
                'before_title'  => '<h2>',
                'after_title'   => '</h2>',
            ) ) );
            
            do_action( 'hippo_after_register_sidebar' );
            
        }
        
        add_action( 'widgets_init', 'hippo_widgets_init' );
        
        if ( ! function_exists( 'hippo_widget_grid_class_to_remove' ) ) :
            function hippo_widget_grid_class_to_remove( $classes ) {
                $classes[] = 'col-md-3';
                $classes[] = 'col-sm-3';
                
                return $classes;
            }
            
            add_filter( 'hippo_widget_grid_class_to_remove', 'hippo_widget_grid_class_to_remove' );
        endif;
        
        
        if ( ! function_exists( 'hippo_nav_menu_item_meta_list' ) ) :
            
            function hippo_nav_menu_item_meta_list( $fields ) {
                
                
                $fields[ 'menuheading' ] = array(
                    'type'  => 'checkbox',
                    'label' => esc_html__( 'Menu Heading', 'sink' ),
                    'value' => '1',
                    'depth' => 0
                );
                
                $fields[ 'icon' ] = array(
                    'type'    => 'icon',
                    'label'   => esc_html__( 'Menu Icon', 'sink' ),
                    'options' => ( function_exists( 'hippo_material_icons' ) ? hippo_material_icons() : array() ),
                    'depth'   => 0
                );
                
                $fields[ 'iconcolor' ] = array(
                    'type'       => 'color',
                    'label'      => esc_html__( 'Menu Icon Color', 'sink' ),
                    'default'    => '',
                    'depth'      => 0,
                    'dependency' => array(
                        array( 'icon' => array( 'type' => '!empty' ) )
                    )
                );
                
                $fields[ 'iconbackgroundcolor' ] = array(
                    'type'       => 'color',
                    'label'      => esc_html__( 'Menu Icon Background Color', 'sink' ),
                    'default'    => '',
                    'depth'      => 0,
                    'dependency' => array(
                        array( 'icon' => array( 'type' => '!empty' ) )
                    )
                );
                
                $fields[ 'widgets' ] = array(
                    'type'    => 'select2',
                    'label'   => esc_html__( 'Megamenu Sidebar', 'sink' ),
                    'options' => array(
                        ''                => esc_html__( '-- Select --', 'sink' ),
                        'mega-menu-one'   => esc_html__( 'Mega Menu Widget One', 'sink' ),
                        'mega-menu-two'   => esc_html__( 'Mega Menu Widget Two', 'sink' ),
                        'mega-menu-three' => esc_html__( 'Mega Menu Widget Three', 'sink' ),
                        'mega-menu-four'  => esc_html__( 'Mega Menu Widget Four', 'sink' ),
                        'mega-menu-five'  => esc_html__( 'Mega Menu Widget Five', 'sink' )
                    ),
                    'depth'   => 0
                );
                
                $fields[ 'menucolumnclass' ] = array(
                    'type'       => 'text',
                    'label'      => esc_html__( 'Mega Menu Column Class', 'sink' ),
                    'default'    => 'col-md-10',
                    'depth'      => 0,
                    'dependency' => array(
                        array( 'widgets' => array( 'type' => '!empty' ) )
                    )
                );
                
                $fields[ 'menubackgroundimage' ] = array(
                    'type'       => 'image',
                    'label'      => esc_html__( 'Mega Menu Background Image', 'sink' ),
                    'depth'      => 0,
                    'dependency' => array(
                        array( 'widgets' => array( 'type' => '!empty' ) )
                    )
                );
                
                return apply_filters( 'hippo_nav_menu_item_meta_list', $fields );
            }
            
            add_filter( 'hippo_nav_menu_item_meta', 'hippo_nav_menu_item_meta_list' );
        endif;
    endif;
    
    
    //-------------------------------------------------------------------------------
    // Load Google Font If Redux is not Active.
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_fonts_url' ) ):
        
        function hippo_fonts_url() {
            $font_url = '';
            
            /*
            Translators: If there are characters in your language that are not supported
            by chosen font(s), translate this to 'off'. Do not translate into your own language.
             */
            if ( 'off' !== esc_html_x( 'on', 'Google font: on or off', 'sink' ) ) {
                $font_url = add_query_arg( 'family', rawurlencode( 'Roboto:400,700&subset=latin,latin-ext' ), "//fonts.googleapis.com/css" );
            }
            
            return apply_filters( 'hippo_google_font_url', $font_url );
        }
    endif;
    
    
    //-------------------------------------------------------------------------------
    // Enqueue scripts and styles.
    //-------------------------------------------------------------------------------
    
    if ( ! function_exists( 'hippo_scripts' ) ) :
        
        function hippo_scripts() {
            
            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
            
            do_action( 'hippo_before_enqueue_scripts' );
            
            if ( ! hippo_option( 'body-typography', 'font-family' ) ) {
                wp_enqueue_style( 'google-font', hippo_fonts_url(), array(), NULL );
            }
            
            
            // Material-design-icons
            wp_enqueue_style( 'hippo-material-design-icons', get_template_directory_uri() . '/css/material-design-iconic-font.min.css', array(), '2.1.2' );
            
            // Font Awesome Icons
            wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.css', array(), '4.4.0' );
            
            // Animate css
            wp_enqueue_style( 'animate', get_template_directory_uri() . '/css/animate.css', array(), NULL );
            
            hippo_enqueue_twitter_bootstrap();
            
            if ( is_active_widget( FALSE, FALSE, 'hippo_latest_tweet', TRUE ) ) :
                
                hippo_enqueue_owl_carousel();
            endif;
            
            if ( ( function_exists( 'is_product' ) && ! is_product() ) ) {
                hippo_enqueue_magnific_popup();
            }
            
            // hippo-offcanvas
            wp_enqueue_style( 'hippo-offcanvas', get_template_directory_uri() . '/css/hippo-off-canvas.css', array(), NULL );
            
            
            if ( class_exists( 'Hippo_Less_Css_Init' ) ) {
                wp_enqueue_style( 'master-less', hippo_locate_template_uri( 'less/master.less' ) );
            }
            else {
                wp_enqueue_style( 'hippo-main-css', sprintf( '%s/css-compiled/master-%s.css', get_template_directory_uri(), hippo_option_get_preset() ) );
            }
            // main stylesheet
            wp_enqueue_style( 'stylesheet', get_stylesheet_uri() );
            
            do_action( 'hippo_after_enqueue_styles' );
            
            // modernizr
            wp_enqueue_script( 'hippo-modernizr', get_template_directory_uri() . '/js/modernizr-2.8.1.min.js', array(), NULL );
            
            
            // Hippo offcanvas
            wp_enqueue_script( 'hippo-off-canvas', get_template_directory_uri() . '/js/hippo-off-canvas.js', array( 'jquery' ), NULL, TRUE );
            
            if ( hippo_option( 'sticky-menu', FALSE, TRUE ) ) {
                // Sticky menu js
                wp_enqueue_script( 'hippo-sticky-menu', get_template_directory_uri() . '/js/sticky-menu.js', array( 'jquery' ), NULL, TRUE );
            }
            
            if ( hippo_option( 'retina-ready', FALSE, FALSE ) ):
                // Retina js
                wp_enqueue_script( 'hippo-retina', get_template_directory_uri() . '/js/retina.min.js', array( 'jquery' ), NULL, TRUE );
            endif;
            
            if ( is_active_widget( FALSE, FALSE, 'hippo_flickr_photo', TRUE ) ) :
                hippo_enqueue_flickr_feed();
            endif;
            
            if ( substr( basename( get_page_template_slug() ), 0, 9 ) == 'blog-grid' ) :
                // Masonry
                wp_enqueue_script( 'jquery-masonry' );
            endif;
            
            // plugin
            wp_enqueue_script( 'hippo-script', get_template_directory_uri() . '/js/scripts.js', array( 'jquery', 'wp-util' ), NULL, TRUE );
            
            // localize script
            wp_localize_script( 'hippo-script', 'hippoJSObject', apply_filters( 'hippo_js_object', array(
                'ajax_url'                => esc_url( admin_url( 'admin-ajax.php' ) ),
                'site_url'                => esc_url( site_url( '/' ) ),
                'home_url'                => esc_url( home_url( '/' ) ),
                'theme_url'               => get_template_directory_uri(),
                'is_front_page'           => is_front_page(),
                'is_home'                 => is_home(),
                'offcanvas_menu_position' => 'hippo-offcanvas-' . hippo_option( 'offcanvas-menu-position', FALSE, 'left' ),
                'offcanvas_menu_effect'   => hippo_option( 'offcanvas-menu-effect', FALSE, 'reveal' ),
                'currency_switcher'       => hippo_option( 'currency-switcher', FALSE, FALSE ),
                'back_to_top'             => hippo_option( 'back-to-top', FALSE, TRUE )
            ) ) );
            
            
            if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) :
                wp_enqueue_script( 'comment-reply' );
            endif;
            
            do_action( 'hippo_after_enqueue_scripts' );
        }
        
        add_action( 'wp_enqueue_scripts', 'hippo_scripts', 11 );
    endif;
    
    
    if ( ! function_exists( 'hippo_audio_video_shortcode_class' ) ) :
        function hippo_audio_video_shortcode_class( $class ) {
            return $class . ' mejs-mejskin';
        }
        
        add_filter( 'wp_audio_shortcode_class', 'hippo_audio_video_shortcode_class' );
        add_filter( 'wp_video_shortcode_class', 'hippo_audio_video_shortcode_class' );
    endif;
    
    if ( class_exists( 'WooCommerce' ) ) :
        //-------------------------------------------------------------------------------
        // WooCommerce Functionality
        //-------------------------------------------------------------------------------
        require get_template_directory() . '/inc/woocommerce.php';
    endif;
    
    //-------------------------------------------------------------------------------
    // Custom template tags for this theme.
    //-------------------------------------------------------------------------------
    
    require get_template_directory() . '/inc/template-tags.php';
    
    //-------------------------------------------------------------------------------
    // Custom functions that act independently of the theme templates.
    //-------------------------------------------------------------------------------
    
    require get_template_directory() . '/inc/extras.php';
    
    
    //-------------------------------------------------------------------------------
    // Load Jetpack compatibility file.
    //-------------------------------------------------------------------------------
    require get_template_directory() . '/inc/jetpack.php';
    
    //----------------------------------------------------------------------
    // Admin Functions
    //----------------------------------------------------------------------
    
    require get_template_directory() . '/admin/admin-init.php';