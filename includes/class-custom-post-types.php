<?php
/**
 * Class to handle all custom post type definitions for Excellent Engineering Portfolio
 */

if ( !defined( 'ABSPATH' ) )
	exit;

class EepCustomPostTypes {

	/**
	 * Array of menu item taxonomies
	 *
	 * @param array
	 * @since 1.5
	 */
	public $project_taxonomies = array();
	public $portfolio_item_taxonomies = array();

	public function __construct() {
		
		// Call when plugin is initialized on every page load load_cpts
		add_action( 'init', 			array( $this, 'load_cpts') );
		add_action( 'admin_menu', array( $this, 'register_custom_menu_page') );
		add_action( 'admin_menu', array( $this, 'load_cpt_admin_menu' ) );

		// Handle metaboxes
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', 			array( $this, 'save_meta' ) );
/*
		// Add columns and filters to the admin list of menu items
		add_filter( 'manage_fdm-menu-item_posts_columns', array( $this, 'menu_item_posts_columns' ) );
		add_filter( 'manage_edit-fdm-menu-item_sortable_columns', array( $this, 'menu_item_posts_sortable_columns' ) );
		add_action( 'pre_get_posts', array( $this, 'menu_item_posts_orderby' ) );
		add_action( 'manage_fdm-menu-item_posts_custom_column', array( $this, 'menu_item_posts_columns_content' ), 10, 2 );
		add_action( 'restrict_manage_posts', array( $this, 'menu_item_posts_filters' ) );
		add_filter( 'parse_query', array( $this, 'menu_item_posts_filter_query' ) );

		// Add columns and filters to the admin list of menus
		add_filter( 'manage_fdm-menu_posts_columns', array( $this, 'menu_posts_columns' ) );
		add_action( 'manage_fdm-menu_posts_custom_column', array( $this, 'menu_posts_columns_content' ), 10, 2 );

		// Process price changes from the menu item list table
		add_action( 'wp_ajax_nopriv_fdm-menu-item-price' , array( $this , 'ajax_nopriv' ) );
		add_action( 'wp_ajax_fdm-menu-item-price', array( $this, 'ajax_menu_item_price' ) );

		// Allow menus to opt for a page template if desired
		add_filter( 'theme_' . FDM_MENU_POST_TYPE . '_templates', array( $this, 'add_menu_templates' ), 10, 3 );
		add_filter( 'template_include', array( $this, 'load_menu_template' ), 99 );
		*/
		
		/*
		add_action('post_updated', array( $this, 'update_post_callback_custom_post_type_project'), 10, 2);

		add_action('post_updated', array( $this, 'update_post_callback_custom_post_type_publication'), 10, 2);
		
		add_action('post_updated', array( $this, 'update_post_callback_custom_post_type_portfolio_item'), 10, 2);
		
		add_action('post_updated', array( $this, 'update_post_callback_featured'), 10, 3);
		*/
		
		
		//	The function below is the ajax handler. The first and second argument strings strings must match!!
		
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
	
	///////////////////////////////////////////////////////////
	
	/**
	 * loads custom post types and taxonomies in order
	 */		
	public function load_cpts() {

		$this->create_post_type_project();
		$this->create_post_type_publications();
		$this->create_post_type_portfolio_item();
		$this->create_taxonomy_skill();
		$this->create_taxonomy_publication_type();

	}

	/**
	 * enables 'project' custom post type
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
			'rewrite' 				=> array( 'slug' => 'project' ),
			'supports'        => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', ),
			'show_in_menu'		=> 'engineering-portfolio',
			);
			
		// Create filter so addons can modify the arguments
		$args = apply_filters( 'eep_project_args', $args );

		// Add an action so addons can hook in before the project is registered
		do_action( 'eep_project_pre_register' );

		// Register the project
		register_post_type( 'project', $args );

		// Add an action so addons can hook in after the project is registered
		do_action( 'eep_project_post_register' );
	}
	
	/**
	 * enables 'publication' custom post type
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
			'rewrite' 				=> array( 'slug' => 'publication' ),			
			'supports'        => array( 'title' ),
			'show_in_menu'		=> 'engineering-portfolio',
			);
		
		// Create filter so addons can modify the arguments
		$args = apply_filters( 'eep_publication_args', $args );

		// Add an action so addons can hook in before the publication is registered
		do_action( 'eep_publication_pre_register' );

		// Register the publication
		register_post_type( 'publication', $args );

		// Add an action so addons can hook in after the publication is registered
		do_action( 'eep_publication_post_register' );		
	}
	
	/**
	 * enables 'portfolio_item' custom post type
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
			'rewrite' 				=> array( 'slug' => 'portfolio-item' ),
			'supports'        => array( 'title', 'thumbnail' ),
			'show_in_menu'		=> 'engineering-portfolio',
			);

		// Create filter so addons can modify the arguments
		$args = apply_filters( 'eep_portfolio_item_args', $args );

		// Add an action so addons can hook in before the portfolio_item is registered
		do_action( 'eep_portfolio_item_pre_register' );

		// Register the portfolio_item
		register_post_type( 'portfolio_item', $args );

		// Add an action so addons can hook in after the portfolio_item is registered
		do_action( 'eep_portfolio_item_post_register' );				
	}


	/**
	 * enables 'skill' taxonomy
	 */	
	public function create_taxonomy_skill() {
		
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
		
		// Create skill taxonomy
		$this->project_taxonomies['skill'] = $args;
		$this->portfolio_item_taxonomies['skill'] = $args;
		
		// Create filter so addons can modify the taxonomies
		$this->project_taxonomies        = apply_filters( 'eep_project_taxonomies', $this->project_taxonomies );
		$this->portfolio_item_taxonomies = apply_filters( 'eep_portfolio_item_taxonomies', $this->portfolio_item_taxonomies );
		
		// Register taxonomies
		foreach( $this->project_taxonomies as $id => $taxonomy ) {
			register_taxonomy(
				$id,
				array('project', 'portfolio_item'),
				$taxonomy
			);
		}
		// Register taxonomies
		/*
		foreach( $this->portfolio_item_taxonomies as $id => $taxonomy ) {
			register_taxonomy(
				$id,
				'portfolio_item',
				$taxonomy
			);
		}
		*/

		//register_taxonomy( 'skill', array('project', 'portfolio_item'), $args );


	}

	/**
	 * enables 'publication_type' taxonomy
	 */	
	public function create_taxonomy_publication_type() {
		
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

		// Create menu sections (desserts, entrees, etc)
		$this->publication_taxonomies['publication_type'] = $args;
		
		// Create filter so addons can modify the taxonomies
		$this->publication_taxonomies        = apply_filters( 'eep_publication_taxonomies', $this->publication_taxonomies );
					
			
		register_taxonomy( 'publication_type', array('publication'), $args );

		wp_insert_term('patent', 'publication_type', array('description' => 'Patents'));
		wp_insert_term('paper',  'publication_type', array('description' => 'Papers'));

		$term_id_patent = get_term_by('name', 'patent', 'publication_type')->term_id;
		$term_id_paper  = get_term_by('name', 'paper',  'publication_type')->term_id;
		
		wp_update_term($term_id_patent, 'publication_type', array('description' => 'Patents'));
		wp_update_term($term_id_paper,  'publication_type', array('description' => 'Papers'));		
	}
	
	/**
	 * Add submenu items to the menu
	 *
	 */
	public function load_cpt_admin_menu() {

		// Remove the Add Menu item
		/*
		remove_submenu_page(
			'edit.php?post_type=' . FDM_MENU_POST_TYPE,
			'post-new.php?post_type=' . FDM_MENU_POST_TYPE
		);
		*/

		// Add any menu item taxonomies
		foreach( $this->project_taxonomies as $id => $taxonomy ) {
			add_submenu_page(
				'engineering-portfolio',
				$taxonomy['labels']['name'],
				$taxonomy['labels']['name'],
				isset( $taxonomy['capabilities'] ) ? $taxonomy['capabilities']['edit_terms'] : 'edit_posts',
				'edit-tags.php?taxonomy=' . $id . '&post_type=' . EEP_PROJECT_POST_TYPE
			);
		}
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

	/**
	 * Add metaboxes to specify custom post type data
	 */
	public function add_meta_boxes() {

/*
		add_meta_box("project_meta_box_project_details", "Project details", array( $this, "add_meta_box_callback_render_project_details"), "project", "normal", "low");
		add_meta_box("project_meta_box_related_publications", "Related Publications", array( $this, "add_meta_box_callback_render_related_publications"), "project", "normal", "low");
		add_meta_box("publication_meta", "Publication details", array($this, "add_meta_box_callback_render_publication"), "publication", "normal", "low");
		add_meta_box("portfolio_item_meta", "Portfolio Item details", "add_meta_box_callback_render_portfolio_item", "portfolio_item", "normal", "low");
		add_meta_box("featured_meta", "Feature on front page", array($this, "add_meta_box_callback_render_featured"), "project", "side", "low");
		add_meta_box("featured_meta", "Feature on front page", array($this, "add_meta_box_callback_render_featured"), "publication", "side", "low");
		add_meta_box("featured_meta", "Feature on front page", array($this, "add_meta_box_callback_render_featured"), "portfolio_item", "side", "low");
*/
		
		
		
		$meta_boxes = array(

			// Add project details metabox
			'project_meta_box_project_details' => array (
				'id'		=>	'project_meta_box_project_details',
				'title'		=> esc_html__( 'Project details', 'textdomain' ),
				'callback'	=> array( $this, 'add_meta_box_callback_render_project_details' ),
				'screen'	=> 'project',
				'context'	=> 'normal',
				'priority'	=> 'low'
			),

			// Add a metabox
			'project_meta_box_related_publications' => array (
				'id'		=>	'project_meta_box_related_publications',
				'title'		=> esc_html__( 'Related Publications', 'textdomain' ),
				'callback'	=> array( $this, 'add_meta_box_callback_render_related_publications' ),
				'screen'	=> 'project',
				'context'	=> 'normal',
				'priority'	=> 'low'
			),
			
			// Add a metabox
			'publication_meta' => array (
				'id'		=>	'publication_meta',
				'title'		=> esc_html__( 'Publication details', 'textdomain' ),
				'callback'	=> array( $this, 'add_meta_box_callback_render_publication' ),
				'screen'	=> 'publication',
				'context'	=> 'normal',
				'priority'	=> 'low'
			),
						
			// Add a metabox
			'portfolio_item_meta' => array (
				'id'		=>	'portfolio_item_meta',
				'title'		=> esc_html__( 'Portfolio Item details', 'textdomain' ),
				'callback'	=> array( $this, 'add_meta_box_callback_render_portfolio_item' ),
				'screen'	=> 'portfolio_item',
				'context'	=> 'normal',
				'priority'	=> 'low'
			),
			
			// Add a metabox to multiple pages at once
			'featured_meta' => array (
				'id'		=>	'featured_meta',
				'title'		=> esc_html__( 'Feature on front page', 'textdomain' ),
				'callback'	=> array( $this, 'add_meta_box_callback_render_featured' ),
				'screen'	=> array('project', 'publication', 'portfolio_item'),
				'context'	=> 'side',
				'priority'	=> 'low'
			),
		);

		// Create filter so addons can modify the metaboxes
		$meta_boxes = apply_filters( 'eep_meta_boxes', $meta_boxes );

		// Create the metaboxes
		foreach ( $meta_boxes as $meta_box ) {
			add_meta_box(
				$meta_box['id'],
				$meta_box['title'],
				$meta_box['callback'],
				$meta_box['screen'],
				$meta_box['context'],
				$meta_box['priority']
			);
		}
		
	}
	
	/**
	 * Save the metabox data
	 * @since 1.0
	 */
	public function save_meta( $post_id ) {
		
		// Check autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		
		// Array of values to fetch and store
		$meta_ids = array();

		$post_type = get_post_type($post_id);

		// Define Menu Item data
		if ( EEP_PROJECT_POST_TYPE == $post_type ) {
			
			$meta_ids['project_title'] 			= 'sanitize_text_field';
			$meta_ids['project_subtitle'] 	= 'sanitize_text_field';
			$meta_ids['project_credits'] 		= 'sanitize_text_field';
			$meta_ids['featured'] = '';
			
		}	elseif ( EEP_PORTFOLIO_ITEM_POST_TYPE == $post_type ) {
			
			$meta_ids['portfolio_item_details'] = 'sanitize_text_field';
			$meta_ids['portfolio_item_link'] = 'sanitize_text_field';
			$meta_ids['featured'] = '';
			
		} elseif ( EEP_PUBLICATION_POST_TYPE == $post_type ) {
			
			$meta_ids['publication_authors'] = 'sanitize_text_field';
			$meta_ids['publication_details'] = 'sanitize_text_field';
			$meta_ids['publication_link'] = 'sanitize_text_field';
			$meta_ids['featured'] = '';

			$this->store_publication_file($post_id);
		}

		
		

		// Create filter so addons can add new data
		$meta_ids = apply_filters( 'eep_save_meta', $meta_ids );		
			
		// Save the metadata
		foreach ($meta_ids as $meta_id => $sanitize_callback) {

			$cur = get_post_meta( $post_id, $meta_id, true );
			
			if ($meta_id == 'featured') {
				$new = ( isset( $_POST[$meta_id] ) && $_POST[$meta_id] == 'on' ) ? 1 : 0;
			} else {
				$new = isset( $_POST[$meta_id] ) ? call_user_func( $sanitize_callback, $_POST[$meta_id] ) : '';
			}
		
			if ( $new != $cur ) {
				update_post_meta( $post_id, $meta_id, $new );
			}
			
		}		
		
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
							'post_mime_type' => array('application/pdf', 'text/plain', 'application/vnd.msword'),
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
		$this->publication_meta_render_metabox_callback_populate($publication_id)	;
	}


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
					echo $this->get_related_publication_list_item($project_publication_id);
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
						
						<div class="existing_publication_wrapper">

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
								<?php $this->add_meta_box_callback_render_publication(null, array()) ?>
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
		Saves the values entered in the custom metaboxes for the project custom post type
	*/ 
	/*
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
	*/
	

	/*
		Saves the values entered in the custom metaboxes for the publication custom post type
	*/
	public function update_post_callback_custom_post_type_publication($post_id, $post){
		if ("publication" == get_post_type($post_id)) {
			if (array_key_exists("publication_authors", $_POST)) {
				// this check is inserted because this callback function gets triggered both when
				// making new posts from the new publications page and adding a new publication in the projects page.
				
				update_publication_post_meta($post_id, $_POST);
				store_publication_file($post_id);


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
	/*
	public function update_post_callback_custom_post_type_portfolio_item($post_id, $post){
		if ("portfolio_item" == get_post_type($post_id)) {
			if (isset($_POST["portfolio_item_details"])) { update_post_meta($post_id, "portfolio_item_details", 	sanitize_text_field($_POST["portfolio_item_details"])); }
			if (isset($_POST["portfolio_item_link"]))    { update_post_meta($post_id, "portfolio_item_link", 		  sanitize_text_field($_POST["portfolio_item_link"])); }
		}
	}
	*/
	

/*
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
	*/
	

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

/*
	public function update_publication_post_meta($post_id, $new_publication) {
		update_post_meta($post_id, "publication_authors", 	sanitize_text_field($new_publication["publication_authors"]));
		update_post_meta($post_id, "publication_details", 	sanitize_text_field($new_publication["publication_details"]));
		update_post_meta($post_id, "publication_link", 		sanitize_text_field($new_publication["publication_link"]));
		//update_post_meta($post_id, "publication_file", 		sanitize_text_field($new_publication["publication_file"]));
	}
	*/

	public function store_publication_file($post_id) {
		
		// If the upload field has a file in it
		if(isset($_FILES['publication_file']) && ($_FILES['publication_file']['size'] > 0)) {

			// Get the type of the uploaded file. This is returned as "type/extension"
			$arr_file_type = wp_check_filetype(basename($_FILES['publication_file']['name']));
			$uploaded_file_type = $arr_file_type['type'];

			// Set an array containing a list of acceptable formats
			$allowed_file_types = array('application/pdf', 'text/plain', 'application/vnd.msword');

			// If the uploaded file is the right format
			if(in_array($uploaded_file_type, $allowed_file_types)) {

				// Options array for the wp_handle_upload function. 'test_upload' => false
				$upload_overrides = array( 'test_form' => false ); 

				// Handle the upload using WP's wp_handle_upload function. Takes the posted file and an options array
				$uploaded_file = wp_handle_upload( $_FILES['publication_file'], $upload_overrides );

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

				}

			}

		}		
		
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