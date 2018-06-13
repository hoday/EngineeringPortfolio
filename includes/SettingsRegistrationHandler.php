<?php

namespace ElectrifyingEngineeringPortfolio;


class SettingsRegistrationHandler {

    private $plugin_options_key = null;
    private $plugin_options_array_keys = null;
    private $plugin_options_array_defaults = null;

	public function __construct($configs) {

        $this->plugin_options_key = $configs->plugin_options_key;
        $this->plugin_options_array_keys =  $configs->plugin_options_array_keys;
        $this->plugin_options_array_defaults =  $configs->plugin_options_array_defaults;

    }

    public function registerSettings() {

        $optionsArray = array();
        foreach($this->plugin_options_array_keys as $handle => $key) {
            $value = $this->plugin_options_array_defaults[$handle];
            $optionsArray[$key] = $value;
        }

        add_option($this->plugin_options_key, $optionsArray);

    }

    public function deleteSettings() {

        delete_option($this->plugin_options_key);

    }

}
