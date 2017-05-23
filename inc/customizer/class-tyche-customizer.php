<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Tyche_Customizer
 */
class Tyche_Customizer {
	/**
	 * Tyche_Customizer constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'customize_register' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customizer_enqueues' ) );
		add_action( 'customize_preview_init', array( $this, 'customize_preview_js' ) );
	}

	/**
	 * @param $wp_customize
	 */
	public function customize_register( $wp_customize ) {
		$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
		$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

		$wp_customize->get_section( 'title_tagline' )->panel    = 'appearance';
		$wp_customize->get_section( 'title_tagline' )->title    = esc_html__( 'General Options', 'tyche' );
		$wp_customize->get_section( 'colors' )->panel           = 'appearance';
		$wp_customize->get_section( 'background_image' )->panel = 'appearance';

		if ( ! class_exists( 'Kirki' ) ) {
			require_once get_template_directory() . '/inc/libraries/class-kirki-installer-section.php';
		}

		/**
		 * Add the theme configuration
		 */
		tyche_Kirki::add_config( 'tyche_theme', array(
			'option_type' => 'theme_mod',
			'capability'  => 'edit_theme_options',
		) );

		/**
		 * Load panels, sections and options
		 */
		require_once get_template_directory() . '/inc/customizer/theme-options/panels.php';
		require_once get_template_directory() . '/inc/customizer/theme-options/sections.php';
		require_once get_template_directory() . '/inc/customizer/theme-options/options.php';
	}

	/**
	 *
	 */
	public function customize_preview_js() {
		wp_enqueue_script( 'tyche_customizer', get_template_directory_uri() . '/inc/customizer/assets/js/previewer.js', array( 'customize-preview' ), '201512', true );
		wp_localize_script( 'tyche_customizer', 'WPUrls', array(
			'siteurl' => get_option( 'siteurl' ),
			'theme'   => get_template_directory_uri(),
			'ajaxurl' => admin_url( 'admin-ajax.php' )
		) );
	}

	/**
	 *
	 */
	public function customizer_enqueues() {
		wp_enqueue_media();
		wp_enqueue_style( 'tyche_media_upload_css', get_template_directory_uri() . '/inc/customizer/assets/css/upload-media.css' );
		wp_enqueue_script( 'tyche_media_upload_js', get_template_directory_uri() . '/inc/customizer/assets/js/upload-media.js', array( 'jquery' ) );
		wp_localize_script( 'tyche_media_upload_js', 'WPUrls', array(
			'siteurl' => get_option( 'siteurl' ),
			'theme'   => get_template_directory_uri(),
			'ajaxurl' => admin_url( 'admin-ajax.php' )
		) );
		wp_enqueue_script( 'tyche-image-upload', get_template_directory_uri() . '/assets/js/upload-media.js', array(
			'jquery',
			'customize-controls'
		) );
		wp_enqueue_style( 'tyche-image-upload', get_template_directory_uri() . '/assets/css/upload-media.css' );
	}

}