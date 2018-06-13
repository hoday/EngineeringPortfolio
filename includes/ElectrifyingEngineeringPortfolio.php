<?php

namespace ElectrifyingEngineeringPortfolio;

require_once(EEPF_PLUGIN_DIR . '/includes/Configs.php');
require_once(EEPF_PLUGIN_DIR . '/includes/CustomPostTypesRegistrationHandler.php');
require_once(EEPF_PLUGIN_DIR . '/includes/SettingsRegistrationHandler.php');
require_once(EEPF_PLUGIN_DIR . '/includes/AdminRenderer.php');
require_once(EEPF_PLUGIN_DIR . '/includes/FrontendRenderer.php');

class ElectrifyingEngineeringPortfolio {

	private $cpts 		        = null;
    private $settingsRegHandler = null;
    private $configs            = null;

	/**
	 * Initialize the plugin
	 */
	public function __construct() {

        $this->configs = new Configs();
        $this->cpts = new CustomPostTypesRegistrationHandler($this->configs);
        $this->settingsRegHandler = new SettingsRegistrationHandler($this->configs);

	}

    /**
	 * Register hooks
	 */
    public function init() {

        $this->cpts->registerCallbacks();

        if (is_admin()) {
            (new AdminRenderer($this->configs))->registerCallbacks();
        } else {
            (new FrontendRenderer($this->configs))->registerCallbacks();
        }


        // Loads translations
		add_action('init', array($this, 'loadTextdomain'));

		// Add links to plugin listing
		add_filter('plugin_action_links', array($this, 'modifyPluginActionLinks'), 10, 2);
    }

    function activatePlugin() {
        $this->cpts->registerCptsFlushRewrite();
        $this->cpts->registerCapabilities();
        $this->settingsRegHandler->registerSettings();
    }

    function deactivatePlugin() {
        $this->cpts->deregisterCptsFlushRewrite();
        $this->cpts->deregisterCapabilities();
    }

    function uninstallPlugin() {
        $this->settingsRegHandler->deleteSettings();
    }

	/**
	 * Load the plugin electrifying-engineering-portfolio for localistion
	 */
	public function loadTextdomain() {
		load_plugin_textdomain('electrifying-engineering-portfolio', false, EEPF_PLUGIN_DIR . '/languages' );
	}

	/**
	 * Add links below the plugin listing on the installed plugins page
	 */
	public function modifyPluginActionLinks($links, $plugin) {

        if (current_user_can('manage_options')) {
    		if ( $plugin == plugin_basename(__FILE__) ) {

                $href = site_url().'/wp-admin/admin.php?page=eep-settings-menu';
    			$links['settings'] = '<a href="'.$href.'" title="' . __( 'Edit settings for Electrifying Engineering Portfolio', 'electrifying-engineering-portfolio' ) . '">' . __( 'Settings', 'electrifying-engineering-portfolio' ) . '</a>';

    		}
        }

		return $links;

	}
}
