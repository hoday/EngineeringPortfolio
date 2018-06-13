<?php

namespace ElectrifyingEngineeringPortfolio;

require_once(EEPF_PLUGIN_DIR . '/includes/PortfolioRenderer.php');
require_once(EEPF_PLUGIN_DIR . '/includes/ProjectRenderer.php');
require_once(EEPF_PLUGIN_DIR . '/includes/ProjectArchivesRenderer.php');
require_once(EEPF_PLUGIN_DIR . '/includes/PortfolioItemArchivesRenderer.php');

const EEP_FRONTEND_TEMPLATES_DIR = EEPF_PLUGIN_DIR . '/views/frontend/templates';
const EEP_OVERRIDABLE_TEMPLATES_DIR = EEPF_PLUGIN_DIR . '/eep-templates';

class FrontendRenderer {

    protected $configs = null;
    protected $portfolioRenderer  = null;
    protected $projectRenderer  = null;
    protected $projectArchivesRenderer  = null;
    protected $portfolioItemArchivesRenderer  = null;


    function __construct($configs) {

        $this->configs = $configs;

        $this->portfolioRenderer = new PortfolioRenderer($this->configs);
        $this->projectRenderer = new ProjectRenderer($this->configs);
        $this->projectArchivesRenderer = new ProjectArchivesRenderer($this->configs);
        $this->portfolioItemArchivesRenderer = new PortfolioItemArchivesRenderer($this->configs);

    }

    function registerCallbacks(){

        $this->portfolioRenderer->registerCallbacks();
        $this->projectRenderer->registerCallbacks();
        $this->projectArchivesRenderer->registerCallbacks();
        $this->portfolioItemArchivesRenderer->registerCallbacks();

        add_action('wp_enqueue_scripts', array($this, 'enqueueAssetsTemporary'));
        add_action('init', array($this, 'registerThumbnailSizes'));

    }

    function enqueueAssetsTemporary() {

        // load number of columns user setting
        $options = get_option($this->configs->plugin_options_key);
        if ($options) {
            $key = $this->configs->plugin_options_array_keys['eep_field_style'];
            $selectedStyle = $options[$key];
        } else {
            $selectedStyle = 'base';
        }

        // load the selected style
        $selectedStyleInfo = $this->configs->styleDefs[$selectedStyle];

        foreach($selectedStyleInfo['srcs'] as $src) {

            // create handle
            $filenameNoExt = pathinfo($src)['filename'];
            $handle = 'eep-'.$filenameNoExt;

            wp_enqueue_style($handle, $src);
        }

    }



    function registerThumbnailSizes() {
        $thumbnail_settings = $this->configs->thumbnail_settings;
        foreach ($thumbnail_settings as $thumbnail_setting) {
            $name = $thumbnail_setting['name'];
            $width = $thumbnail_setting['width'];
            $height = $thumbnail_setting['height'];
            add_image_size($name, $width, $height, ['center', 'center']);
        }
    }

}
