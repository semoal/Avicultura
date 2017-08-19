<?php
    /**
     * This file represents an example of the code that themes would use to register
     * the required plugins.
     *
     * It is expected that theme authors would copy and paste this code into their
     * functions.php file, and amend to suit.
     *
     * @see        http://tgmpluginactivation.com/configuration/ for detailed documentation.
     *
     * @package    TGM-Plugin-Activation
     * @subpackage Example
     * @version    2.5.0
     * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
     * @copyright  Copyright (c) 2011, Thomas Griffin
     * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
     * @link       https://github.com/TGMPA/TGM-Plugin-Activation
     */
    /**
     * Include the TGM_Plugin_Activation class.
     */
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    require get_template_directory() . '/required-plugins/class-tgm-plugin-activation.php';
    
    add_action( 'tgmpa_register', 'hippo_theme_register_required_plugins' );
    
    function hippo_theme_register_required_plugins() {
        /*
         * Array of plugin arrays. Required keys are name and slug.
         * If the source is NOT from the .org repo, then source is also required.
         */
        
        $plugins = array(
            
            // Sink Theme Plugin
            array(
                'name'     => esc_html__( 'Sink Theme Plugin', 'sink' ),
                // The plugin name
                'slug'     => 'hippo-plugin',
                // The plugin slug (typically the folder name)
                'source'   => esc_url( 'https://s3-us-west-2.amazonaws.com/helper-plugins/sink-theme-plugin.zip' ),
                // The plugin source
                'required' => TRUE,
                // If false, the plugin is only 'recommended' instead of required
                'version'  => '2.0.0'
            ),
            // Visual Composer
            array(
                'name'     => esc_html__( 'WPBakery Visual Composer', 'sink' ),
                // The plugin name
                'slug'     => 'js_composer',
                // The plugin slug (typically the folder name)
                'source'   => esc_url( 'https://s3-us-west-2.amazonaws.com/theme-required-plugins/js_composer.zip' ),
                // The plugin source
                'required' => TRUE,
                // If false, the plugin is only 'recommended' instead of required
                'version'  => '5.2',
            ),
            // Revolution Slider
            array(
                'name'     => esc_html__( 'Revolution Slider', 'sink' ),
                // The plugin name
                'slug'     => 'revslider',
                // The plugin slug (typically the folder name)
                'source'   => esc_url( 'https://s3-us-west-2.amazonaws.com/theme-required-plugins/revslider.zip' ),
                // The plugin source
                'required' => TRUE,
                // If false, the plugin is only 'recommended' instead of required
                'version'  => '5.4.5.1',
            ),
            // Envato WordPress Toolkit
            array(
                'name'     => esc_html__( 'Envato Market Plugin for automatic update', 'sink' ),
                // The plugin name
                'slug'     => 'envato-market',
                // The plugin slug (typically the folder name)
                'source'   => esc_url( 'http://envato.github.io/wp-envato-market/dist/envato-market.zip' ),
                // The plugin source
                'required' => FALSE,
                // If false, the plugin is only 'recommended' instead of required
            ),
            // Contact Form 7
            array(
                'name'     => esc_html__( 'Contact Form 7', 'sink' ),
                'slug'     => 'contact-form-7',
                'required' => TRUE,
            ),
            // Redux Framework
            array(
                'name'     => esc_html__( 'Redux Framework', 'sink' ),
                'slug'     => 'redux-framework',
                'required' => TRUE,
            ),
            // Regenerate Thumbnails
            array(
                'name'     => esc_html__( 'Regenerate Thumbnails', 'sink' ),
                'slug'     => 'regenerate-thumbnails',
                'required' => FALSE,
            ),
            // Woocommerce
            array(
                'name'     => esc_html__( 'WooCommerce', 'sink' ),
                'slug'     => 'woocommerce',
                'required' => FALSE,
            ),
            // WooCommerce Wishlist
            array(
                'name'     => esc_html__( 'WooCommerce Wishlist', 'sink' ),
                'slug'     => 'yith-woocommerce-wishlist',
                'required' => FALSE,
            )
        );
        
        /*
         * Array of configuration settings. Amend each line as needed.
         *
         * TGMPA will start providing localized text strings soon. If you already have translations of our standard
         * strings available, please help us make TGMPA even better by giving us access to these translations or by
         * sending in a pull-request with .po file(s) with the translations.
         *
         * Only uncomment the strings in the config array if you want to customize the strings.
         */
        $config = array(
            'id'           => 'sink',                 // Unique ID for hashing notices for multiple instances of TGMPA.
            'default_path' => '',                      // Default absolute path to bundled plugins.
            'menu'         => 'tgmpa-install-plugins', // Menu slug.
            'has_notices'  => TRUE,                    // Show admin notices or not.
            'dismissable'  => TRUE,                    // If false, a user cannot dismiss the nag message.
            'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => FALSE,                   // Automatically activate plugins after installation or not.
            'message'      => '',                      // Message to output right before the plugins table.
        );
        tgmpa( $plugins, $config );
    }

