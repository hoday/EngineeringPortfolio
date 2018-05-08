<?php
/**
 * Plugin Name:  Excellent Engineering Portfolio
 * Description:  Make a portfolio website for engineers
 * Version:      0.0.0
 * Author:       Hoday Stearns
 * Author URI:   https://github.com/hodayx
 * License:      GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  eep-portfolio
 */

if ( ! defined( 'ABSPATH' ) )
	exit;

class ExcellentEngineeringPortfolio {

	private $cpts 			= null; //@written
	public $styles 		= array(); // accessed by View.class.php //@unused

	/**
	 * Initialize the plugin and register hooks
	 */
	public function __construct() {

		// Common strings

		define( 'EEP_DOMAIN', 			'excellent-engineering-portfolio' ); //@unused
		define( 'EEP_PLUGIN_DIR', 	untrailingslashit( plugin_dir_path( __FILE__ ) ) ); //@used
		define( 'EEP_PLUGIN_URL', 	untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) ); //@used
		define( 'EEP_PLUGIN_FNAME', plugin_basename( __FILE__ ) ); //@unused
		define( 'EEP_TEMPLATE_DIR', 'eep-templates' ); //@unused
		define( 'EEP_VERSION', 			3 ); //@unused

		define( 'EEP_PROJECT_POST_TYPE', 				'project' ); //@unused
		define( 'EEP_PORTFOLIO_ITEM_POST_TYPE', 'portfolio_item' ); //@unused
		define( 'EEP_PUBLICATION_POST_TYPE', 		'publication' ); //@unused
		define( 'EEP_SKILL_POST_TYPE', 					'skill' ); //@unused

		// Load template functions
		require_once( EEP_PLUGIN_DIR . '/includes/template-functions.php' );

		// Loads plugin-specific styles
		add_action( 'init', array( $this, 'load_config' ) );

		// Loads translations
		add_action( 'init', array( $this, 'load_textdomain' ) );

        require_once( EEP_PLUGIN_DIR . '/includes/Configs.php' );
        $configs = new Configs();

		// Load custom post types
		require_once( EEP_PLUGIN_DIR . '/includes/CustomPostTypesRegistrationHandler.php' );
		$this->cpts = new CustomPostTypesRegistrationHandler($configs);
        $this->cpts->registerCallbacks();

        require_once( EEP_PLUGIN_DIR . '/includes/AdminRenderer.php' );
        (new AdminRenderer($configs))->registerCallbacks();

        // Call when the plugin is activated
		register_activation_hook( __FILE__, array( $this, 'activate_plugin' ) );



		// Load admin assets
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets') );

		// Register the widgets
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
/*
		// Order the menu items by menu order in the admin interface
		add_filter( 'pre_get_posts', array( $this, 'admin_order_posts' ) );
*/

		// Add links to plugin listing
		add_filter( 'plugin_action_links', array( $this, 'modify_plugin_action_links' ), 10, 2);







	}

    function activate_plugin() {
        error_log('register_activation_hook called');
        $this->cpts->rewrite_flush();
    }



  //@calledalways

	/**
	 * Load the plugin's configuration settings and default content
	 */
	public function load_config() {

		// Define supported styles
		eep_load_view_files();
		$this->styles = array( //@unused
			'base' => new EepStyle(
				array(
					'id'			=> 'base', //@unused
					'label'		=> __( 'Base style only', 'textdomain' ), //@unused
					'css'			=> array(
						'base' 			=> EEP_PLUGIN_URL . '/assets/css/base.css' //@unused
						),
					'js' 		=> array(), //@unused
				)
			),
			'default' => new EepStyle(
				array(
					'id'			=> 'default',
					'label'		=> __( 'Default style', 'textdomain' ),
					'css'			=> array(
						'base' 			=> EEP_PLUGIN_URL . '/assets/css/base.css',
						'default' 	=> EEP_PLUGIN_URL . '/assets/css/default.css',
						'bootstrap' => 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css'
						),
					'js' 			=> array(
						'jquery-js'		=> 'https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js',
						'bootstrap-js'=> 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js'
						),
				)
			),
			'none' => new EepStyle(
				array(
					'id'			=> 'none',
					'label'		=> __( 'Don\'t load any CSS styles', 'textdomain' ),
					'css'			=> array( ),
					'js' 			=> array( ),
				)

			),
		);
		$this->styles = apply_filters( 'eep_styles', $this->styles );

	}
