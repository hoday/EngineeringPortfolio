<?php

namespace ElectrifyingEngineeringPortfolio;

require_once(EEPF_PLUGIN_DIR . '/includes/ProjectModel.php');
require_once(EEPF_PLUGIN_DIR . '/views/admin/View.php');
require_once(EEPF_PLUGIN_DIR . '/views/frontend/PortfolioView.php');


class ProjectRenderer {

    protected $configs = null;

    public function __construct($configs) {
        $this->configs = $configs;
    }

    public function registerCallbacks() {

        add_filter('the_content', array($this, 'getProjectHtml'));

        add_filter('the_title', array($this, 'eraseTitle'));

        add_filter('template_include', array($this, 'switcPageTemplate'));

    }


	/*
	 * This appends CPT custom fields to the_content so that it can be printed out in the theme
	*/
	public function getProjectHtml($content) {

        if (
            in_the_loop() &&
            is_main_query() &&
            is_singular($this->configs->cpt_names['project'])
        ) {
            error_log('the content project');

			// temporarily disable this filter to prevent infinite loop
			remove_filter('the_content', array($this, 'getProjectHtml'));

            $model = new ProjectModel($this->configs, get_the_ID());
            //$template = EEP_FRONTEND_TEMPLATES_DIR . '/project.php';
            $template = EEP_OVERRIDABLE_TEMPLATES_DIR . '/project.php';

            ob_start();


            (new PortfolioView(
                $this->configs,
                $model,
                $template
            ))->render();

            $custom_content = ob_get_clean();


            $content = $custom_content;

			// reenable this filter
			add_filter('the_content', array($this, 'getProjectHtml'));

        }

        return $content;

    }

    public function eraseTitle($title) {

        if (
            in_the_loop() &&
            is_main_query() &&
            (
                is_singular($this->configs->cpt_names['project'])
            )
        ) {
            error_log('title erased project : '. $title);

            $title = '';
        }

        return $title;
    }

    function switcPageTemplate($template) {

        if (
            !is_admin() &&
            is_main_query() &&
            is_singular() &&
            get_post_type() == ($this->configs->cpt_names['project'])
        ) {
            error_log('switching template project');
    		$new_template = locate_template(array('page.php'));
    		if (!empty( $new_template)) {
    			return $new_template;
    		}
    	}

    	return $template;
    }


}
