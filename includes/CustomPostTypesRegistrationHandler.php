<?php

namespace ElectrifyingEngineeringPortfolio;

require_once(EEPF_PLUGIN_DIR . '/includes/PublicationTaxonomySyncHandler.php');

class CustomPostTypesRegistrationHandler {

    protected $configs = null;
    protected $publicationsTaxonomySyncHandler = null;

	public function __construct($configs) {

        $this->configs = $configs;

        $this->publicationsTaxonomySyncHandler = new PublicationTaxonomySyncHandler($configs);

    }

    public function registerCallbacks() {

		// Call when plugin is initialized on every page load registerCpts
		add_action( 'init', array( $this, 'registerCpts') );

        add_action( 'init', array( $this, 'insertTaxonomyTerms') );

        add_filter( 'manage_nav-menus_columns', array($this, 'enableNavMenuProjectByDefault') );

        $this->publicationsTaxonomySyncHandler->registerCallbacks();

	}

    function enableNavMenuProjectByDefault( $columns )
    {
        $userId  = wp_get_current_user()->ID;
        $optionArrayKey = 'metaboxhidden_nav-menus';
        $optionKey = 'add-post-type-eep_project';
        $hiddenNavMenusArray   = get_user_option($optionArrayKey, $userId);


        if (!$hiddenNavMenusArray) {
            // this option has not been created in the db yet
            update_user_option(
                $userId,
                $optionArrayKey,
                array ()
            );
        }
        elseif (FALSE !== ($key = array_search($optionKey, $hiddenNavMenusArray))) {
            // this option exists in the db
            unset($hiddenNavMenusArray[$key]);
            update_user_option(
                $userId,
                $optionArrayKey,
                $hiddenNavMenusArray
            );
        }

        return $columns;
    }

	/**
	 * loads custom post types and taxonomies in order
	 */
	public function registerCpts() {

		$this->registerPostTypeProject();
		$this->registerPostTypePublications();
		$this->registerPostTypePortfolioItem();
		$this->registerTaxonomySkill();
		$this->registerTaxonomyPublicationType();
        $this->registerTaxonomyRelatedPublication();

	}

    public function deregisterCpts() {

        foreach ($this->configs->cpt_names as $cpt_name) {
            unregister_post_type($cpt_name);
        }

        foreach ($this->configs->custom_taxonomy_names as $taxonomy) {
            unregister_taxonomy($taxonomy['name']);
        }

    }

    private function createCapabilitiesArray($cptNamesArray) {
        $singular       = $cptNamesArray[0];
        $plural         = $cptNamesArray[1];

        $capabilities = array(
            'edit_post' => 'edit_' . $singular,
            'delete_post' => 'delete_' . $singular,
            'read_post' => 'read_' . $singular,
            'publish_posts' => 'publish_' . $plural,
            'edit_posts' => 'edit_' . $plural,
            'edit_others_posts' => 'edit_others_' . $plural,
            'edit_published_posts' => 'edit_published_' . $plural,
            'edit_private_posts' => 'edit_private_' . $plural,
            'delete_posts' => 'delete_' . $plural,
            'delete_others_posts' => 'delete_others_' . $plural,
            'delete_published_posts' => 'delete_published_' . $plural,
            'delete_private_posts' => 'delete_private_' . $plural,
            'read_private_posts' => 'read_private_' . $plural,
            'create_posts' => 'create_' . $plural,
            'read'  => 'read'
        );

        return $capabilities;
    }

    public function registerCapabilities() {

        $cpts = $this->configs->cpt_names;

        foreach ($cpts as $cptHandle => $cptName) {

            $singular = $cptName;
            $plural = $cptName . 's';

            $capabilities = $this->createCapabilitiesArray(array($singular, $plural));

            $roleNames = $this->configs->roleNames;

            foreach ($capabilities as $key => $capability) {
                foreach ($roleNames as $roleName) {
                    $role = get_role($roleName);
                    $role->add_cap($capability);
                }
            }
        }
    }

    public function deregisterCapabilities() {

        $cpts = $this->configs->cpt_names;

        foreach ($cpts as $cptHandle => $cptName) {

            $singular = $cptName;
            $plural = $cptName . 's';

            $capabilities = $this->createCapabilitiesArray(array($singular, $plural));

            $roleNames = $this->configs->roleNames;

            foreach ($capabilities as $key => $capability) {
                foreach ($roleNames as $roleName) {
                    $role = get_role($roleName);
                    $role->remove_cap($capability);
                }
            }
        }
    }

    public function insertTaxonomyTerms() {
        $this->insertTaxonomyTermsPublicationType();
    }

	/**
	 * Flush the rewrite rules when this plugin is activated
	 */
	public function registerCptsFlushRewrite() {
		$this->registerCpts();
		flush_rewrite_rules();
	}

    public function deregisterCptsFlushRewrite() {
        $this->deregisterCpts();
		flush_rewrite_rules();
    }

