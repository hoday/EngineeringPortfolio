<?php

namespace ElectrifyingEngineeringPortfolio;

class SeeAllProjectsAdminPageRenderer {

    protected $configs = null;

    function __construct($configs) {

        $this->configs = $configs;
    }


    public function registerCallbacks() {

        $cpt_names = array(
            $this->configs->cpt_names['project'],
            $this->configs->cpt_names['publication'],
            $this->configs->cpt_names['portfolio_item'],
        );

        foreach ($cpt_names as $cpt_name){
            // Add featured post column in admin panel for project custom post type
            add_filter('manage_'.$cpt_name.'_posts_columns',
                array( $this, 'managePostsColumnsCallbackFeatured')
            );

            add_action('manage_'.$cpt_name.'_posts_custom_column',
                array( $this, 'managePostsCustomColumnsCallbackFeatured'),
                10,
                2
            );
        }

        add_action('pre_get_posts', array($this, 'rearrangeCptOrder'));

        add_action('quick_edit_custom_box', array($this, 'renderQuickEditFeatured'), 10, 2);

        add_action('admin_enqueue_scripts', array($this, 'enqueueScriptForQuickEdit'));

    }


    // Add featured post column in admin panel
	public function managePostsColumnsCallbackFeatured($columns) {
		// adds a column with the identifier 'featured' and display string 'Featured'.
		array_splice($columns, sizeof($columns) - 1, 0, array('featured' => __('Featured')));

		return $columns;

	}

	// Add featured post column in admin panel
	public function managePostsCustomColumnsCallbackFeatured($column_name, $post_ID) {
		if ($column_name == 'featured') {
			$featured = get_post_meta($post_ID, "eep_featured", true);
			if ($featured) {
				echo 'Featured';
			}
		}
	}

    function renderQuickEditFeatured($column_name, $post_type) {
        if ($column_name == 'featured') {
            include(EEP_ADMIN_TEMPLATES_DIR . '/admin-quickedit-featured.php');
        }
    }

    function enqueueScriptForQuickEdit($hook) {

        $cptNamesArray = array_values($this->configs->cpt_names);

        if (
            'edit.php' === $hook &&
    		isset($_GET['post_type']) &&
    		in_array($_GET['post_type'], $cptNamesArray)
        ) {

    		wp_enqueue_script(
                'eep-quickedit',
                EEPF_PLUGIN_URL . '/assets/js/admin_quickedit.js',
                array('jquery'),
    			false,
                true
            );
    	}
    }

    public function rearrangeCptOrder($query) {
        if (is_admin() && $query->is_main_query()) {
            $postType = $query->get('post_type');
            if (in_array($postType, array_values($this->configs->cpt_names))) {
                error_log('rearrange order');
                $query->set('orderby', 'menu_order');
                $query->set('order', 'DESC');
            }
        }
    }

}
