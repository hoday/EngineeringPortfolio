<?php
/**
 * Plugin Name:  Electrifying Engineering Portfolio
 * Plugin URI:   https://wordpress.org/plugins/electrifying-engineering-portfolio/
 * Description:  Creates a portfolio website for engineers
 * Version:      1.0.0
 * Author:       Hoday Stearns
 * Author URI:   https://profiles.wordpress.org/hodayx
 * License:      GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  electrifying-engineering-portfolio
 * Domain Path:  /languages
 *
 * Electrifying Engineering Portfolio is free software: you can redistribute
 * it and/or modify  the Free Software Foundation, either version 2 of the
 * License, or any later version.
 *
 * Electrifying Engineering Portfolio is distributed in the hope that it will
 * be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Electrifying Engineering Portfolio. If not, see
 * https://www.gnu.org/licenses/gpl-2.0.html.
 *
 * Includes FontAwesome icon
 * License:     CCY BY 4.0
 * License URI: https://fontawesome.com/license
 */

if ( ! defined( 'ABSPATH' ) )
	exit;

define('EEPF_PLUGIN_DIR',   untrailingslashit(plugin_dir_path( __FILE__ )));

define('EEPF_PLUGIN_URL',   untrailingslashit(plugins_url(
                                basename(plugin_dir_path(__FILE__)),
                                basename(__FILE__)
                            ))
);

if (!class_exists('\ElectrifyingEngineeringPortfolio\ElectrifyingEngineeringPortfolio')) {

    require_once(EEPF_PLUGIN_DIR . '/includes/ElectrifyingEngineeringPortfolio.php');

    $eepf = new \ElectrifyingEngineeringPortfolio\ElectrifyingEngineeringPortfolio();
    $eepf->init();

    // Call when the plugin is activated
    register_activation_hook(__FILE__, array($eepf, 'activatePlugin'));

    // Call when the plugin is deactivated
    register_deactivation_hook(__FILE__, array($eepf, 'deactivatePlugin'));
}
