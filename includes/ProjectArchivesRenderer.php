<?php

namespace ElectrifyingEngineeringPortfolio;

require_once(EEPF_PLUGIN_DIR . '/includes/ProjectArchivesModel.php');
require_once(EEPF_PLUGIN_DIR . '/views/admin/View.php');
require_once(EEPF_PLUGIN_DIR . '/views/frontend/PortfolioView.php');

class ProjectArchivesRenderer {

    protected $configs = null;

    public function __construct($configs) {
        $this->configs = $configs;
    }

    public function registerCallbacks() {

        add_action('loop_start', array($this, 'accumulatePosts'));
        add_filter('the_title', array($this, 'eraseTitle'));
        add_filter('the_content', array($this, 'getProjectHtml'));
        add_filter('template_include', array($this, 'switchPageTemplate'));
    }


	/*
	 * This appends CPT custom fields to the_content so that it can be printed out in the theme
	*/
	public function getProjectHtml($content) {

        if (
            in_the_loop() &&
            is_main_query() &&
            is_post_type_archive($this->configs->cpt_names['project'])
        ) {
            error_log('the content project archives');

			// temporarily disable this filter to prevent infinite loop
			remove_filter('the_content', array($this, 'getProjectHtml'));

            $model = new ProjectArchivesModel($this->configs);
            //$template = EEP_FRONTEND_TEMPLATES_DIR . '/project.php';
            $template = EEP_OVERRIDABLE_TEMPLATES_DIR . '/project-archives.php';

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
            is_post_type_archive($this->configs->cpt_names['project'])
        ) {
            error_log('title erased project archives : '. $title);

            $title = '';
        }

        return $title;
    }

    function accumulatePosts($query) {

        if (
            !is_admin() &&
            is_main_query() &&
            is_post_type_archive($this->configs->cpt_names['project'])
        ) {

            error_log('loop start');
            error_log(print_r($query, true));
            $query->post_count = 0;
            $query->posts[0]->post_title = 'Projects';
            $query->posts[0]->ID = -1;

            $query->post->post_title = 'Projects';
            $query->post->ID = -1;

        }
    }


    function switchPageTemplate($template) {

        if (
            !is_admin() &&
            is_main_query() &&
            is_post_type_archive($this->configs->cpt_names['project'])
        ) {

            error_log('switching template project archives:');

    		$new_template = locate_template(array('page.php'));
    		if (!empty( $new_template)) {
    			return $new_template;
    		}
    	}

    	return $template;
    }

}
