<?php

namespace ElectrifyingEngineeringPortfolio;

require_once(EEPF_PLUGIN_DIR . '/includes/PortfolioModel.php');
require_once(EEPF_PLUGIN_DIR . '/views/admin/View.php');

class PortfolioRenderer {

    protected $configs = null;

    public function __construct($configs) {
        $this->configs = $configs;
    }

    public function registerCallbacks() {

        add_shortcode('eep-portfolio', array($this, 'eepPortfolioShortcode'));

    }

    function eepPortfolioShortcode($atts) {

        return $this->getPortfolioHtml();

    }

	/*
	 * This appends CPT custom fields to the_content so that it can be printed out in the theme
	*/
	public function getPortfolioHtml() {


            $model = new PortfolioModel($this->configs);
            $template = EEP_OVERRIDABLE_TEMPLATES_DIR . '/portfolio.php';

            ob_start();

            (new View(
                $this->configs,
                $model,
                $template
            ))->render();

            $custom_content = ob_get_clean();

            $content = $custom_content;

        return $content;

    }


}
