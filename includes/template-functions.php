<?php
/**
 * Template tags and shortcodes for use with Food and Drink Menu
 */


/**
 * Create a shortcode to display the portfolio
 * @since 1.0
 */
function eep_portfolio_shortcode( $atts ) {

	// Define shortcode attributes
	$menu_atts = array(
		'id' => null,
		'layout' => 'classic',
		'show_title' => false,
		'show_content' => false,
	);

	// Create filter so addons can modify the accepted attributes
	$menu_atts = apply_filters( 'eep_shortcode_portfolio_atts', $menu_atts );

	// Extract the shortcode attributes
	$args = shortcode_atts( $menu_atts, $atts );

	// Render portfolio
	eep_load_view_files();
	$portfolio = new EepViewPortfolio( $args );

	return $portfolio->render();
}
add_shortcode( 'eep-portfolio', 'eep_portfolio_shortcode' );

/**
 * Load files needed for views
 * @note Can be filtered to add new classes as needed
 */
function eep_load_view_files() {

	$files = array(
		EEP_PLUGIN_DIR . '/views/Base.class.php' // This will load all default classes
	);

	$files = apply_filters( 'eep_load_view_files', $files );

	foreach( $files as $file ) {
		require_once( $file );
	}

}