<?php
/**
 * Plugin Name:  Excellent Engineering Portfolio
 * Description:  Make a portfolio website for engineers
 * Version:      0.0.0
 * Author:       Hoday Stearns
 * Author URI:   https://github.com/hodayx
 * License:      GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 
 * Text Domain:  ee-portfolio
 * Domain Path:  /languages/
 */
 
if ( ! defined( 'ABSPATH' ) )
	exit;

class ExcellentEngineeringPortfolio {
	
	/**
	 * Initialize the plugin and register hooks
	 */
	public function __construct() {
		// Common strings
		
		define( 'EEP_DOMAIN', 'food-and-drink-menu' );
		define( 'EEP_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'EEP_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
		define( 'EEP_PLUGIN_FNAME', plugin_basename( __FILE__ ) );
		define( 'EEP_TEMPLATE_DIR', 'fdm-templates' );
		define( 'EEP_VERSION', 3 );
		define( 'EEP_MENU_POST_TYPE', 'fdm-menu' );
		define( 'EEP_MENUITEM_POST_TYPE', 'fdm-menu-item' );

		// Load template functions
		//require_once( EEP_PLUGIN_DIR . '/includes/template-functions.php' );

		// Call when plugin is initialized on every page load
		//add_action( 'init', array( $this, 'load_config' ) );
		//add_action( 'init', array( $this, 'load_textdomain' ) );

		// Load custom post types
		//require_once( EEP_PLUGIN_DIR . '/includes/class-custom-post-types.php' );
		//$this->cpts = new fdmCustomPostTypes();

		// Load settings
		//require_once( EEP_PLUGIN_DIR . '/includes/class-settings.php' );
		//$this->settings = new fdmSettings();

		// Load integrations with other plugins
		//require_once( EEP_PLUGIN_DIR . '/includes/integrations/business-profile.php' );
		//require_once( EEP_PLUGIN_DIR . '/includes/integrations/wordpress-seo.php' );

		// Load compatibility sections
		//require_once( EEP_PLUGIN_DIR . '/includes/compatibility.php' );

		// Call when the plugin is activated
		//register_activation_hook( __FILE__, array( $this, 'rewrite_flush' ) );

		/*
		// Load admin assets
		add_action( 'admin_print_scripts-post-new.php', array( $this, 'enqueue_admin_assets' ) );
		add_action( 'admin_print_scripts-post.php', array( $this, 'enqueue_admin_assets' ) );
		add_action( 'admin_print_scripts-edit.php', array( $this, 'enqueue_admin_assets' ) );

		// Register the widget
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );

		// Order the menu items by menu order in the admin interface
		add_filter( 'pre_get_posts', array( $this, 'admin_order_posts' ) );

		// Append menu and menu item content to a post's $content variable
		add_filter( 'the_content', array( $this, 'append_to_content' ) );

		// Add links to plugin listing
		add_filter( 'plugin_action_links', array( $this, 'plugin_action_links' ), 10, 2);

		// Backwards compatibility for new taxonomy term splitting
		// in 4.2
		// https://make.wordpress.org/core/2015/02/16/taxonomy-term-splitting-in-4-2-a-developer-guide/
		add_action( 'split_shared_term', array( $this, 'compat_split_shared_term' ), 10, 4 );
	  */
		
	add_action( 'admin_menu', 'register_custom_menu_page' );

	add_action( 'init', array( $this, 'create_post_type_portfolio_item'), 40 );
	add_action( 'init', array( $this, 'create_post_type_publications'),   30 );
	add_action( 'init', array( $this, 'create_post_type_project'),        20 );
	add_action( 'init', array( $this, 'create_skill_taxonomy'),           50 );
	add_action( 'init', array( $this, 'create_publication_type_taxonomy'),60 );
	add_action( 'admin_menu', array( $this, 'add_taxonomy_submenu_page') );
	add_action("add_meta_boxes", array( $this, "add_meta_boxes_callback_project"));
	add_action("add_meta_boxes", array( $this, "add_meta_boxes_callback_publication"));
	add_action("add_meta_boxes", array( $this, "add_meta_boxes_callback_portfolio_item"));
	add_action("add_meta_boxes", array( $this, "add_meta_boxes_callback_featured"));

	add_action('post_updated', array( $this, 'update_post_callback_custom_post_type_project'), 10, 2);

	add_action('post_updated', array( $this, 'update_post_callback_custom_post_type_publication'), 10, 2);
	
	add_action('post_updated', array( $this, 'update_post_callback_custom_post_type_portfolio_item'), 10, 2);
	
	add_action('post_updated', array( $this, 'update_post_callback_featured'), 10, 3);
	
	add_action( 'admin_enqueue_scripts', array( $this, 'register_plugin_scripts') );
	
	/*
		The function below is the ajax handler. The first and second argument strings strings must match!!
	*/
	add_action('wp_ajax_nopriv_publication_add_new_action', array( $this, 'publication_add_new_action'));
	add_action('wp_ajax_publication_add_new_action', 				array( $this, 'publication_add_new_action'));

	add_action('wp_ajax_nopriv_publication_add_existing_action', array( $this, 'publication_add_existing_action'));
	add_action('wp_ajax_publication_add_existing_action', 			 array( $this, 'publication_add_existing_action'));

	add_action('wp_ajax_nopriv_publication_remove_action', array( $this, 'publication_remove_action'));
	add_action('wp_ajax_publication_remove_action', 			 array( $this, 'publication_remove_action'));

	// Add featured post column in admin panel for project custom post type
	add_filter('manage_project_posts_columns', array( $this, 'manage_posts_columns_callback_featured'));
	add_action('manage_project_posts_custom_column', array( $this, 'manage_posts_custom_columns_callback_featured'), 10, 2);
	// Add featured post column in admin panel for publication custom post type
	add_filter('manage_publication_posts_columns', array( $this, 'manage_posts_columns_callback_featured'));
	add_action('manage_publication_posts_custom_column', array( $this, 'manage_posts_custom_columns_callback_featured'), 10, 2);
	// Add featured post column in admin panel for portfolio_item custom post type
	add_filter('manage_portfolio_item_posts_columns', array( $this, 'manage_posts_columns_callback_featured'));
	add_action('manage_portfolio_item_posts_custom_column', array( $this, 'manage_posts_custom_columns_callback_featured'), 10, 2);

	add_action('post_edit_form_tag', array( $this, 'post_edit_form_tag_callback_multipart_encoding'));


	add_action('update_post',array( $this, 'update_post_callback_publication_upload_file'), 10, 2);

	
	
	}	
	
	



	/*
	 * Register a custom menu page.
	 */
	public function register_custom_menu_page() {
		add_menu_page(
				__('Engineering Portfolio'),
				__('Engineering Portfolio'),				
				'manage_options',
				'engineering-portfolio',
				'',
				'',
				20
		);
	}

	/*
		enables 'project' custom post type
	*/
	public function create_post_type_project() {
		$labels = array(
			'name'                       => _x( 'Projects', 'taxonomy general name', 'textdomain' ),
			'singular_name'              => _x( 'Project', 'taxonomy singular name', 'textdomain' ),
			'search_items'               => __( 'Search Projects', 'textdomain' ),
			'popular_items'              => __( 'Popular Projects', 'textdomain' ),
			'all_items'                  => __( 'All Projects', 'textdomain' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Project', 'textdomain' ),
			'update_item'                => __( 'Update Project', 'textdomain' ),
			'add_new_item'               => __( 'Add New Project', 'textdomain' ),
			'new_item_name'              => __( 'New Project Name', 'textdomain' ),
			'separate_items_with_commas' => __( 'Separate projects with commas', 'textdomain' ),
			'add_or_remove_items'        => __( 'Add or remove projects', 'textdomain' ),
			'choose_from_most_used'      => __( 'Choose from the most used projects', 'textdomain' ),
			'not_found'                  => __( 'No projects found.', 'textdomain' ),
			'menu_name'                  => __( 'Projects', 'textdomain' ),
		);
		
		$args = array(
			'labels' 					=> $labels,
			'public' 					=> true,
			'has_archive' 		=> true,
			'supports'        => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', ),
			'show_in_menu'		=> 'engineering-portfolio',
			);
		register_post_type('project', $args);	
			

	}
	/*
		enables 'publication' custom post type
	*/
	public function create_post_type_publications() {
		$labels = array(
			'name'                       => _x( 'Publications', 'taxonomy general name', 'textdomain' ),
			'singular_name'              => _x( 'Publication', 'taxonomy singular name', 'textdomain' ),
			'search_items'               => __( 'Search Publications', 'textdomain' ),
			'popular_items'              => __( 'Popular Publications', 'textdomain' ),
			'all_items'                  => __( 'All Publications', 'textdomain' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Publication', 'textdomain' ),
			'update_item'                => __( 'Update Publication', 'textdomain' ),
			'add_new_item'               => __( 'Add New Publication', 'textdomain' ),
			'new_item_name'              => __( 'New Publication Name', 'textdomain' ),
			'separate_items_with_commas' => __( 'Separate publications with commas', 'textdomain' ),
			'add_or_remove_items'        => __( 'Add or remove publications', 'textdomain' ),
			'choose_from_most_used'      => __( 'Choose from the most used publications', 'textdomain' ),
			'not_found'                  => __( 'No publications found.', 'textdomain' ),
			'menu_name'                  => __( 'Publications', 'textdomain' ),
		);
		
		$args = array(
			'labels' 					=> $labels,
			'public' 					=> true,
			'has_archive' 		=> true,
			'supports'        => array('title'),
			'show_in_menu'		=> 'engineering-portfolio',
			);

		register_post_type('publication', $args);	
	}
	/*
		enables 'portfolioitem' custom post type
	*/
	public function create_post_type_portfolio_item() {
		$labels = array(
			'name'                       => _x( 'Portfolio Items', 'taxonomy general name', 'textdomain' ),
			'singular_name'              => _x( 'Portfolio Item', 'taxonomy singular name', 'textdomain' ),
			'search_items'               => __( 'Search Portfolio Items', 'textdomain' ),
			'popular_items'              => __( 'Popular Portfolio Items', 'textdomain' ),
			'all_items'                  => __( 'All Portfolio Items', 'textdomain' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Portfolio Item', 'textdomain' ),
			'update_item'                => __( 'Update Portfolio Item', 'textdomain' ),
			'add_new_item'               => __( 'Add New Portfolio Item', 'textdomain' ),
			'new_item_name'              => __( 'New Portfolio Item Name', 'textdomain' ),
			'separate_items_with_commas' => __( 'Separate portfolio items with commas', 'textdomain' ),
			'add_or_remove_items'        => __( 'Add or remove portfolio items', 'textdomain' ),
			'choose_from_most_used'      => __( 'Choose from the most used portfolio items', 'textdomain' ),
			'not_found'                  => __( 'No portfolio items found.', 'textdomain' ),
			'menu_name'                  => __( 'Portfolio Items', 'textdomain' ),
		);
		
		$args = array(
			'labels' 					=> $labels,
			'public' 					=> true,
			'has_archive' 		=> true,
			'supports'        => array('title', 'thumbnail',),
			'show_in_menu'		=> 'engineering-portfolio',
			);

		register_post_type('portfolio_item', $args);
	}


	/*
		enables 'skill' taxonomy
	*/
	public function create_skill_taxonomy() {
		
		$labels = array(
			'name'                       => _x( 'Skills', 'taxonomy general name', 'textdomain' ),
			'singular_name'              => _x( 'Skill', 'taxonomy singular name', 'textdomain' ),
			'search_items'               => __( 'Search Skills', 'textdomain' ),
			'popular_items'              => __( 'Popular Skills', 'textdomain' ),
			'all_items'                  => __( 'All Skills', 'textdomain' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Skill', 'textdomain' ),
			'update_item'                => __( 'Update Skill', 'textdomain' ),
			'add_new_item'               => __( 'Add New Skill', 'textdomain' ),
			'new_item_name'              => __( 'New Skill Name', 'textdomain' ),
			'separate_items_with_commas' => __( 'Separate skills with commas', 'textdomain' ),
			'add_or_remove_items'        => __( 'Add or remove skills', 'textdomain' ),
			'choose_from_most_used'      => __( 'Choose from the most used skills', 'textdomain' ),
			'not_found'                  => __( 'No skills found.', 'textdomain' ),
			'menu_name'                  => __( 'Skills', 'textdomain' ),
		);

		$args = array(
			'labels'                => $labels,
			'public'								=> true,
			'publicly_queryable'		=> true,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'show_in_quick_edit'    => true,		
			'query_var'             => true,
			'hierarchical'      		=> true,
			'show_in_menu'					=> 'engineering-portfolio',
			);

			register_taxonomy( 'skill', array('project', 'portfolio_item'), $args );


	}

	/*
		enables 'publication_type' taxonomy
	*/
	public function create_publication_type_taxonomy() {
		
		$labels = array(
			'name'                       => _x( 'Publication Types', 'taxonomy general name', 'textdomain' ),
			'singular_name'              => _x( 'Publication Type', 'taxonomy singular name', 'textdomain' ),
			'search_items'               => __( 'Search Publication Types', 'textdomain' ),
			'popular_items'              => __( 'Popular Publication Types', 'textdomain' ),
			'all_items'                  => __( 'All Publication Types', 'textdomain' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Publication Type', 'textdomain' ),
			'update_item'                => __( 'Update Publication Type', 'textdomain' ),
			'add_new_item'               => __( 'Add New Publication Type', 'textdomain' ),
			'new_item_name'              => __( 'New Publication Type Name', 'textdomain' ),
			'separate_items_with_commas' => __( 'Separate Publication Types with commas', 'textdomain' ),
			'add_or_remove_items'        => __( 'Add or remove Publication Types', 'textdomain' ),
			'choose_from_most_used'      => __( 'Choose from the most used Publication Types', 'textdomain' ),
			'not_found'                  => __( 'No Publication Types found.', 'textdomain' ),
			'menu_name'                  => __( 'Publication Types', 'textdomain' ),
		);

		$args = array(
			'labels'                => $labels,
			'public'								=> true,
			'publicly_queryable'		=> true,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'show_in_quick_edit'    => true,		
			'query_var'             => true,
			'hierarchical'      		=> true,
			//'show_in_menu'					=> 'engineering-portfolio',
			);

			register_taxonomy( 'publication_type', array('publication'), $args );

			wp_insert_term('patent', 'publication_type', array('description' => 'Patents'));
			wp_insert_term('paper',  'publication_type', array('description' => 'Papers'));

			$term_id_patent = get_term_by('name', 'patent', 'publication_type')->term_id;
			$term_id_paper  = get_term_by('name', 'paper',  'publication_type')->term_id;
			
			wp_update_term($term_id_patent, 'publication_type', array('description' => 'Patents'));
			wp_update_term($term_id_paper,  'publication_type', array('description' => 'Papers'));		
	}

	public function add_taxonomy_submenu_page() {
		add_submenu_page('engineering-portfolio', __('Skills'), __('Skills'), 'manage_options', 'edit-tags.php?taxonomy=skill', null );	
	}
	

	/*
		adds custom metabox (area with input fields) for the admin page for the 'project' custom post type
	*/ 
	public function add_meta_boxes_callback_project(){
		// parameters:
		// add_meta_box(id, title, callback function, page in admin area to add the metabox to, area on page to add it, priority, callback function arguments);
		add_meta_box("project_meta_box_project_details", "Project details", "add_meta_box_callback_render_project_details", "project", "normal", "low");
		//add_meta_box("publication_meta_new", "Publication details new", "publication_meta_render_metabox_callback", "project", "normal", "low");
		//add_meta_box("publication_meta_existing", "Publication details existing", "project_meta_add_existing_publication_render_metabox", "project", "normal", "low");
		add_meta_box("project_meta_box_related_publications", "Related Publications", "add_meta_box_callback_render_related_publications", "project", "normal", "low");
	}
	
		


	/*
		adds custom metabox (area with input fields) for the admin page for the 'publication' custom post type
	*/ 
	public function add_meta_boxes_callback_publication(){
		// parameters:
		// add_meta_box(id, title, callback function, page in admin area to add the metabox to, area on page to add it, priority, callback function arguments);
		add_meta_box("publication_meta", "Publication details", "add_meta_box_callback_render_publication", "publication", "normal", "low");
	}

	/*
		adds custom metabox (area with input fields) for the admin page for the 'portfolio_item' custom post type
	*/ 
	public function add_meta_boxes_callback_portfolio_item(){
		// parameters:
		// add_meta_box(id, title, callback function, page in admin area to add the metabox to, area on page to add it, priority, callback function arguments);
		add_meta_box("portfolio_item_meta", "Portfolio Item details", "add_meta_box_callback_render_portfolio_item", "portfolio_item", "normal", "low");
	}
	


	/*
		adds custom metabox (area with input fields) for the admin page for all custom post types
	*/ 
	public function add_meta_boxes_callback_featured(){
		add_meta_box("featured_meta", "Feature on front page", "add_meta_box_callback_render_featured", "project", "side", "low");
		add_meta_box("featured_meta", "Feature on front page", "add_meta_box_callback_render_featured", "publication", "side", "low");
		add_meta_box("featured_meta", "Feature on front page", "add_meta_box_callback_render_featured", "portfolio_item", "side", "low");
	}
	


	 /*
		 Renders the metabox contents for the 'Project details' metabox for the admin page for 'project' custom post type
	 */
	public function add_meta_box_callback_render_project_details($post, $args) {
		/* The below will populate it with the existing database entry to edit existing content*/
		if ($post != null) {
			$project_title 							= get_post_meta($post->ID, "project_title", true);
			$project_subtitle 					= get_post_meta($post->ID, "project_subtitle", true);
			$project_credits    				= get_post_meta($post->ID, "project_credits",    true);
		} else {
			$project_title 							= '';
			$project_subtitle 					= '';
			$project_credits    				= '';	
		}	
		?>
		<style>
		.full-width {
			width:100%;
		}
		</style>	
		<p>
			<label>Title:</label><br />
			<input name="project_title" value="<?php echo $project_title; ?>" class="full-width">
		</p>
		<p>
			<label>Subtitle:</label><br />
			<input name="project_subtitle" value="<?php echo $project_subtitle; ?>" class="full-width">
		</p>
		<p>
			<label>Credits:</label><br />
			<textarea cols="50" rows="5" name="project_credits" class="full-width"><?php echo $project_credits; ?></textarea>
		</p>
		<?php
	}

	 /*
		 Renders the metabox contents for the 'Publication details' metabox for the admin page for 'publication' custom post type
	 */
	public function add_meta_box_callback_render_publication($post, $args) {
		/* The below will populate it with the existing database entry to edit existing content*/
		if ($post != null) {
			$publication_authors = get_post_meta($post->ID, "publication_authors", true);
			$publication_details = get_post_meta($post->ID, "publication_details", true);
			$publication_link    = get_post_meta($post->ID, "publication_link",    true);
			$args = array(
							'post_type'      => 'attachment',
							'post_mime_type' => 'application/pdf',
							'post_parent'    => $post->ID,
					);
			$attachments  		 = get_posts($args);
			if ($attachments != null && sizeof($attachments) > 0) {
				$publication_file_id = $attachments[0]->ID;	
				$publication_file    = get_the_title($publication_file_id);
			} else {
				$publication_file_id = '';
				$publication_file    = '';
			}
		} else {
			$publication_authors = '';
			$publication_details = '';
			$publication_link    = '';
			$publication_file_id = '';	
			$publication_file    = '';	

			
		}

		?>
		<style>
		.full-width {
			width:100%;
		}
		</style>
		<p>
			<label>Authors:</label><br />
			<input name="publication_authors" value="<?php echo $publication_authors; ?>" class="full-width">
		</p>
		<p>
			<label>Details:</label><br />
			<input name="publication_details" value="<?php echo $publication_details; ?>" class="full-width">
		</p>
		<p>
			<label>Link:</label><br />
			<input name="publication_link" value="<?php echo $publication_link; ?>" class="full-width">
		</p>
		<p>
			<label>File:</label><br />
			<?php if ($publication_file_id != '') : ?>
				<a href="<?php echo wp_get_attachment_url($publication_file_id); ?>"><?php echo $publication_file; ?></a><br />
			<?php endif; ?>
			<input name="publication_file" type="file" id="publication_file">
		</p>
		<?php
	}

	/*
		 Renders the metabox contents for the 'Portfolio item details' metabox for the admin page for 'portfolio_item' custom post type
	*/
	public function add_meta_box_callback_render_portfolio_item($post, $args) {
		/* The below will populate it with the existing database entry to edit existing content*/
		if ($post != null) {
			$portfolio_item_details = get_post_meta($post->ID, "portfolio_item_details", true);
			$portfolio_item_link    = get_post_meta($post->ID, "portfolio_item_link",    true);
		} else {
			$portfolio_item_details = '';
			$portfolio_item_link    = '';
		}
		?>
		<style>
		.full-width {
			width:100%;
		}
		</style>
		<p>
			<label>Details:</label><br />
			<input name="portfolio_item_details" value="<?php echo $portfolio_item_details; ?>" class="full-width">
		</p>
		<p>
			<label>Link:</label><br />
			<input name="portfolio_item_link" value="<?php echo $portfolio_item_link; ?>" class="full-width">
		</p>
		<?php
	}

	public function add_meta_box_callback_render_featured($post, $args) {
		$DEFAULT = 1;
		if ($post != null) {
			$featured = get_post_meta($post->ID, "featured", true);
			if ($featured == '') { $featured = $DEFAULT; }
		} else {
			$featured = $DEFAULT;
		}
		?>	
		<p>
			<label>Feature on front page:</label><br />
			<input id="checkBox" name="featured" type="checkbox" <?php if ($featured) { echo "checked"; }?>>
		</p>
		<?php	
	}


	/*
	*/
	public function add_meta_box_callback_render_related_publications_for_project($post) {
		$custom = get_post_custom($post->ID);
	$publication_id = 	$custom->id;
		publication_meta_render_metabox_callback_populate($publication_id)	;
	}

	/*
	Not used
	*/
	/*
	function project_meta_add_existing_publication_render_metabox($post, $args) {
			wp_nonce_field( plugin_basename( __FILE__ ), 'p2p2_project_publication_nonce' );
			$publication_id = get_post_meta($post->ID, 'p2p2_project_publication', true);

			echo "<p>Select the existing publication to add</p>";
			echo "<select id='p2p2_project_publication' name='p2p2_project_publication'>";
			// Query the publications here
			$query = new WP_Query( 'post_type=publication' );
			while ( $query->have_posts() ) {
					$query->the_post();
					$id = get_the_ID();
					$selected = "";

					if($id == $publication_id){
							$selected = ' selected="selected"';
					}
					echo '<option' . $selected . ' value=' . $id . '>' . get_the_title() . '</option>';
			}
			echo "</select>";
		
	}
	*/

	public function get_related_publication_list_item($project_publication_id) {
		ob_start();
		?>
				<div class="publication-container">
						<div><?php echo get_the_title($project_publication_id); ?></div>
						<div><a id="removepublication_<?php echo $project_publication_id; ?>" class="remove-publication" href="#/">Remove</a></div>
						<input type="hidden" name="post_ID_publication" value="<?php echo $project_publication_id; ?>">					
				</div>
				<hr/>
		<?php
		return ob_get_clean();
	}

	 /*
		 Renders the metabox contents for the 'Publications' metabox for the admin page for 'project' custom post type
	 */
	public function add_meta_box_callback_render_related_publications($post, $args) {
		$project_publication_id_list = maybe_unserialize(get_post_meta($post->ID, "project_publication_id_list", true));
		?>
		<label>Publications ID List:</label><br />
		<div id="related-publication-list">
			<?php		
			if (is_array($project_publication_id_list) && sizeof($project_publication_id_list) > 0) {
				foreach ($project_publication_id_list as $i => $project_publication_id) {
					echo get_related_publication_list_item($project_publication_id);
				}
			}
			?>
		</div>	
		
				<div id="publication-adder" class="wp-hidden-children">
					<a id="publication-add-toggle" href="#/" class="hide-if-no-js">
						+ Add Related Publication				
					</a>
					<div id="publication-add" class="publication-add wp-hidden-child">
							
						<div id="publication-add-selector" class="nav-tab-wrapper">
								<a id="publication-add-selector-existing" href="#/" class="hide-if-no-js nav-tab nav-tab-active">Add existing publications</a>
								<a id="publication-add-selector-new"      href="#/" class="hide-if-no-js nav-tab">Add new publication</a>
						</div>
						
						<div style="background-color:#f5f5f5; padding: 15px; border-bottom: 1px solid #ddd; height: 24rem;">

							<div id="existing_publication" class="existing_publication">
								<label class="screen-reader-text" for="newcategory_parent">Existing Publication:</label>
								<?php $query = new WP_Query( 'post_type=publication' ); ?>
								<?php if ( $query->have_posts() ) : ?>
									<select  name='newcategory_parent' id='newcategory_parent' class='postform' >
										<option value='-1'>&mdash; Existing Publications &mdash;</option>
										<?php while ( $query->have_posts() ) : $query->the_post(); ?>
										<option class="level-0" value="<?php the_ID()?>"><?php the_title()?></option>
										<?php endwhile; ?>
									</select>
								<?php else: ?>
									<p>No publications have been created yet</p>
								<?php endif; ?>
							</div>				

							<div id="create_new_publication" class="create_new_publication wp-hidden-child">
								<p>
									<label>Title:</label><br />
									<input name="publication_title" class="full-width">
								</p>							
								<?php add_meta_box_callback_render_publication(null, array()) ?>
							</div>				

						<input type="button" id="publication-add-submit" data-wp-lists="add:categorychecklist:publication-add" class="button category-add-submit" value="Add Publication" />
						<input type="hidden" id="_ajax_nonce-add-category" name="_ajax_nonce-add-category" value="2e8771f3cf" />					<span id="category-ajax-response"></span>
						 
						<?php /*submit_button("Add publication");*/			?>		
						
						</div>
						

					</div>
					
				</div>	
		
		<?php
		
	}

	/*
	function p2p2_save_publication_metabox($post_id, $post){
			// Don't wanna save this now, right?
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
					return;
			if ( !isset( $_POST['p2p2_project_publication_nonce'] ) )
					return;
			if ( !wp_verify_nonce( $_POST['p2p2_project_publication_nonce'], plugin_basename( __FILE__ ) ) )
					return;

			$key = 'p2p2_project_publication_list'; // name of key of the metadata (value for column meta_key in table wp_postmeta)
			$value = $_POST["p2p2_project_publication_list"]; // value of the metadata (value for column meta_value in table wp_postmeta)
			$value = array(55, 44, 33);
			if ( get_post_meta( $post_id, $key, FALSE ) ) {
				// If the metadata key already exists in the table, just update it
				update_post_meta( $post_id, $key, $value );
			} else { 
				// If the metadata key does not exist in the table yet, add a new row
				add_post_meta( $post_id, $key, $value );
			}
			//if ( !$value ) delete_post_meta( $post_id, $key ); // Delete if blank
	}
	add_action('save_post', 'p2p2_save_publication_metabox', 1, 2);
	*/


	/*
		Saves the values entered in the custom metaboxes for the project custom post type
	*/ 
	public function update_post_callback_custom_post_type_project($post_id, $post){
	 
		if ("project" == get_post_type($post_id)) {
			//error_log('update posts callback');
			$_POST["project_title"]    = isset($_POST["project_title"]) ? $_POST["project_title"] : '';
			$_POST["project_subtitle"] = isset($_POST["project_title"]) ? $_POST["project_subtitle"] : '';
			$_POST["project_credits"]  = isset($_POST["project_title"]) ? $_POST["project_credits"] : '';

			update_post_meta($post_id, "project_title", 							sanitize_text_field($_POST["project_title"]));
			update_post_meta($post_id, "project_subtitle", 					  sanitize_text_field($_POST["project_subtitle"]));
			update_post_meta($post_id, "project_credits", 						sanitize_text_field($_POST["project_credits"]));
		}
	}
	

	/*
		Saves the values entered in the custom metaboxes for the publication custom post type
	*/
	public function update_post_callback_custom_post_type_publication($post_id, $post){
		if ("publication" == get_post_type($post_id)) {
			if (array_key_exists("publication_authors", $_POST)) {
				// this check is inserted because this callback function gets triggered both when
				// making new posts from the new publications page and adding a new publication in the projects page.
				
				update_publication_post_meta($post_id, $_POST);
				store_publication_file($post_id, $_FILES);


				//update_post_meta($post_id, "publication_authors", 	sanitize_text_field($_POST["publication_authors"]));
				//update_post_meta($post_id, "publication_details", 	sanitize_text_field($_POST["publication_details"]));
				//update_post_meta($post_id, "publication_link", 			sanitize_text_field($_POST["publication_link"]));
				//update_post_meta($post_id, "publication_file", 			sanitize_text_field($_POST["publication_file"]));
			}
		}
	}
	//add_action('save_post', 'save_post_callback_custom_post_type_publication', 10, 3);


	/*
		Saves the values entered in the custom metaboxes for the portfolio_item custom post type
	*/
	public function update_post_callback_custom_post_type_portfolio_item($post_id, $post){
		if ("portfolio_item" == get_post_type($post_id)) {
			if (isset($_POST["portfolio_item_details"])) { update_post_meta($post_id, "portfolio_item_details", 	sanitize_text_field($_POST["portfolio_item_details"])); }
			if (isset($_POST["portfolio_item_link"]))    { update_post_meta($post_id, "portfolio_item_link", 		  sanitize_text_field($_POST["portfolio_item_link"])); }
		}
	}
	


	public function update_post_callback_featured($post_id, $post, $update){
		if ("project" == get_post_type($post_id) || "portfolio_item" == get_post_type($post_id) || "publication" == get_post_type($post_id)) {
			if (array_key_exists("featured", $_POST) && ($_POST["featured"] == "on")) {
				$is_featured = 1;
			} else {
				$is_featured = 0;	
			}
			update_post_meta($post_id, "featured", 	$is_featured);
		}
	}
	


	/*
		register the javascript needed for the admin panel
	*/
	public function register_plugin_scripts()
	{
			// Register the script like this for a plugin:
			wp_register_script( 'excellent-engineering-portfolio-admin-script', plugins_url( '/js/excellent-engineering-portfolio-admin.js', __FILE__ ),  array( 'jquery' ) );

			// For either a plugin or a theme, you can then enqueue the script:
			wp_enqueue_script( 'excellent-engineering-portfolio-admin-script' );
	}
	


	public function associate_publication_with_project($post_id_project, $post_id_publication) {
		$project_publication_id_list = maybe_unserialize(get_post_meta($post_id_project, "project_publication_id_list", true));	
		if (is_array($project_publication_id_list) && sizeof($project_publication_id_list) > 0) {
			if (!in_array($post_id_publication, $project_publication_id_list)) {
				array_push($project_publication_id_list, $post_id_publication);
			}
		}	else {
			$project_publication_id_list = array($post_id_publication);
		}
		$success = update_post_meta($post_id_project, "project_publication_id_list", serialize($project_publication_id_list));
		return $success;
	}

	public function disassociate_publication_with_project($post_id_project, $post_id_publication) {
		$success = TRUE;
		$project_publication_id_list = maybe_unserialize(get_post_meta($post_id_project, "project_publication_id_list", true));	
		if (is_array($project_publication_id_list) && sizeof($project_publication_id_list) > 0) {
			//remove from array
			$index = array_search($post_id_publication, $project_publication_id_list);
			echo "found index: ".$index.".";
			if ($index !== FALSE) {
				unset($project_publication_id_list[$index]);
				$project_publication_id_list = array_values($project_publication_id_list); // need to call to make indices consecutive again
				$success = update_post_meta($post_id_project, "project_publication_id_list", serialize($project_publication_id_list));
			}
		}
		return $success;
	}


	public function update_publication_post_meta($post_id, $new_publication) {
		update_post_meta($post_id, "publication_authors", 	sanitize_text_field($new_publication["publication_authors"]));
		update_post_meta($post_id, "publication_details", 	sanitize_text_field($new_publication["publication_details"]));
		update_post_meta($post_id, "publication_link", 		sanitize_text_field($new_publication["publication_link"]));
		//update_post_meta($post_id, "publication_file", 		sanitize_text_field($new_publication["publication_file"]));
	}

	public function store_publication_file($post_id, $dummy) {
		// If the upload field has a file in it
		if(isset($_FILES['publication_file']) && ($_FILES['publication_file']['size'] > 0)) {

				// Get the type of the uploaded file. This is returned as "type/extension"
				$arr_file_type = wp_check_filetype(basename($_FILES['publication_file']['name']));
				$uploaded_file_type = $arr_file_type['type'];

				// Set an array containing a list of acceptable formats
				$allowed_file_types = array('application/pdf');

				// If the uploaded file is the right format
				if(in_array($uploaded_file_type, $allowed_file_types)) {

						// Options array for the wp_handle_upload function. 'test_upload' => false
						$upload_overrides = array( 'test_form' => false ); 

						// Handle the upload using WP's wp_handle_upload function. Takes the posted file and an options array
						$uploaded_file = wp_handle_upload($_FILES['publication_file'], $upload_overrides);

						// If the wp_handle_upload call returned a local path for the image
						if(isset($uploaded_file['file'])) {

								// The wp_insert_attachment function needs the literal system path, which was passed back from wp_handle_upload
								$file_name_and_location = $uploaded_file['file'];

								// Generate a title for the image that'll be used in the media library
								$file_title_for_media_library = $_FILES['publication_file']['name'];

								// Set up options array to add this file as an attachment
								$attachment = array(
										'post_mime_type' => $uploaded_file_type,
										'post_title' => $file_title_for_media_library,
										'post_content' => '',
										'post_status' => 'inherit'
								);

								// Run the wp_insert_attachment function. This adds the file to the media library and generates the thumbnails. If you wanted to attch this image to a post, you could pass the post id as a third param and it'd magically happen.
								$attach_id = wp_insert_attachment( $attachment, $file_name_and_location, $post_id);
								//require_once(ABSPATH . "wp-admin" . '/includes/image.php');
								//$attach_data = wp_generate_attachment_metadata( $attach_id, $file_name_and_location );
								
								//wp_update_attachment_metadata($attach_id,  $attach_data);

								// Before we update the post meta, trash any previously uploaded image for this post.
								// You might not want this behavior, depending on how you're using the uploaded images.
								//$existing_uploaded_image = (int) get_post_meta($post_id,'_xxxx_attached_image', true);
								//if(is_numeric($existing_uploaded_image)) {
								//		wp_delete_attachment($existing_uploaded_image);
								//}

								// Now, update the post meta to associate the new image with the post
								//update_post_meta($post_id, 'publication_file_id', $attach_id);

								// Set the feedback flag to false, since the upload was successful
								//$upload_feedback = false;


						} else { // wp_handle_upload returned some kind of error. the return does contain error details, so you can use it here if you want.

								//$upload_feedback = 'There was a problem with your upload.';

						}

				} else { // wrong file type

						//$upload_feedback = 'Please upload only image files (jpg, gif or png).';

				}

		} else { // No file was passed

				//$upload_feedback = false;

		}

		// Update the post meta with any feedback
		//update_post_meta($post_id,'_xxxx_attached_image_upload_feedback',$upload_feedback);
		
		
	}

	/*
		This is the ajax responder - it gets input from client-side javascript and sends back a response.
	*/
	public function publication_add_new_action() {
		
		$post_id         = intval($_POST['post_id']);
		$new_publication = json_decode(stripslashes($_POST['new_publication']), true);
		
		//error_log(var_export($new_publication, true));
		
		// insert post into the database
		
		$postarr = array(
			'post_title'    => $new_publication["publication_title"],
			'post_content'  => '',
			'post_status'   => 'publish',
			'post_type'     => 'publication',	
		);
		$added_publication_id = wp_insert_post($postarr);	
		update_publication_post_meta($added_publication_id, $new_publication);
		store_publication_file($added_publication_id, null);
		
		// and associate this publication with this project
		$success = associate_publication_with_project($post_id, $added_publication_id);
		
		if ($success == TRUE) {
			echo get_related_publication_list_item($added_publication_id); // return this to the client
		} else {
			echo "error";
		}
		wp_die(); // this is required to terminate immediately and return a proper response
	}

	/*
		This is the ajax responder - it gets input from client-side javascript and sends back a response.
	*/
	public function publication_add_existing_action() {
		
		// access the post variable from the client
		$post_id              = intval($_POST['post_id']);
		$added_publication_id = intval($_POST['publication_id']);
		
		// and associate this publication with this project
		$success = associate_publication_with_project($post_id, $added_publication_id);
		
		if ($success == TRUE) {
			echo get_related_publication_list_item($added_publication_id); // return this to the client
		} else {
			echo "error";
		}
		wp_die(); // this is required to terminate immediately and return a proper response
	}

	/*
		This is the another ajax responder - for removing a related publication
	*/
	public function publication_remove_action() {
		$post_ID         = intval($_POST['post_ID']);
		$publication_ID  = intval($_POST['publication_ID']);
		$success = disassociate_publication_with_project($post_ID, $publication_ID);
		echo $success;
		wp_die();
	}


	


	// Add featured post column in admin panel
	public function manage_posts_columns_callback_featured($columns) {
		// adds a column with the identifier 'featured' and display string 'Featured'.
		array_splice($columns, sizeof($columns) - 1, 0, array('featured' => __('Featured')));

		return $columns;
		 //return array_merge( $columns, 
		 //           array('featured' => __('Featured')) );	
	}
	 
	// Add featured post column in admin panel
	public function manage_posts_custom_columns_callback_featured($column_name, $post_ID) {
		if ($column_name == 'featured') {
			$featured = get_post_meta($post_ID, "featured", true);
			if ($featured) {
				echo $featured;
			}
		}
	}

	
	
	/*
		Add file upload functionality to Publications custom post type admin page
	*/

	// changes encoding type for form post data to allow file uploads
	public function post_edit_form_tag_callback_multipart_encoding() {

			echo ' enctype="multipart/form-data"';

	}
	
	


	
}

set_error_handler(function($severity, $message, $file, $line) {
		if (error_reporting() & $severity) {
				throw new ErrorException($message, 0, $severity, $file, $line);
		}
});

global $eep_controller;
$eep_controller = new ExcellentEngineeringPortfolio();


function get_publication_href($post_id) {
	$HREF_TYPE_LINK = 1;
	$HREF_TYPE_FILE = 2;

	if ('' != get_post_meta(get_the_ID(), "publication_link", true)) {
		$has_href = true;
		$href_type = $HREF_TYPE_LINK;
		$href = get_post_meta(get_the_ID(), "publication_link", true);
	} else {
		$args = array(
			'post_type'      => 'attachment',
			'post_mime_type' => 'application/pdf',
			'post_parent'    => get_the_ID(),
		);
		$attachments  		 = get_posts($args);
		if ($attachments != null && sizeof($attachments) > 0) {
			$publication_file_id = $attachments[0]->ID;	
			$has_href = true;
			$href_type = $HREF_TYPE_FILE;
			$href = wp_get_attachment_url($publication_file_id);
		} else {
			$has_href = false;
		}										
	}	
	return array ($has_href, $href, $href_type);
}	



// TODO:

/*

make the links/downloads for the projects work
fix the resume
Make the patent display on the appropriate project single page
Make the skills display on portfolio items
*/
