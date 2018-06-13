<?php

namespace ElectrifyingEngineeringPortfolio;

require_once(EEPF_PLUGIN_DIR . '/includes/AdminMenuRenderer.php');
require_once(EEPF_PLUGIN_DIR . '/includes/AdminPagesRenderer.php');
require_once( EEPF_PLUGIN_DIR . '/includes/SettingsAdminPageRenderer.php' );
require_once( EEPF_PLUGIN_DIR . '/includes/SeeAllProjectsAdminPageRenderer.php' );

class AdminRenderer {

    protected $configs = null;
    protected $adminPagesRenderer  = null;
    protected $settingsAdminPageRenderer  = null;
    protected $adminMenuRenderer  = null;
    protected $seeAllProjectsAdminPageRenderer = null;

    function __construct($configs) {

        $this->configs = $configs;

        $this->adminPagesRenderer = new AdminPagesRenderer($this->configs);

        $this->settingsAdminPageRenderer = new SettingsAdminPageRenderer($this->configs);

        $this->adminMenuRenderer = new AdminMenuRenderer(
            $this->configs,
            $this->adminPagesRenderer,
            $this->settingsAdminPageRenderer
        );

        $this->seeAllProjectsAdminPageRenderer = new SeeAllProjectsAdminPageRenderer($this->configs);

    }

    function registerCallbacks(){

        // Register renderers for admin panel
        $this->adminPagesRenderer->registerCallbacks();

        $this->settingsAdminPageRenderer->registerCallbacks();

        $this->adminMenuRenderer->registerCallbacks();

        $this->seeAllProjectsAdminPageRenderer->registerCallbacks();
    }

}
