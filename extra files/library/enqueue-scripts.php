<?php
/**
 * Enqueue all styles and scripts
 *
 * Learn more about enqueue_script: {@link https://codex.wordpress.org/Function_Reference/wp_enqueue_script}
 * Learn more about enqueue_style: {@link https://codex.wordpress.org/Function_Reference/wp_enqueue_style }
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */


// Check to see if rev-manifest exists for CSS and JS static asset revisioning
//https://github.com/sindresorhus/gulp-rev/blob/master/integration.md

if ( ! function_exists( 'foundationpress_asset_path' ) ) :
	function foundationpress_asset_path( $filename ) {
		$filename_split = explode( '.', $filename );
		$dir            = end( $filename_split );
		$manifest_path  = dirname( dirname( __FILE__ ) ) . '/dist/assets/' . $dir . '/rev-manifest.json';

		if ( file_exists( $manifest_path ) ) {
			$manifest = json_decode( file_get_contents( $manifest_path ), true );
		} else {
			$manifest = array();
		}

		if ( array_key_exists( $filename, $manifest ) ) {
			return $manifest[ $filename ];
		}
		return $filename;
	}
endif;


if ( ! function_exists( 'foundationpress_scripts' ) ) :
	function foundationpress_scripts() {
		
		// Bootstrap
		wp_enqueue_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css', array(), '1.0.0', 'all' );

		// Enque Web fonts
		wp_enqueue_style( 'web-fonts', 'https://use.typekit.net/zip1fwz.css', array(), '1.0.0', 'all' );

		// Enqueue the main Stylesheet.
		wp_enqueue_style( 'main-stylesheet', get_stylesheet_directory_uri() . '/dist/css/styles.css', array(), '1.1', 'all' );

		// Enqueue the main Stylesheet.
		wp_enqueue_style( 'lightslider', get_stylesheet_directory_uri() . '/dist/css/lightslider.css', array(), '1.1', 'all' );

		// Enque Font Awesome
		wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), '1.0.0', 'all' );


		wp_enqueue_style( 'slick', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', array(), '1.0.0', 'all' );



		// Enque swiffy style
		wp_enqueue_style( 'swiffy', 'https://cdn.jsdelivr.net/npm/swiffy-slider@1.5.3/dist/css/swiffy-slider.min.css', array(), '1.5.3', 'all' );

		// Deregister the jquery version bundled with WordPress.
		wp_deregister_script( 'jquery' );

		wp_enqueue_style('googlFonts', 'https://fonts.googleapis.com/css2?family=Inter&family=Slabo+13px&display=swap', array(), null);


		// CDN hosted jQuery placed in the header, as some plugins require that jQuery is loaded in the header.
		wp_enqueue_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js', array(), '1.11.3', false );

					// Enqueue scripts
		wp_enqueue_script( 'gsap', 'https://assets.codepen.io/16327/gsap-latest-beta.min.js?r=3.11.5', array(), '1.0', true );

		wp_enqueue_script( 'slick', 'https://unpkg.com/gsap@3/dist/Draggable.min.js', array(), '1.0', true );


		// Enqueue scripts
		wp_enqueue_script( 'lightslider', get_stylesheet_directory_uri() . '/dist/js/lightslider.js', array(), '1.0', true );


		// Enqueue scripts
		wp_enqueue_script( 'core', get_stylesheet_directory_uri() . '/dist/js/scripts.js', array(), '1.0', true );

		// Swiffy js
		wp_enqueue_script( 'swiffy', 'https://cdn.jsdelivr.net/npm/swiffy-slider@1.5.3/dist/js/swiffy-slider.min.js', array(), '1.5.3', false );
		
		
		wp_enqueue_script( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', array(), '5.0.2', false );
	

		
		// Add the comment-reply library on pages where it is necessary
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	add_action( 'wp_enqueue_scripts', 'foundationpress_scripts' );
endif;
