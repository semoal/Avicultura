<?php

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	// new Hippo_Rev_Slider_Import('http://example.com/', array('slider1.zip', 'slider2.zip'));

	class Hippo_Rev_Slider_Import {

		public $files = array();

		public function __construct( $file ) {

			$this->files = $file;

			if ( class_exists( 'RevSlider' ) ) {
				foreach ( $this->files as $file ) {
					$data   = download_url( $file, $timeout = 300 );
					$slider = new RevSlider();
					$slider->importSliderFromPost( TRUE, TRUE, $data );
					unset( $slider );
					unlink( $data );
				}
			}
		}
	}