	/**
	 * enables 'project' custom post type
	 */
	public function registerPostTypeProject() {

		$labels = $this->configs->cpt_labels['project'];

        $slug = $this->configs->cpt_names['project'];

		$args = array(
			'labels' 			=> $labels,
			'public' 			=> true,
            'publicly_queryable' => true,
			'has_archive' 		=> true,
			'rewrite' 			=> array( 'slug' => 'project' ),
			'supports'          => array( 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes'),
			'show_ui'           => true,
            'show_in_menu'      => false,
            'show_in_nav_menus' => true,
            'capability_type'   => array($slug, $slug.'s'),
            'map_meta_cap'      => true, // this needs to be set true, otherwise error message will appear that delete_posts is missing
			);

		// Register the project
		register_post_type($this->configs->cpt_names['project'], $args );

	}

	/**
	 * enables 'publication' custom post type
	 */
	public function registerPostTypePublications() {

        $labels = $this->configs->cpt_labels['publication'];

        $slug = $this->configs->cpt_names['publication'];

		$args = array(
			'labels' 			=> $labels,
			'public' 			=> true,
            'publicly_queryable' => false,
			'has_archive' 		=> true,
			'rewrite' 			=> array('slug' => 'publication'),
			'supports'          => array('title', 'page-attributes'),
			'show_in_menu'      => false,
            'show_in_nav_menus' => false,
            'capability_type'   => array($slug, $slug.'s'),
            'map_meta_cap'      => true, // this needs to be set true, otherwise error message will appear that delete_posts is missing
		);

		// Register the publication
		register_post_type($this->configs->cpt_names['publication'], $args );

	}

	/**
	 * enables 'portfolio_item' custom post type
	 */
	public function registerPostTypePortfolioItem() {

        $labels = $this->configs->cpt_labels['portfolio_item'];

        $slug = $this->configs->cpt_names['portfolio_item'];

		$args = array(
			'labels' 			=> $labels,
			'public' 			=> true,
            'publicly_queryable' => true,
			'has_archive' 		=> true,
			'rewrite' 			=> array( 'slug' => 'portfolio-item' ),
			'supports'          => array( 'title', 'thumbnail', 'page-attributes' ),
			'show_in_menu'      => false,
            'show_in_nav_menus' => false,
            'capability_type'   => array($slug, $slug.'s'),
            'map_meta_cap'      => true, // this needs to be set true, otherwise error message will appear that delete_posts is missing
		);


		// Register the portfolio_item
		register_post_type($this->configs->cpt_names['portfolio_item'], $args );

	}


	/**
	 * enables 'skill' taxonomy
	 */
	public function registerTaxonomySkill() {

        $labels = $this->configs->taxonomy_labels['skill'];
        $slug = $this->configs->custom_taxonomy_names['skill']['name'];

        $args = array(
            'labels'                => $labels,
            'public'				=> true,
            'publicly_queryable'	=> false,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'show_in_quick_edit'    => true,
            'query_var'             => true,
            'hierarchical'      	=> true,
            'show_in_menu'          => false,
            'show_in_nav_menus'     => false,
        );

        $taxonomy_name = $this->configs->custom_taxonomy_names['skill']['name'];

		register_taxonomy(
            $taxonomy_name,
            array(
                $this->configs->cpt_names['project'],
                $this->configs->cpt_names['portfolio_item'],
            ),
            $args
        );

	}

	/**
	 * enables 'publication_type' taxonomy
	 */
	public function registerTaxonomyPublicationType() {

        $labels = $this->configs->taxonomy_labels['publication_type'];

		$args = array(
			'labels'                => $labels,
			'public'			    => true,
			'publicly_queryable'	=> false,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'show_in_quick_edit'    => true,
			'query_var'             => true,
			'hierarchical'      	=> true,
            'show_in_menu'          => false,
            'show_in_nav_menus'     => false,
        );

        $taxonomy_name = $this->configs->custom_taxonomy_names['publication_type']['name'];

		register_taxonomy(
            $taxonomy_name,
            array($this->configs->cpt_names['publication']),
            $args
        );

	}

    public function registerTaxonomyRelatedPublication() {

        $labels = $this->configs->taxonomy_labels['related_publication'];

		$args = array(
			'labels'                => $labels,
			'public'			    => true,
			'publicly_queryable'	=> false,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'show_in_quick_edit'    => true,
			'query_var'             => true,
			'hierarchical'      	=> true,
            'show_in_menu'          => false,
            'show_in_nav_menus'     => false,
        );

        $taxonomy_name = $this->configs->custom_taxonomy_names['related_publication']['name'];

		register_taxonomy(
            $taxonomy_name,
            array(
                $this->configs->cpt_names['project'],
                $this->configs->cpt_names['portfolio_item']
            ),
            $args
        );

	}

    function insertTaxonomyTermsPublicationType() {

        $taxonomy_id = 'publication_type';

        $taxonomy_name = $this->configs->custom_taxonomy_names[$taxonomy_id]['name'];
        $taxonomy_terms = $this->configs->custom_taxonomy_names[$taxonomy_id]['terms'];

        foreach ($taxonomy_terms as $term => $term_data) {

            wp_insert_term(
                $term,
                $taxonomy_name,
                array('description' => $term_data['description'])
            );

            $term_id = get_term_by(
                'name',
                $term,
                $taxonomy_name
            )->term_id;

            wp_update_term(
                $term_id,
                $taxonomy_name,
                array('description' => $term_data['description'])
            );

        }
    }

}
