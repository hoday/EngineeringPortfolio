<?php

namespace ElectrifyingEngineeringPortfolio;

class PortfolioView {

    protected $configs;
    protected $model;
    protected $template;

    function __construct($configs, $model, $template) {
        $this->configs = $configs;
        $this->model = $model;
        $this->template = $template;
    }

    function render() {

    	ob_start();
    	$template = $this->template;
    	if ( $template ) {
    		include( $template );
    	}
    	$output = ob_get_clean();

        echo $output;

    }
}
