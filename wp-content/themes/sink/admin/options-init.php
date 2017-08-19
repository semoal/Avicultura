<?php
    
    /**
     * Theme Settings Config File
     *
     */
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    // This is your option name where all the Redux data is stored.
    $redux_opt_name = hippo_option_name();
    
    //===============================================================================
    //  SET ARGUMENTS
    // For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
    //===============================================================================
    
    $theme = wp_get_theme(); // For use with some settings. Not necessary.
    
    $args = array(
        // TYPICAL -> Change these values as you need/desire
        'opt_name'                  => $redux_opt_name,
        // This is where your data is stored in the database and also becomes your global variable name.
        'display_name'              => $theme->get( 'Name' ),
        // Name that appears at the top of your panel
        'display_version'           => $theme->get( 'Version' ),
        // Version that appears at the top of your panel
        'menu_type'                 => 'menu',
        //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
        'allow_sub_menu'            => TRUE,
        // Show the sections below the admin menu item or not
        'menu_title'                => sprintf( esc_html__( '%s Options', 'sink' ), $theme->get( 'Name' ) ),
        'page_title'                => sprintf( esc_html__( '%s Theme Options', 'sink' ), $theme->get( 'Name' ) ),
        // You will need to generate a Google API key to use this feature.
        // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
        'google_api_key'            => '',
        // Set it you want google fonts to update weekly. A google_api_key value is required.
        'google_update_weekly'      => FALSE,
        // Must be defined to add google fonts to the typography module
        'async_typography'          => FALSE,
        // Use a asynchronous font on the front end or font string
        'disable_google_fonts_link' => FALSE,
        // Disable this in case you want to create your own google fonts loader
        'admin_bar'                 => TRUE,
        // Show the panel pages on the admin bar
        'admin_bar_icon'            => 'dashicons-admin-generic',
        // Choose an icon for the admin bar menu
        'admin_bar_priority'        => 50,
        // Choose an priority for the admin bar menu
        'global_variable'           => '',
        // Set a different name for your global variable other than the opt_name
        'dev_mode'                  => FALSE,
        //'forced_dev_mode_off'  => TRUE,
        // Show the time the page took to load, etc
        'update_notice'             => TRUE,
        // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
        'customizer'                => TRUE,
        // Enable basic customizer support
        //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
        //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field
        
        // OPTIONAL -> Give you extra features
        'page_priority'             => '40',
        // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
        'page_parent'               => 'themes.php',
        // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
        'page_permissions'          => 'manage_options',
        // Permissions needed to access the options panel.
        'menu_icon'                 => '',
        // Specify a custom URL to an icon
        'last_tab'                  => '',
        // Force your panel to always open to a specific tab (by id)
        'page_icon'                 => 'icon-themes',
        // Icon displayed in the admin panel next to your menu_title
        'page_slug'                 => sanitize_title_with_dashes( $theme->get( 'Name' ) ),
        // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
        'save_defaults'             => TRUE,
        // On load save the defaults to DB before user clicks save or not
        'default_show'              => FALSE,
        // If true, shows the default value next to each field that is not the default value.
        'default_mark'              => '',
        // What to print by the field's title if the value shown is default. Suggested: *
        'show_import_export'        => TRUE,
        // Shows the Import/Export panel when not used as a field.
        
        // CAREFUL -> These options are for advanced use only
        'transient_time'            => 60 * MINUTE_IN_SECONDS,
        'output'                    => TRUE,
        // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
        'output_tag'                => TRUE,
        // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
        'footer_credit'             => sprintf( esc_html__( '%s Theme Options', 'sink' ), $theme->get( 'Name' ) ),
        // Disable the footer credit of Redux. Please leave if you can help it.
        
        // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
        'database'                  => '',
        // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
        'use_cdn'                   => TRUE,
        // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.
        
        // HINTS
        'hints'                     => array(
            'icon'          => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color'    => 'lightgray',
            'icon_size'     => 'normal',
            'tip_style'     => array(
                'color'   => 'red',
                'shadow'  => TRUE,
                'rounded' => FALSE,
                'style'   => '',
            ),
            'tip_position'  => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect'    => array(
                'show' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'mouseover',
                ),
                'hide' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'click mouseleave',
                ),
            ),
        )
    );
    
    // ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
    $args[ 'admin_bar_links' ][] = array(
        'href'  => 'https://themehippo.com/documentation/sink/',
        'title' => sprintf( esc_html__( '%s Theme Documentation', 'sink' ), $theme->get( 'Name' ) ),
    );
    
    $args[ 'admin_bar_links' ][] = array(
        'href'  => 'https://www.youtube.com/playlist?list=PLqqd1WvKoyJs3UR0yK-Nx2uxTtTDow9C_',
        'title' => sprintf( esc_html__( '%s Theme Videos', 'sink' ), $theme->get( 'Name' ) ),
    );
    
    $args[ 'admin_bar_links' ][] = array(
        'href'  => 'https://themehippo.com/tickets/',
        'title' => sprintf( esc_html__( '%s Theme Support', 'sink' ), $theme->get( 'Name' ) ),
    );
    
    Redux::setArgs( $redux_opt_name, apply_filters( 'hippo_theme_option_args', $args ) );
    
    //===============================================================================
    //  END ARGUMENTS
    //===============================================================================
    
    do_action( 'hippo_before_theme_options_deceleration', $redux_opt_name );
    
    Redux::setSection( $redux_opt_name, array(
        'icon'   => 'el-icon-cogs',
        'title'  => esc_html__( 'General Settings', 'sink' ),
        'fields' => array(
            array(
                'id'       => 'demo-data-installer',
                'type'     => 'switch',
                'title'    => esc_html__( 'Theme Setup Wizard', 'sink' ),
                'subtitle' => esc_html__( 'Show or Hide Theme Setup Wizard link on admin bar.', 'sink' ),
                'on'       => esc_html__( 'Show', 'sink' ),
                'off'      => esc_html__( 'Hide', 'sink' ),
                'default'  => TRUE,
            ),
            array(
                'id'       => 'show-preloader',
                'type'     => 'switch',
                'title'    => esc_html__( 'Page Preloader', 'sink' ),
                'subtitle' => esc_html__( 'Show or Hide page preloader.', 'sink' ),
                'on'       => esc_html__( 'Show', 'sink' ),
                'off'      => esc_html__( 'Hide', 'sink' ),
                'default'  => FALSE,
            ),
            array(
                'id'       => 'back-to-top',
                'type'     => 'switch',
                'title'    => esc_html__( 'Back To Top', 'sink' ),
                'subtitle' => esc_html__( 'Show or Hide Back To Top.', 'sink' ),
                'on'       => esc_html__( 'Show', 'sink' ),
                'off'      => esc_html__( 'Hide', 'sink' ),
                'default'  => TRUE,
            ),
            array(
                'id'       => 'retina-ready',
                'type'     => 'switch',
                'title'    => esc_html__( 'Retina Ready', 'sink' ),
                'subtitle' => esc_html__( 'Retina ready image support', 'sink' ),
                'on'       => esc_html__( 'Enable', 'sink' ),
                'off'      => esc_html__( 'Disable', 'sink' ),
                'default'  => FALSE,
            ),
            array(
                'id'       => 'shadow-less-layout',
                'type'     => 'switch',
                'title'    => esc_html__( 'Shadow Less Style', 'sink' ),
                'subtitle' => esc_html__( 'Use shadow or shadow less layout.', 'sink' ),
                'on'       => esc_html__( 'Yes', 'sink' ),
                'off'      => esc_html__( 'No', 'sink' ),
                'default'  => FALSE,
            ),
        )
    ) );
    
    Redux::setSection( $redux_opt_name, array(
        'icon'   => 'el el-website',
        'title'  => esc_html__( 'Header Settings', 'sink' ),
        'fields' => array(
            
            array(
                'id'       => 'header-style',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Header style', 'sink' ),
                'subtitle' => esc_html__( 'Select Header style.', 'sink' ),
                'options'  => array(
                    'header-style-one'   => array(
                        'alt' => esc_html__( 'Header style one', 'sink' ),
                        'img' => get_template_directory_uri() . '/img/header-style/header-style-1.png'
                    ),
                    'header-style-two'   => array(
                        'alt' => esc_html__( 'Header style two', 'sink' ),
                        'img' => get_template_directory_uri() . '/img/header-style/header-style-2.png'
                    ),
                    'header-style-three' => array(
                        'alt' => esc_html__( 'Header style three', 'sink' ),
                        'img' => get_template_directory_uri() . '/img/header-style/header-style-3.png'
                    ),
                    'header-style-four'  => array(
                        'alt' => esc_html__( 'Header style four', 'sink' ),
                        'img' => get_template_directory_uri() . '/img/header-style/header-style-4.png'
                    )
                ),
                'default'  => 'header-style-one'
            ),
            
            array(
                'id'       => 'header-two-logo',
                'type'     => 'media',
                'preview'  => 'true',
                'required' => array( 'header-style', '=', 'header-style-two' ),
                'title'    => esc_html__( 'Header style two Logo.', 'sink' ),
                'subtitle' => esc_html__( 'Change header two logo. Dimension: Width: 100px, Height: 20px', 'sink' ),
                'desc'     => esc_html__( 'Its a fallback option and will remove on future version. You can choose your site logo from Customize => Site Identity => Logo Option', 'sink' ),
            
            ),
            array(
                'id'       => 'header-two-retina-logo',
                'type'     => 'media',
                'preview'  => 'true',
                'required' => array(
                    array( 'header-style', '=', 'header-style-two' ),
                    array( 'retina-ready', '=', '1' )
                ),
                'title'    => esc_html__( 'Header style two Retina Logo.', 'sink' ),
                'subtitle' => esc_html__( 'Change header two retina logo. Dimension: Width: 200px, Height: 40px', 'sink' ),
                'desc'     => esc_html__( 'Its a fallback option and will remove on future version. You can choose your site logo from Customize => Site Identity => Logo Option', 'sink' ),
            ),
            
            
            array(
                'id'       => 'header-top-bg-visibility',
                'type'     => 'switch',
                'title'    => esc_html__( 'Header Top Background', 'sink' ),
                'subtitle' => esc_html__( 'Show or hide header top background color', 'sink' ),
                'on'       => esc_html__( 'Show', 'sink' ),
                'off'      => esc_html__( 'Hide', 'sink' ),
                'default'  => TRUE,
            ),
            array(
                'id'       => 'bg-color-option',
                'type'     => 'switch',
                'title'    => esc_html__( 'Header top Background color', 'sink' ),
                'subtitle' => esc_html__( 'Select custom to change background color', 'sink' ),
                'required' => array( 'header-top-bg-visibility', '=', '1' ),
                'on'       => esc_html__( 'Default', 'sink' ),
                'off'      => esc_html__( 'Custom', 'sink' ),
                'default'  => TRUE,
            ),
            array(
                'id'       => 'custom-top-bg',
                'type'     => 'background',
                'preview'  => 'true',
                'title'    => esc_html__( 'Header Background', 'sink' ),
                'subtitle' => esc_html__( 'Change header top bar background', 'sink' ),
                'required' => array( 'bg-color-option', '=', '0' ),
                'default'  => array(
                    'background-color' => '#5DCAD1'
                )
            ),
            array(
                'id'       => 'header-background-style',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Header background style', 'sink' ),
                'subtitle' => esc_html__( 'Select Header background style.', 'sink' ),
                'required' => array(
                    array( 'header-top-bg-visibility', '=', '1' ),
                    array( 'bg-color-option', '=', '1' )
                ),
                'options'  => array(
                    'bg-style-one'   => array(
                        'alt' => esc_html__( 'Header background style one', 'sink' ),
                        'img' => get_template_directory_uri() . '/img/header-bg/style-1.png'
                    ),
                    'bg-style-two'   => array(
                        'alt' => esc_html__( 'Header background style two', 'sink' ),
                        'img' => get_template_directory_uri() . '/img/header-bg/style-2.png'
                    ),
                    'bg-style-three' => array(
                        'alt' => esc_html__( 'Header background style three', 'sink' ),
                        'img' => get_template_directory_uri() . '/img/header-bg/style-3.png'
                    ),
                    'bg-style-four'  => array(
                        'alt' => esc_html__( 'Header background style four', 'sink' ),
                        'img' => get_template_directory_uri() . '/img/header-bg/style-4.png'
                    ),
                    'bg-style-five'  => array(
                        'alt' => esc_html__( 'Header background style five', 'sink' ),
                        'img' => get_template_directory_uri() . '/img/header-bg/style-5.png'
                    )
                ),
                'default'  => 'bg-style-five'
            ),
            array(
                'id'       => 'show-tagline',
                'type'     => 'switch',
                'title'    => esc_html__( 'Show tagline', 'sink' ),
                'subtitle' => esc_html__( 'Show or hide tagline from header', 'sink' ),
                'desc'     => sprintf( '<a target="_blank" href="%s">', esc_url( admin_url( 'options-general.php' ) ) ) . esc_html__( 'Change Tagline', 'sink' ) . ' </a>',
                'on'       => esc_html__( 'Show', 'sink' ),
                'off'      => esc_html__( 'Hide', 'sink' ),
                'default'  => TRUE,
            ),
            array(
                'id'       => 'login-form',
                'type'     => 'switch',
                'title'    => esc_html__( 'Login Option', 'sink' ),
                'subtitle' => esc_html__( 'You can enable or disable login menu from here.', 'sink' ),
                'on'       => esc_html__( 'Enable', 'sink' ),
                'off'      => esc_html__( 'Disable', 'sink' ),
                'default'  => TRUE,
            ),
            array(
                'id'       => 'wpml-flag-show',
                'type'     => 'switch',
                'title'    => esc_html__( 'Show Language List', 'sink' ),
                'subtitle' => esc_html__( 'You can show or hide language switcher list on header', 'sink' ),
                'desc'     => esc_html__( 'You should have installed and activated WPML plugins.', 'sink' ),
                'on'       => esc_html__( 'Show', 'sink' ),
                'off'      => esc_html__( 'Hide', 'sink' ),
                'default'  => TRUE,
            ),
            array(
                'id'       => 'currency-switcher',
                'type'     => 'switch',
                'title'    => esc_html__( 'Show Currency Switcher', 'sink' ),
                'subtitle' => esc_html__( 'You can show or hide Currency switcher from header', 'sink' ),
                'desc'     => sprintf( '<a target="_blank" href="%s">', esc_url( admin_url( 'admin.php?page=wc-settings' ) ) ) . esc_html__( 'Set default currency', 'sink' ) . ' </a>',
                'on'       => esc_html__( 'Show', 'sink' ),
                'off'      => esc_html__( 'Hide', 'sink' ),
                'default'  => FALSE,
            ),
            array(
                'id'       => 'woo_currency_switcher',
                'type'     => 'hippo_repeater',
                'title'    => esc_html__( 'WooCommerce Currency Switcher', 'sink' ),
                'subtitle' => esc_html__( 'Choose currencies that you want to show a list', 'sink' ),
                'fields'   => array(
                    array(
                        'id'      => 'currency',
                        'type'    => 'select',
                        'title'   => esc_html__( 'Currency', 'sink' ),
                        'options' => ( function_exists( 'hippo_wc_get_currencies' ) ) ? hippo_wc_get_currencies() : array(),
                    ),
                    array(
                        'id'      => 'currency_position',
                        'type'    => 'select',
                        'title'   => esc_html__( 'Currency Position', 'sink' ),
                        'options' => ( function_exists( 'hippo_wc_get_currency_position' ) ) ? hippo_wc_get_currency_position() : array(),
                    ),
                    array(
                        'id'      => 'thousand_separator',
                        'type'    => 'text',
                        'title'   => esc_html__( 'Thousand Separator', 'sink' ),
                        'default' => ','
                    ),
                    array(
                        'id'      => 'decimal_separator',
                        'type'    => 'text',
                        'title'   => esc_html__( 'Decimal Separator', 'sink' ),
                        'default' => '.'
                    ),
                    array(
                        'id'      => 'decimal_number',
                        'type'    => 'text',
                        'title'   => esc_html__( 'Number of Decimals', 'sink' ),
                        'default' => '2'
                    ),
                )
            ),
            array(
                'id'       => 'sticky-menu',
                'type'     => 'switch',
                'title'    => esc_html__( 'Active Sticky Menu?', 'sink' ),
                'subtitle' => esc_html__( 'You can active or deactive sticky menu from here. Sticky menu available on header style: two, three and three', 'sink' ),
                'on'       => esc_html__( 'Yes', 'sink' ),
                'off'      => esc_html__( 'No', 'sink' ),
                'default'  => TRUE,
            )
        )
    ) );
    
    Redux::setSection( $redux_opt_name, array(
        'icon'   => 'el-icon-brush',
        'title'  => esc_html__( 'Preset Settings', 'sink' ),
        'id'     => 'hippo_preset_manager',
        'fields' => array(
            
            array(
                'id'       => 'less-compiler',
                'type'     => 'switch',
                'title'    => esc_html__( 'LESS Compiler', 'sink' ),
                'subtitle' => esc_html__( 'Turn on built-in LESS Compiler.', 'sink' ),
                'desc'     => esc_html__( 'You should always turned it off when you are on production / live site. But when you make changes on LESS file / preset / color / typography just turn it on, refresh your home page and turn it off again.', 'sink' ),
                'on'       => 'Enable',
                'off'      => 'Disable',
                'default'  => FALSE,
            ),
            array(
                'id'       => 'compress-less-output',
                'type'     => 'switch',
                'title'    => esc_html__( 'Compress LESS Output', 'sink' ),
                'subtitle' => esc_html__( 'Compress LESS Output for better page load.', 'sink' ),
                'on'       => 'Yes',
                'off'      => 'No',
                'default'  => FALSE,
                'required' => array( 'less-compiler', '=', '1' ),
            ),
            array(
                'id'    => 'preset_change_warning',
                'type'  => 'info',
                'icon'  => 'el-icon-info-sign',
                'title' => esc_html__( 'Remember Please!', 'sink' ),
                'style' => 'warning',
                'desc'  => esc_html__( 'If you wish to change preset or color settings, please make sure "Less Compiler" is enabled. Other wise no css effect will shown.', 'sink' )
            ),
            'hippo_preset_manager' => array(
                'id'       => 'preset',
                'type'     => 'hippo_preset',
                'title'    => esc_html__( 'Color Presets', 'sink' ),
                'subtitle' => esc_html__( 'Theme Color Presets', 'sink' ),
                'default'  => 'preset1',
                'options'  => array(
                    'preset1' => esc_html__( 'Preset 1', 'sink' ),
                    'preset2' => esc_html__( 'Preset 2', 'sink' ),
                    'preset3' => esc_html__( 'Preset 3', 'sink' ),
                    'preset4' => esc_html__( 'Preset 4', 'sink' ),
                    'preset5' => esc_html__( 'Preset 5', 'sink' ),
                ),
                'presets'  => array(
                    array(
                        'id'       => 'theme-color',
                        'type'     => 'color', // hippo_preset_color
                        'title'    => esc_html__( 'Theme Base Color', 'sink' ),
                        'subtitle' => esc_html__( 'Change theme base color', 'sink' ),
                        'default'  => array(
                            'preset1' => '#512da8',
                            'preset2' => '#0097a7',
                            'preset3' => '#388e3c',
                            'preset4' => '#ffa000',
                            'preset5' => '#303f9f'
                        )
                    ),
                    array(
                        'id'       => 'links-color',
                        'type'     => 'color',
                        'title'    => esc_html__( 'Link Color', 'sink' ),
                        'subtitle' => esc_html__( 'Change Link Color', 'sink' ),
                        'default'  => array(
                            'preset1' => '#ff4081',
                            'preset2' => '#ffc107',
                            'preset3' => '#ff5722',
                            'preset4' => '#8bc34a',
                            'preset5' => '#03a9f4'
                        ),
                    ),
                    array(
                        'id'       => 'hover-color',
                        'type'     => 'color',
                        'title'    => esc_html__( 'Hover Color', 'sink' ),
                        'subtitle' => esc_html__( 'Change Hover Color', 'sink' ),
                        'default'  => array(
                            'preset1' => '#c0c0c0',
                            'preset2' => '#a0a0a0',
                            'preset3' => '#c0c0c0',
                            'preset4' => '#c0c0c0',
                            'preset5' => '#c0c0c0'
                        ),
                    ),
                    array(
                        'id'       => 'body-background-color',
                        'type'     => 'color',
                        'title'    => esc_html__( 'Body Background Color', 'sink' ),
                        'subtitle' => esc_html__( 'Change body color', 'sink' ),
                        'default'  => array(
                            'preset1' => '#ffffff',
                            'preset2' => '#ffffff',
                            'preset3' => '#ffffff',
                            'preset4' => '#ffffff',
                            'preset5' => '#ffffff'
                        )
                    ),
                    array(
                        'id'       => 'contents-color',
                        'type'     => 'color',
                        'title'    => esc_html__( 'Content Color', 'sink' ),
                        'subtitle' => esc_html__( 'Change content color', 'sink' ),
                        'default'  => array(
                            'preset1' => '#7f7f7f',
                            'preset2' => '#606676',
                            'preset3' => '#606676',
                            'preset4' => '#606676',
                            'preset5' => '#606676'
                        )
                    ),
                    array(
                        'id'       => 'menu-color',
                        'type'     => 'color',
                        'title'    => esc_html__( 'Menu Color', 'sink' ),
                        'subtitle' => esc_html__( 'Change menu color', 'sink' ),
                        'default'  => array(
                            'preset1' => '#313131',
                            'preset2' => '#9197a8',
                            'preset3' => '#9197a8',
                            'preset4' => '#9197a8',
                            'preset5' => '#9197a8'
                        )
                    ),
                    array(
                        'id'       => 'headings-color',
                        'type'     => 'color',
                        'title'    => esc_html__( 'Heading Color', 'sink' ),
                        'subtitle' => esc_html__( 'Change all heading color', 'sink' ),
                        'default'  => array(
                            'preset1' => '#313131',
                            'preset2' => '#3a424d',
                            'preset3' => '#3a424d',
                            'preset4' => '#3a424d',
                            'preset5' => '#3a424d'
                        )
                    ),
                ),
            ),
        )
    ) );
    
    Redux::setSection( $redux_opt_name, array(
        'icon'   => 'el-icon-font',
        'title'  => esc_html__( 'Typography Settings', 'sink' ),
        'fields' => array(
            array(
                'id'          => 'body-typography',
                'type'        => 'typography',
                'title'       => esc_html__( 'Body Typography ', 'sink' ),
                'google'      => TRUE,
                'font-backup' => FALSE,
                // 'output'      => array('h2.site-description'),
                // 'units'       =>'px',
                'font-size'   => FALSE,
                'line-height' => FALSE,
                'color'       => FALSE,
                'text-align'  => FALSE,
                'all_styles'  => FALSE,
                'font-style'  => FALSE,
                'subtitle'    => esc_html__( 'Typography option with each property can be called individually.', 'sink' ),
                'default'     => array(
                    'font-style'  => '400',
                    'font-family' => 'Roboto',
                    'google'      => TRUE,
                ),
            ),
            array(
                'id'          => 'heading-typography',
                'type'        => 'typography',
                'title'       => esc_html__( 'Heading Typography ', 'sink' ),
                'google'      => TRUE,
                'font-backup' => FALSE,
                // 'output'      => array('h2.site-description'),
                // 'units'       =>'px',
                'font-size'   => FALSE,
                'line-height' => FALSE,
                'color'       => FALSE,
                'text-align'  => FALSE,
                'all_styles'  => TRUE,
                'font-style'  => FALSE,
                'subtitle'    => esc_html__( 'Typography option with each property can be called individually.', 'sink' ),
                'default'     => array(
                    'font-style'  => '700',
                    'font-family' => 'Roboto',
                    'google'      => TRUE,
                )
            ),
        )
    ) );
    
    Redux::setSection( $redux_opt_name, array(
        'icon'   => 'el-icon-lines',
        'title'  => esc_html__( 'Mobile Menu Settings', 'sink' ),
        'fields' => array(
            
            array(
                'id'       => 'offcanvas-menu-title',
                'type'     => 'text',
                'title'    => esc_html__( 'Offcanvas Menu Title', 'sink' ),
                'subtitle' => esc_html__( 'Change Offcanvas Menu Title', 'sink' ),
                'default'  => esc_html__( 'Sidebar Menu', 'sink' ),
            ),
            array(
                'id'      => 'offcanvas-menu-position',
                'type'    => 'image_select',
                'title'   => esc_html__( 'Mobile menu position', 'sink' ),
                'options' => array(
                    'left'  => array(
                        'alt' => 'Left Side',
                        'img' => ReduxFramework::$_url . 'assets/img/2cl.png'
                    ),
                    'right' => array(
                        'alt' => 'Right Side',
                        'img' => ReduxFramework::$_url . 'assets/img/2cr.png'
                    ),
                ),
                'default' => 'left'
            ),
            array(
                'id'      => 'offcanvas-menu-effect',
                'type'    => 'select',
                'title'   => esc_html__( 'Mobile menu effect', 'sink' ),
                'options' => array(
                    'slide-in-on-top'        => esc_html__( 'Slide in on top', 'sink' ),
                    'reveal'                 => esc_html__( 'Reveal', 'sink' ),
                    'slide-along'            => esc_html__( 'Slide along', 'sink' ),
                    'reverse-slide-out'      => esc_html__( 'Reverse slide out', 'sink' ),
                    'scale-down-pusher'      => esc_html__( 'Scale down pusher', 'sink' ),
                    'scale-up'               => esc_html__( 'Scale Up', 'sink' ),
                    'scale-rotate-pusher'    => esc_html__( 'Scale Rotate Pusher', 'sink' ),
                    'open-door'              => esc_html__( 'Open Door', 'sink' ),
                    'fall-down'              => esc_html__( 'Fall Down', 'sink' ),
                    'push-down'              => esc_html__( 'Push Down', 'sink' ),
                    'rotate-pusher'          => esc_html__( 'Rotate Pusher', 'sink' ),
                    'three-d-rotate-in'      => esc_html__( '3D Rotate In', 'sink' ),
                    'three-d-rotate-out'     => esc_html__( '3D Rotate Out', 'sink' ),
                    'delayed-three-d-rotate' => esc_html__( 'Delayed 3D rotate', 'sink' ),
                ),
                'default' => 'reveal',
            ),
        )
    ) );
    
    Redux::setSection( $redux_opt_name, array(
        'icon'   => 'el-icon-group-alt',
        'title'  => esc_html__( 'Social Settings', 'sink' ),
        'fields' => array(
            
            array(
                'id'       => 'social-section-show',
                'type'     => 'switch',
                'title'    => esc_html__( 'Show Social Section', 'sink' ),
                'subtitle' => esc_html__( 'Show or Hide Social Section in Header.', 'sink' ),
                'on'       => esc_html__( 'Show', 'sink' ),
                'off'      => esc_html__( 'Hide', 'sink' ),
                'default'  => TRUE,
            ),
            array(
                'id'       => 'rss-link',
                'type'     => 'switch',
                'required' => array( 'social-section-show', '=', '1' ),
                'title'    => esc_html__( 'RSS Link', 'sink' ),
                'subtitle' => esc_html__( 'Insert your custom link to show the RSS icon. Leave blank to hide icon.', 'sink' ),
                'on'       => esc_html__( 'Show', 'sink' ),
                'off'      => esc_html__( 'Hide', 'sink' ),
                'default'  => TRUE,
            ),
            array(
                'id'       => 'facebook-link',
                'type'     => 'text',
                'required' => array( 'social-section-show', '=', '1' ),
                'title'    => esc_html__( 'Facebook Link', 'sink' ),
                'subtitle' => esc_html__( 'Insert your custom link to show the Facebook icon. Leave blank to hide icon.', 'sink' ),
                'default'  => "#"
            ),
            array(
                'id'       => 'twitter-link',
                'type'     => 'text',
                'required' => array( 'social-section-show', '=', '1' ),
                'title'    => esc_html__( 'Twitter Link', 'sink' ),
                'subtitle' => esc_html__( 'Insert your custom link to show the Twitter icon. Leave blank to hide icon.', 'sink' ),
                'default'  => "#"
            ),
            array(
                'id'       => 'google-plus-link',
                'type'     => 'text',
                'required' => array( 'social-section-show', '=', '1' ),
                'title'    => esc_html__( 'Google Plus Link', 'sink' ),
                'subtitle' => esc_html__( 'Insert your custom link to show the Google Plus icon. Leave blank to hide icon.', 'sink' ),
                'default'  => "#"
            ),
            array(
                'id'       => 'youtube-link',
                'type'     => 'text',
                'required' => array( 'social-section-show', '=', '1' ),
                'title'    => esc_html__( 'Youtube Link', 'sink' ),
                'subtitle' => esc_html__( 'Insert your custom link to show the Youtube icon. Leave blank to hide icon.', 'sink' ),
            ),
            array(
                'id'       => 'skype-link',
                'type'     => 'text',
                'required' => array( 'social-section-show', '=', '1' ),
                'title'    => esc_html__( 'Skype Link', 'sink' ),
                'subtitle' => esc_html__( 'Insert your custom link to show the Skype icon. Leave blank to hide icon.', 'sink' ),
            ),
            array(
                'id'       => 'pinterest-link',
                'type'     => 'text',
                'required' => array( 'social-section-show', '=', '1' ),
                'title'    => esc_html__( 'Pinterest Link', 'sink' ),
                'subtitle' => esc_html__( 'Insert your custom link to show the Pinterest icon. Leave blank to hide icon.', 'sink' ),
                'default'  => "#"
            ),
            array(
                'id'       => 'flickr-link',
                'type'     => 'text',
                'required' => array( 'social-section-show', '=', '1' ),
                'title'    => esc_html__( 'Flickr Link', 'sink' ),
                'subtitle' => esc_html__( 'Insert your custom link to show the Flickr icon. Leave blank to hide icon.', 'sink' ),
            ),
            array(
                'id'       => 'linkedin-link',
                'type'     => 'text',
                'required' => array( 'social-section-show', '=', '1' ),
                'title'    => esc_html__( 'Linkedin Link', 'sink' ),
                'subtitle' => esc_html__( 'Insert your custom link to show the Linkedin icon. Leave blank to hide icon.', 'sink' ),
            ),
            array(
                'id'       => 'vimeo-link',
                'type'     => 'text',
                'required' => array( 'social-section-show', '=', '1' ),
                'title'    => esc_html__( 'Vimeo Link', 'sink' ),
                'subtitle' => esc_html__( 'Insert your custom link to show the Vimeo icon. Leave blank to hide icon.', 'sink' ),
            ),
            array(
                'id'       => 'instagram-link',
                'type'     => 'text',
                'required' => array( 'social-section-show', '=', '1' ),
                'title'    => esc_html__( 'Instagram Link', 'sink' ),
                'subtitle' => esc_html__( 'Insert your custom link to show the Instagram icon. Leave blank to hide icon.', 'sink' ),
            ),
            array(
                'id'       => 'dribbble-link',
                'type'     => 'text',
                'required' => array( 'social-section-show', '=', '1' ),
                'title'    => esc_html__( 'Dribbble Link', 'sink' ),
                'subtitle' => esc_html__( 'Insert your custom link to show the Dribbble icon. Leave blank to hide icon.', 'sink' ),
            ),
            array(
                'id'       => 'tumblr-link',
                'type'     => 'text',
                'required' => array( 'social-section-show', '=', '1' ),
                'title'    => esc_html__( 'Tumblr Link', 'sink' ),
                'subtitle' => esc_html__( 'Insert your custom link to show the Tumblr icon. Leave blank to hide icon.', 'sink' ),
            ),
        )
    ) );
    
    Redux::setSection( $redux_opt_name, array(
        'icon'   => 'el-icon-livejournal',
        'title'  => esc_html__( 'Blog Settings', 'sink' ),
        'fields' => array(
            
            array(
                'id'       => 'blog-title',
                'type'     => 'text',
                'title'    => esc_html__( 'Blog Subtitle', 'sink' ),
                'subtitle' => esc_html__( 'Write blog sub title here.', 'sink' ),
                'default'  => esc_html__( 'Blog', 'sink' ),
            ),
            
            array(
                'id'       => 'blog-layout',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Blog Layout', 'sink' ),
                'subtitle' => esc_html__( 'Blog layout content and sidebar alignment. Choose from Fullwidth, Left sidebar or Right sidebar layout.', 'sink' ),
                'options'  => array(
                    'sidebar-none'  => array(
                        'alt' => esc_html__( 'No Sidebar', 'sink' ),
                        'img' => ReduxFramework::$_url . 'assets/img/1col.png'
                    ),
                    'sidebar-left'  => array(
                        'alt' => esc_html__( 'Left Sidebar', 'sink' ),
                        'img' => ReduxFramework::$_url . 'assets/img/2cl.png'
                    ),
                    'sidebar-right' => array(
                        'alt' => esc_html__( 'Right Sidebar', 'sink' ),
                        'img' => ReduxFramework::$_url . 'assets/img/2cr.png'
                    )
                ),
                'default'  => 'sidebar-right'
            ),
            
            array(
                'id'       => 'thumbnail-image-linkable',
                'type'     => 'switch',
                'title'    => esc_html__( 'Thumbnail Image Linkable', 'sink' ),
                'subtitle' => esc_html__( 'Blog Post Thumbnail Image Linkable', 'sink' ),
                'on'       => esc_html__( 'Yes', 'sink' ),
                'off'      => esc_html__( 'No', 'sink' ),
                'default'  => FALSE,
            ),
            
            array(
                'id'       => 'hippo-single-post-sidebar',
                'type'     => 'switch',
                'title'    => esc_html__( 'Single post sidebar', 'sink' ),
                'subtitle' => esc_html__( 'Show or hide single post sidebar', 'sink' ),
                'on'       => esc_html__( 'Show', 'sink' ),
                'off'      => esc_html__( 'Hide', 'sink' ),
                'default'  => TRUE,
            ),
            array(
                'id'       => 'show-blog-share-button',
                'type'     => 'switch',
                'title'    => esc_html__( 'Show share button', 'sink' ),
                'subtitle' => esc_html__( 'You can show or hide social share button from single post.', 'sink' ),
                'on'       => esc_html__( 'Show', 'sink' ),
                'off'      => esc_html__( 'Hide', 'sink' ),
                'default'  => TRUE,
            ),
            array(
                'id'       => 'blog-share-button',
                'type'     => 'checkbox',
                'required' => array( 'show-blog-share-button', '=', '1' ),
                'title'    => esc_html__( 'Share button', 'sink' ),
                'subtitle' => esc_html__( 'Check mark for showing share button', 'sink' ),
                'options'  => array(
                    'facebook' => esc_html__( 'Facebook', 'sink' ),
                    'twitter'  => esc_html__( 'Twitter', 'sink' ),
                    'google'   => esc_html__( 'Google+', 'sink' ),
                    'linkedin' => esc_html__( 'Linkedin', 'sink' )
                ),
                'default'  => array(
                    'facebook' => '1',
                    'twitter'  => '1',
                    'google'   => '1',
                    'linkedin' => '1',
                )
            ),
            array(
                'id'       => 'post-navigation',
                'type'     => 'switch',
                'title'    => esc_html__( 'Post navigation', 'sink' ),
                'subtitle' => esc_html__( 'Blog single post navigation', 'sink' ),
                'desc'     => esc_html__( '< Previous Article | Next Article >', 'sink' ),
                'on'       => esc_html__( 'Show', 'sink' ),
                'off'      => esc_html__( 'Hide', 'sink' ),
                'default'  => TRUE,
            ),
            array(
                'id'       => 'blog-page-nav',
                'type'     => 'switch',
                'title'    => esc_html__( 'Blog Pagination or Navigation', 'sink' ),
                'subtitle' => esc_html__( 'Blog pagination style, posts pagination or newer / older posts', 'sink' ),
                'desc'     => esc_html__( 'Older Entries | Newer Entries, posts pagination [1 | 2 | 3 ... 8 | 9]', 'sink' ),
                'on'       => esc_html__( 'Pagination', 'sink' ),
                'off'      => esc_html__( 'Navigation', 'sink' ),
                'default'  => TRUE,
            ),
        )
    ) );
    
    Redux::setSection( $redux_opt_name, array(
        'icon'   => 'el-icon-file-edit',
        'title'  => esc_html__( 'Page Settings', 'sink' ),
        'fields' => array(
            
            array(
                'id'       => 'page-layout',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Page Layout', 'sink' ),
                'subtitle' => esc_html__( 'Page layout content and sidebar alignment. Choose from Fullwidth, Left sidebar or Right sidebar layout.', 'sink' ),
                'options'  => array(
                    'sidebar-none'  => array(
                        'alt' => esc_html__( 'No Sidebar', 'sink' ),
                        'img' => ReduxFramework::$_url . 'assets/img/1col.png'
                    ),
                    'sidebar-left'  => array(
                        'alt' => esc_html__( 'Left Sidebar', 'sink' ),
                        'img' => ReduxFramework::$_url . 'assets/img/2cl.png'
                    ),
                    'sidebar-right' => array(
                        'alt' => esc_html__( 'Right Sidebar', 'sink' ),
                        'img' => ReduxFramework::$_url . 'assets/img/2cr.png'
                    )
                ),
                'default'  => 'sidebar-right'
            ),
            array(
                'id'       => 'page-comment',
                'type'     => 'switch',
                'title'    => esc_html__( 'Globally enable or disable page comments', 'sink' ),
                'subtitle' => esc_html__( 'Enable or Disabled Page Comments.', 'sink' ),
                'on'       => esc_html__( 'Enable', 'sink' ),
                'off'      => esc_html__( 'Disabled', 'sink' ),
                'default'  => FALSE,
            ),
        )
    ) );
    
    Redux::setSection( $redux_opt_name, array(
        'icon'   => 'el-icon-shopping-cart',
        'title'  => esc_html__( 'Shop Settings', 'sink' ),
        'fields' => array(
            array(
                'id'       => 'linkable-product-block',
                'type'     => 'switch',
                'title'    => esc_html__( 'Linkable product block', 'sink' ),
                'subtitle' => esc_html__( 'Make each product block linkable', 'sink' ),
                'on'       => esc_html__( 'Show', 'sink' ),
                'off'      => esc_html__( 'Hide', 'sink' ),
                'default'  => FALSE,
            ),
            array(
                'id'      => 'shop-perpage',
                'type'    => 'text',
                'title'   => esc_html__( 'Shop page product display limit', 'sink' ),
                'desc'    => esc_html__( 'Enter post number that you want to show product on shop page.', 'sink' ),
                'default' => 12,
            ),
            array(
                'id'      => 'shop-cat-perpage',
                'type'    => 'text',
                'title'   => esc_html__( 'Shop category page product display limit', 'sink' ),
                'desc'    => esc_html__( 'Enter post number that you want to show product on shop category page.', 'sink' ),
                'default' => 12,
            ),
            array(
                'id'      => 'shop-sub-category-column',
                'type'    => 'select',
                'title'   => esc_html__( 'Subcategory Column', 'sink' ),
                'desc'    => esc_html__( 'Choose number of sub category column. Calculated from medium device ( col-md-* ).', 'sink' ),
                'options' => array(
                    'col-md-3'  => esc_html__( '3 Columns 1/4', 'sink' ),
                    'col-md-4'  => esc_html__( '4 Columns 1/3', 'sink' ),
                    'col-md-6'  => esc_html__( '6 Columns 1/2', 'sink' ),
                    'col-md-12' => esc_html__( '12 Columns 1/1', 'sink' ),
                ),
                'default' => 'col-md-6',
            ),
            array(
                'id'      => 'shop-sub-category-grid-class',
                'type'    => 'text',
                'title'   => esc_html__( 'Sub Category column grid class', 'sink' ),
                'desc'    => esc_html__( 'Enter sub category grid column, without dot, use space to use multiple class. Default is ( col-md-* )', 'sink' ),
                'default' => '',
            ),
            array(
                'id'       => 'cart-icon',
                'type'     => 'switch',
                'title'    => esc_html__( 'Cart Icon', 'sink' ),
                'subtitle' => esc_html__( 'Show or Hide cart icon on header', 'sink' ),
                'on'       => esc_html__( 'Show', 'sink' ),
                'off'      => esc_html__( 'Hide', 'sink' ),
                'default'  => TRUE,
            ),
            array(
                'id'      => 'minicart-title-show',
                'type'    => 'switch',
                'title'   => esc_html__( 'Show Mini cart Title', 'sink' ),
                'on'      => esc_html__( 'Show', 'sink' ),
                'off'     => esc_html__( 'Hide', 'sink' ),
                'default' => TRUE,
            ),
            array(
                'id'       => 'mini-cart-title',
                'type'     => 'text',
                'required' => array( 'minicart-title-show', '=', '1' ),
                'title'    => esc_html__( 'Mini Cart Title', 'sink' ),
                'default'  => esc_html__( 'Your Cart', 'sink' ),
            ),
            array(
                'id'       => 'show-shop-share-button',
                'type'     => 'switch',
                'title'    => esc_html__( 'Show share button', 'sink' ),
                'subtitle' => esc_html__( 'You can show or hide social share button from single post.', 'sink' ),
                'on'       => esc_html__( 'Show', 'sink' ),
                'off'      => esc_html__( 'Hide', 'sink' ),
                'default'  => TRUE,
            ),
            array(
                'id'       => 'shop-share-button',
                'type'     => 'checkbox',
                'required' => array( 'show-shop-share-button', '=', '1' ),
                'title'    => esc_html__( 'Share button', 'sink' ),
                'subtitle' => esc_html__( 'Check mark for showing share button', 'sink' ),
                'options'  => array(
                    'facebook' => esc_html__( 'Facebook', 'sink' ),
                    'twitter'  => esc_html__( 'Twitter', 'sink' ),
                    'google'   => esc_html__( 'Google+', 'sink' ),
                    'linkedin' => esc_html__( 'Linkedin', 'sink' )
                ),
                'default'  => array(
                    'facebook' => '1',
                    'twitter'  => '1',
                    'google'   => '1',
                    'linkedin' => '1',
                )
            ),
        )
    ) );
    
    Redux::setSection( $redux_opt_name, array(
        'icon'   => 'el-icon-photo',
        'title'  => esc_html__( 'Footer Settings', 'sink' ),
        'fields' => array(
            
            array(
                'id'       => 'footer-logo',
                'type'     => 'media',
                'preview'  => 'true',
                'title'    => esc_html__( 'Footer Logo', 'sink' ),
                'subtitle' => esc_html__( 'Change site footer logo', 'sink' ),
                'desc'     => esc_html__( 'Logo width: Width: 60px Height: 85px', 'sink' )
            ),
            
            array(
                'id'       => 'footer-contact',
                'type'     => 'editor',
                'title'    => esc_html__( 'Footer Contact', 'sink' ),
                'subtitle' => esc_html__( 'Change footer contact and address', 'sink' ),
                'default'  => wp_kses( '<span>Sink Inc., 8901 Marmora Road, Glasgow, D04 89GR.  Phone - (800) 2345-6789</span>', array( 'span' => array( 'class' => array() ) ) ),
            ),
            array(
                'id'       => 'footer-text',
                'type'     => 'editor',
                'title'    => esc_html__( 'Footer Copyright Text', 'sink' ),
                'subtitle' => esc_html__( 'Change footer copyright text', 'sink' )
            )
        )
    ) );
    
    //   Redux::setSection( $redux_opt_name, array());
    
    do_action( 'hippo_after_theme_options_deceleration', $redux_opt_name );
    
    //===============================================================================
    //  END SETTINGS
    //===============================================================================