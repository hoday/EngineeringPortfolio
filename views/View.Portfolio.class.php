<?php

/**
 * Class for any portfolio view requested on the front end.
 *
 * @since 1.1
 */
class EepViewPortfolio extends EepView {

	

	/**
	 * Render the view and enqueue required stylesheets
	 */
	public function render() {
		
		$this->get_portfolio_post();

		// Add any dependent stylesheets or javascript
		$this->enqueue_assets();

		// Add css classes to the menu list
		$this->classes = $this->portfolio_classes();

		ob_start();
		$template = $this->find_template( 'portfolio' );
		if ( $template ) {
			include( $template );
		}
		$output = ob_get_clean();

		return apply_filters( 'eep_portfolio_output', $output, $this );		
		
	}
	
	private function get_portfolio_post() {
		return null;
	}
	
	private function portfolio_classes() {
		return null;
	}

}
