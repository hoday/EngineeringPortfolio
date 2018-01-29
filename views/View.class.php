<?php

/**
 * Base class for any view requested on the front end.
 *
 * @since 1.1
 */
abstract class EepView extends EepBase {
	
	protected $layout = null; // template layout - but where is this actually set?
	
	protected $style = null; // template stylesheet - but where is this actually set?
	
	abstract public function render();

	final protected function enqueue_assets() {

		global $eep_controller;
		
		$settings = get_option( 'excellent-engineering-portfolio-settings' );
		if ( $settings['eep-style'] == 'none' ) {
			return;
		}
		
		$this->style = 'default'; // set here temporarily. delete later!

		$enqueued = false;
		foreach ( $eep_controller->styles as $style ) {
			if ( $this->style == $style->id ) {
				$style->enqueue_assets();
				$enqueued = true;
				error_log("enqueued default style");
			}
		}
		
		// Fallback to basic style if the selected style does not exist
		// This can happen if they have a custom style defined in a theme, then
		// they switch themes. The setting will still be the custom style, but
		// no entry in $eep_controller->styles will exist for that style.
		if ( !$enqueued && isset( $eep_controller->styles['base'] ) ) {
			$eep_controller->styles['base']->enqueue_assets();
		}	
	
	}
	
	/**
	 * Load a template file for views
	 *
	 * First, it looks in the current theme's /eep-templates/ directory. Then it
	 * will check a parent theme's /eep-templates/ directory. If nothing is found
	 * there, it will retrieve the template from the plugin directory.

	 * @since 1.1
	 * @param string template Type of template to load (for example: portfolio)
	 */
	final protected function find_template( $template ) {

		$template_dirs = array(
			get_stylesheet_directory() . '/' . EEP_TEMPLATE_DIR . '/',
			get_template_directory() . '/' . EEP_TEMPLATE_DIR . '/',
			EEP_PLUGIN_DIR . '/' . EEP_TEMPLATE_DIR . '/'
		);
		
		$template_dirs = apply_filters( 'eep_template_directories', $template_dirs );

		if ( isset( $this->layout ) && $this->layout != 'classic' ) {
			$template .= '-' . $this->layout;
		}

		foreach ( $template_dirs as $dir ) {
			if ( file_exists( $dir . $template . '.php' ) ) {
				return $dir . $template . '.php';
			}
		}

		return false;
	}
	
}
