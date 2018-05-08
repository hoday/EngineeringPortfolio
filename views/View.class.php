<?php

/**
 * Base class for any view requested on the front end.
 *
 * @since 1.1
 */
abstract class EepView extends EepBase {

	protected $layout = null; // template layout - but where is this actually set?

    protected $plugin_options_key = null;
    protected $plugin_options_array_keys = null;


    function __construct() {
        $this->plugin_options_key = 'eep_options';
        $this->plugin_options_array_keys = array(
            'eep_field_num_cols' => 'eep_field_num_cols',
            'eep_field_style'    => 'eep_field_style',
        );

    }

	/**
	 * Render the view
	 */
	abstract public function render();

	final protected function enqueue_assets() {
		error_log('enqueue assets eepview');

		global $eep_controller;

		$plugin_options = get_option($this->plugin_options_key);
		if ( $plugin_options['eep_field_style'] == 'none' ) {
			return;
		}

		$style_setting = $plugin_options['eep_field_style'];

        error_log("user selected ".$style_setting." style");

        //$eepStyle = $eep_controller->styles[$style_setting];
        if (array_key_exists($style_setting,  $eep_controller->styles)) {
            $eepStyle = $eep_controller->styles[$style_setting];
            $eepStyle->enqueue_assets();
            error_log("enqueued ".$style_setting." style");
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
