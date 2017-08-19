<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

    class Hippo_ThemeOption_Import
    {


        public function __construct($file)
        {
            if (file_exists($file))
                $this->import_option_data($file);
        }


        public function import_option_data($import_file)
        {

            $fetch = file_get_contents($import_file);
            $data  = unserialize(base64_decode($fetch)); // leave this from checking :)

// hippo_replace_dev_urls

            $data = json_encode( $data );
            $data = apply_filters('hippo_import_process_theme_option_data', $data, $data);
            $data = json_decode( $data,true );

            $theme = get_option('stylesheet');
            update_option("theme_mods_{$theme}", $data);
            echo '<p>'.__('Theme Options Imported successfully.').'</p>';
        }
    }