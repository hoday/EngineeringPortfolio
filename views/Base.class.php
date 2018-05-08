<?php

/**
 * Base class
 *
 */

// Load library classes
require_once( EEP_PLUGIN_DIR . '/views/Style.class.php' );
require_once( EEP_PLUGIN_DIR . '/views/View.class.php' );
require_once( EEP_PLUGIN_DIR . '/views/View.Portfolio.class.php' );
require_once( EEP_PLUGIN_DIR . '/views/View.Project.class.php' );

abstract class EepBase {

	// Collect errors during processing
	protected $errors = array();


	/**
	 * Initialize the class
	 * @since 1.1
	 */
	public function __construct( $args ) {

		// Parse the values passed
		$this->parse_args( $args );

	}
	
	/**
	 * Parse the arguments passed in the construction and assign them to
	 * internal variables.
	 */
	final protected function parse_args( $args ) {
		foreach ( $args as $key => $val ) {
			$this->{$key} = $val;
		}
	}

	/**
	 * Set an error
	 */
	final protected function set_error( $error ) {
		$this->errors[] = array_merge(
			$error,
			array(
				'class'		=> get_class( $this ),
				'id'		=> $this->id,
				'backtrace'	=> debug_backtrace()
			)
		);
	}
	
}