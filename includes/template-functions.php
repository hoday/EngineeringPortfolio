<?php
/**
 * Template tags and shortcodes
 */

/**
 * Create a shortcode to display the portfolio
 */
function eep_portfolio_shortcode( $atts ) {

	// Define shortcode attributes
	$portfolio_atts = array(
		'id' => null,
		'layout' => 'classic',
		'show_title' => false,
		'show_content' => false,
	);

	// Create filter so addons can modify the accepted attributes
	$portfolio_atts = apply_filters( 'eep_shortcode_portfolio_atts', $portfolio_atts );

	// Extract the shortcode attributes
	$args = shortcode_atts( $portfolio_atts, $atts );

	// Render portfolio
	eep_load_view_files();
	$portfolio = new EepViewPortfolio( $args );

	return $portfolio->render();
}
add_shortcode( 'eep-portfolio', 'eep_portfolio_shortcode' );

//@calledalways
/**
 * Load files needed for views
 * @note File list can be filtered to add new classes as needed
 */
function eep_load_view_files() {

	$files = array(
		EEP_PLUGIN_DIR . '/views/Base.class.php',
	);

	$files = apply_filters( 'eep_load_view_files', $files );

	foreach( $files as $file ) {
		require_once( $file );
	}

}
