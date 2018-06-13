<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

define('EEPF_PLUGIN_DIR',   untrailingslashit(plugin_dir_path( __FILE__ )));

define('EEPF_PLUGIN_URL',   untrailingslashit(plugins_url(
                                basename(plugin_dir_path(__FILE__)),
                                basename(__FILE__)
                            ))
);

require_once(EEPF_PLUGIN_DIR . '/includes/ElectrifyingEngineeringPortfolio.php');

use \ElectrifyingEngineeringPortfolio\ElectrifyingEngineeringPortfolio;

(new ElectrifyingEngineeringPortfolio())->uninstallPlugin();