//@calledalways
	/**
	 * Load the plugin textdomain for localistion
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'textdomain', false, EEP_PLUGIN_DIR . '/languages' );
	}




//@calledalways
	/**
	 * Enqueue the admin-only CSS and Javascript
	 */
	public function enqueue_admin_assets($hook) {

		global $post_type;

		if ( $post_type != EEP_PROJECT_POST_TYPE && $post_type != EEP_PORTFOLIO_ITEM_POST_TYPE && $post_type != EEP_PUBLICATION_POST_TYPE) {
			return;
		}

    //if ( 'edit.php' != $hook ) {
     //   return;
    //}

		// Register the script like this for a plugin:
		wp_register_script( 'eep-admin-script', EEP_PLUGIN_URL . '/assets/js/admin.js',  array( 'jquery' ) );

		// For either a plugin or a theme, you can then enqueue the script:
		wp_enqueue_script( 'eep-admin-script' );

		// register style
		wp_enqueue_style(  'eep-admin-style', 	EEP_PLUGIN_URL . '/assets/css/admin.css', array(), '1.5.2' );

		wp_localize_script(
			'eep-admin',
			'eepSettings',
			array(
				'nonce' => wp_create_nonce( 'eep-admin' ),
				'i18n' => array(
					'undefined_error' => esc_html__( 'An unexpected error occurred. Please reload the page and try again.', 'textdomain' ),
				)
			)
		);

	}

    //@alwayscalled
	/**
	 * Register the widgets
	 */
	public function register_widgets() {

		require_once( EEP_PLUGIN_DIR . '/widgets/WidgetPortfolio.class.php' );
		register_widget( 'eepWidgetPortfolio' );
		//require_once( EEP_PLUGIN_DIR . '/widgets/WidgetProjects.class.php' );
		//register_widget( 'eepWidgetProjects' );

	}

    //@calledalways
	/**
	 * Add links below the plugin listing on the installed plugins page
	 */
	public function modify_plugin_action_links( $links, $plugin ) {

		if ( $plugin == EEP_PLUGIN_FNAME ) {

			$links['help'] = '<a href="#" title="' . __( 'View the help documentation for Excellent Engineering Portfolio', 'textdomain' ) . '">' . __( 'Help', 'textdomain' ) . '</a>';
			$links['settings'] = '<a href="#" title="' . __( 'Edit settings for Excellent Engineering Portfolio', 'textdomain' ) . '">' . __( 'Settings', 'textdomain' ) . '</a>';

		}

		return $links;

	}









}

set_error_handler(function($severity, $message, $file, $line) {
		if (error_reporting() & $severity) {
				throw new ErrorException($message, 0, $severity, $file, $line);
		}
});

global $eep_controller;
$eep_controller = new ExcellentEngineeringPortfolio();

/*
template function
*/
function get_publication_href($post_id) {
	$HREF_TYPE_LINK = 1;
	$HREF_TYPE_FILE = 2;

	if ('' != get_post_meta($post_id, "publication_link", true)) {
		$has_href = true;
		$href_type = $HREF_TYPE_LINK;
		$href = get_post_meta($post_id, "publication_link", true);
	} else {
		$args = array(
			'post_type'      => 'attachment',
			'post_mime_type' => 'application/pdf',
			'post_parent'    => $post_id,
		);
		$attachments  		 = get_posts($args);
		if ($attachments != null && sizeof($attachments) > 0) {
			$publication_file_id = $attachments[0]->ID;
			$has_href = true;
			$href_type = $HREF_TYPE_FILE;
			$href = wp_get_attachment_url($publication_file_id);
		} else {
			$has_href = false;
			$href_type = '';
			$href = '';
		}
	}

	return array ($has_href, $href, $href_type);
}



// TODO:

/*

TODO:


make more widgets
make more shortcodes
make the base.css and classic.css styles display correctly
make help  link and setting link work on the plugin page
fix script localization
see why nonce is needed inside js
move the tmeplate function  get_publication_href
make css style actually selectable
make settings screen
checkwhy translation file not made

fix uploaded file not being saved
add nonce check to ajax handler
uncomment these from the constructor:
		// Order the menu items by menu order in the admin interface
		add_filter( 'pre_get_posts', array( $this, 'admin_order_posts' ) );

		// Append menu and menu item content to a post's $content variable
		add_filter( 'the_content', array( $this, 'append_to_content' ) );




make the links/downloads for the projects work
fix the resume
Make the patent display on the appropriate project single page
Make the skills display on portfolio items

FINISHED TODO:
use nonces in thea dmin panel
fix missing href problem
fix screen_icon deprecated problem
separate admin css and front css

*/
