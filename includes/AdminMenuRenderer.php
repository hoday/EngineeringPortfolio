<?php

namespace ElectrifyingEngineeringPortfolio;

require_once(EEPF_PLUGIN_DIR . '/includes/AdminPagesRenderer.php');
require_once(EEPF_PLUGIN_DIR . '/includes/SettingsAdminPageRenderer.php');

class AdminMenuRenderer {

    protected $configs = null;
    protected $adminPagesRenderer = null;
    protected $settingsAdminPageRenderer = null;

    public function __construct(
        $configs,
        $adminPagesRenderer,
        $settingsAdminPageRenderer
    ) {
        $this->configs = $configs;
        $this->adminPagesRenderer = $adminPagesRenderer;
        $this->settingsAdminPageRenderer = $settingsAdminPageRenderer;
    }


    public function registerCallbacks() {

        add_action('admin_menu', array($this, 'registerPluginMenuPage'));
        add_action('admin_menu', array($this, 'registerPluginSubmenuPages'));

        add_filter('parent_file', array($this, 'setParentFileForSkillTaxonomy'));
        add_filter('parent_file', array($this, 'setParentFileForEditCpt'));

        $this->adminPagesRenderer->registerCallbacks();
    }

    function setParentFileForSkillTaxonomy($parent_file) {
        global $self, $parent_file, $submenu_file, $plugin_page, $typenow;

        $eep_skill = $this->configs->custom_taxonomy_names['skill']['name'];

        if ($submenu_file == "edit-tags.php?taxonomy=$eep_skill") {
            $parent_file = $this->configs->eep_menu_name;
        }
        return $parent_file;
    }

    function setParentFileForEditCpt($parent_file) {
        global $self, $parent_file, $submenu_file, $plugin_page, $typenow;

        $queriesArray = array();
        foreach($this->configs->cpt_names as $cptName) {
            $queryString = "edit.php?post_type=$cptName";
            array_push($queriesArray, $queryString);
        }

        if (in_array($submenu_file, $queriesArray)) {
            $parent_file = $this->configs->eep_menu_name;
        }
        return $parent_file;
    }


    /*
	 * Register a custom menu page.
	 */
	public function registerPluginMenuPage() {

        if (current_user_can('edit_pages')) {

    		add_menu_page(
    				__('Engineering Portfolio', 'electrifying-engineering-portfolio'),
    				__('Engineering Portfolio', 'electrifying-engineering-portfolio'),
    				'manage_options',
    				$this->configs->eep_menu_name,
    				array(
                        $this->adminPagesRenderer,
                        'printPluginLandingSubmenuPage'
                    ),
    				'',
    				20
    		);

        }
    }

    public function registerPluginSubmenuPages() {

        if (current_user_can('edit_pages')) {

            add_submenu_page(
                $this->configs->eep_menu_name,
                $this->configs->cpt_labels['project']['all_items'],
                $this->configs->cpt_labels['project']['all_items'],
                'manage_options',
                'edit.php?post_type='.$this->configs->cpt_names['project']
            );

            add_submenu_page(
                $this->configs->eep_menu_name,
                $this->configs->cpt_labels['project']['add_new_item'],
                $this->configs->cpt_labels['project']['add_new_item'],
                'manage_options',
                'post-new.php?post_type='.$this->configs->cpt_names['project']
            );

            add_submenu_page(
                $this->configs->eep_menu_name,
                $this->configs->cpt_labels['portfolio_item']['all_items'],
                $this->configs->cpt_labels['portfolio_item']['all_items'],
                'manage_options',
                'edit.php?post_type='.$this->configs->cpt_names['portfolio_item']
            );

            add_submenu_page(
                $this->configs->eep_menu_name,
                $this->configs->cpt_labels['portfolio_item']['add_new_item'],
                $this->configs->cpt_labels['portfolio_item']['add_new_item'],
                'manage_options',
                'post-new.php?post_type='.$this->configs->cpt_names['portfolio_item']
            );

            add_submenu_page(
                $this->configs->eep_menu_name,
                $this->configs->cpt_labels['publication']['all_items'],
                $this->configs->cpt_labels['publication']['all_items'],
                'manage_options',
                'edit.php?post_type='.$this->configs->cpt_names['publication']
            );

            add_submenu_page(
                $this->configs->eep_menu_name,
                $this->configs->cpt_labels['publication']['add_new_item'],
                $this->configs->cpt_labels['publication']['add_new_item'],
                'manage_options',
                'post-new.php?post_type='.$this->configs->cpt_names['publication']
            );

            $taxonomy_data = $this->configs->custom_taxonomy_names['skill'];
            $cpt_names = $this->configs->cpt_names;

            // issue: clicking this submenu link collapses the submenu for this plugin
            // and causes the "posts" submenu to expand
    		add_submenu_page(
    			$this->configs->eep_menu_name,
                $this->configs->taxonomy_labels['skill']['name'],
                $this->configs->taxonomy_labels['skill']['name'],
    			'manage_options',
    			'edit-tags.php?taxonomy='.$taxonomy_data['name']
    		);

            add_submenu_page(
                $this->configs->eep_menu_name,
                __('Settings', 'electrifying-engineering-portfolio'),
                __('Settings', 'electrifying-engineering-portfolio'),
                'manage_options',
                'eep-settings-menu',
                array(
                    $this->settingsAdminPageRenderer,
                    'printPluginSettingsSubmenuPage'
                )
            );

        }



	}


}
