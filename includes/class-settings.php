<?php

if ( !defined( 'ABSPATH' ) )
	exit;

/**
 * Class to register all settings in the settings panel
 */
class EepSettings {

	public function __construct() {

		// Call when plugin is initialized on every page load
		add_action( 'init', array( $this, 'load_settings_panel' ) );

		// Add filters on the menu style so we can apply the setting option
		add_filter( 'eep_portfolio_args', array( $this, 'set_style' ) );
		add_filter( 'eep_shortcode_portfolio_atts', array( $this, 'set_style' ) );

	}

	/**
	 * Get the theme supports options for this plugin
	 *
	 * This mimics the core get_theme_support function, except it automatically
	 * looks up this plugin's feature set and searches for features within
	 * those settings.
	 *
	 * @param string $feature The feature support to check
	 * @since 1.5
	 */
	public function get_theme_support( $feature ) {

		$theme_support = get_theme_support( 'excellent-engineering-portfolio' );

		if ( !is_array( $theme_support ) ) {
			return apply_filters( 'eep_get_theme_support_' . $feature, false, $theme_support );
		}

		$theme_support = $theme_support[0];

		if ( isset( $theme_support[$feature] ) ) {
			return apply_filters( 'eep_get_theme_support_' . $feature, $theme_support[$feature], $theme_support );
		}

		return apply_filters( 'eep_get_theme_support_' . $feature, false, $theme_support );
	}

	/**
	 * Load the admin settings page
	 * @since 1.1
	 * @sa https://github.com/NateWr/simple-admin-pages
	 */
	public function load_settings_panel() {

	}

	/**
	 * Set the style of a menu or menu item before rendering
	 * @since 1.1
	 */
	public function set_style( $args ) {

		$settings = get_option( 'eep-excellent-engineering-portfolio-settings' );
		if ( !$settings['eep-style'] ) {
			$args['style'] = 'base';
		} else {
			$args['style'] = $settings['eep-style'];
		}

		return $args;
	}

}
