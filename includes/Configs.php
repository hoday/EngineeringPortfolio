<?php

namespace ElectrifyingEngineeringPortfolio;

class Configs {

    // key for the entry in database table wp_options
    public $plugin_options_key = 'eep_options';

    public $plugin_options_array_keys = array(
        'eep_field_num_cols' => 'eep_field_num_cols',
        'eep_field_style'    => 'eep_field_style',
    );
    public $plugin_options_array_defaults = array(
        'eep_field_num_cols' => 3,
        'eep_field_style'    => 'default',
    );

    public $cpt_names = array(
        'project' => 'eep_project',
        'portfolio_item' => 'eep_portfolio_item',
        'publication' => 'eep_publication',
    );

    public $custom_taxonomy_names = array(
        'skill' => array(
            'name' => 'eep_skill',
            'terms' => array(),
        ),
        'publication_type' => array(
            'name' => 'eep_publication_type',
            'terms' => array(
                'paper' => array(
                    'description' => 'Papers',
                ),
                'patent' => array(
                    'description' => 'Patents',
                ),
            ),
        ),
        'related_publication' => array(
            'name' => 'eep_related_publication',
            'terms' => array(),
        )
    );

    public $cpt_labels = null;

    public $taxonomy_labels = null;

    public $cpt_meta = array(
        'project' => array(
            'subtitle' => 'eep_project_subtitle',
            'description' => 'eep_project_description',
            'credits' => 'eep_project_credits',
            'featured' => 'eep_featured',
        ),
        'publication'  => array(
            'authors' => 'eep_publication_authors',
            'details' => 'eep_publication_details',
            'link' => 'eep_publication_link',
            'featured' => 'eep_featured',
        ),
        'portfolio_item' => array(
            'details' => 'eep_portfolio_item_details',
            'link' => 'eep_portfolio_item_link',
            'featured' => 'eep_featured',
        )
    );

    public $eep_menu_name = 'eep-menu';

    public $thumbnail_settings = array(
        'portfolioimg' => array(
            'name' => 'eep_thumbnail_portfolioimg',
            'width' => 300,
            'height' => 300,
        ),
        'readmoreimg' => array(
            'name' => 'eep_thumbnail_readmoreimg',
            'width' => 150,
            'height' => 120,
        ),
    );

    // these roles have permission to edit the cpts defined in this plugin
    public $roleNames = array('administrator', 'editor');

    // style definitions
    public $styleDefs = [];

    public function __construct() {

        $this->cpt_labels = array(
            'project' => array(
    			'name'                       => _x( 'Projects', 'taxonomy general name', 'electrifying-engineering-portfolio' ),
    			'singular_name'              => _x( 'Project', 'taxonomy singular name', 'electrifying-engineering-portfolio' ),
    			'search_items'               => __( 'Search Projects', 'electrifying-engineering-portfolio' ),
    			'popular_items'              => __( 'Popular Projects', 'electrifying-engineering-portfolio' ),
    			'all_items'                  => __( 'All Projects', 'electrifying-engineering-portfolio' ),
    			'parent_item'                => null,
    			'parent_item_colon'          => null,
    			'edit_item'                  => __( 'Edit Project', 'electrifying-engineering-portfolio' ),
    			'update_item'                => __( 'Update Project', 'electrifying-engineering-portfolio' ),
    			'add_new_item'               => __( 'Add New Project', 'electrifying-engineering-portfolio' ),
    			'new_item_name'              => __( 'New Project Name', 'electrifying-engineering-portfolio' ),
    			'separate_items_with_commas' => __( 'Separate projects with commas', 'electrifying-engineering-portfolio' ),
    			'add_or_remove_items'        => __( 'Add or remove projects', 'electrifying-engineering-portfolio' ),
    			'choose_from_most_used'      => __( 'Choose from the most used projects', 'electrifying-engineering-portfolio' ),
    			'not_found'                  => __( 'No projects found.', 'electrifying-engineering-portfolio' ),
    			'menu_name'                  => __( 'Projects', 'electrifying-engineering-portfolio' ),
    		),
            'publication' => array(
    			'name'                       => _x( 'Publications', 'taxonomy general name', 'electrifying-engineering-portfolio' ),
    			'singular_name'              => _x( 'Publication', 'taxonomy singular name', 'electrifying-engineering-portfolio' ),
    			'search_items'               => __( 'Search Publications', 'electrifying-engineering-portfolio' ),
    			'popular_items'              => __( 'Popular Publications', 'electrifying-engineering-portfolio' ),
    			'all_items'                  => __( 'All Publications', 'electrifying-engineering-portfolio' ),
    			'parent_item'                => null,
    			'parent_item_colon'          => null,
    			'edit_item'                  => __( 'Edit Publication', 'electrifying-engineering-portfolio' ),
    			'update_item'                => __( 'Update Publication', 'electrifying-engineering-portfolio' ),
    			'add_new_item'               => __( 'Add New Publication', 'electrifying-engineering-portfolio' ),
    			'new_item_name'              => __( 'New Publication Name', 'electrifying-engineering-portfolio' ),
    			'separate_items_with_commas' => __( 'Separate publications with commas', 'electrifying-engineering-portfolio' ),
    			'add_or_remove_items'        => __( 'Add or remove publications', 'electrifying-engineering-portfolio' ),
    			'choose_from_most_used'      => __( 'Choose from the most used publications', 'electrifying-engineering-portfolio' ),
    			'not_found'                  => __( 'No publications found.', 'electrifying-engineering-portfolio' ),
    			'menu_name'                  => __( 'Publications', 'electrifying-engineering-portfolio' ),
    		),
            'portfolio_item' => array(
    			'name'                       => _x( 'Portfolio Items', 'taxonomy general name', 'electrifying-engineering-portfolio' ),
    			'singular_name'              => _x( 'Portfolio Item', 'taxonomy singular name', 'electrifying-engineering-portfolio' ),
    			'search_items'               => __( 'Search Portfolio Items', 'electrifying-engineering-portfolio' ),
    			'popular_items'              => __( 'Popular Portfolio Items', 'electrifying-engineering-portfolio' ),
    			'all_items'                  => __( 'All Portfolio Items', 'electrifying-engineering-portfolio' ),
    			'parent_item'                => null,
    			'parent_item_colon'          => null,
    			'edit_item'                  => __( 'Edit Portfolio Item', 'electrifying-engineering-portfolio' ),
    			'update_item'                => __( 'Update Portfolio Item', 'electrifying-engineering-portfolio' ),
    			'add_new_item'               => __( 'Add New Portfolio Item', 'electrifying-engineering-portfolio' ),
    			'new_item_name'              => __( 'New Portfolio Item Name', 'electrifying-engineering-portfolio' ),
    			'separate_items_with_commas' => __( 'Separate portfolio items with commas', 'electrifying-engineering-portfolio' ),
    			'add_or_remove_items'        => __( 'Add or remove portfolio items', 'electrifying-engineering-portfolio' ),
    			'choose_from_most_used'      => __( 'Choose from the most used portfolio items', 'electrifying-engineering-portfolio' ),
    			'not_found'                  => __( 'No portfolio items found.', 'electrifying-engineering-portfolio' ),
    			'menu_name'                  => __( 'Portfolio Items', 'electrifying-engineering-portfolio' ),
    		),
        );

        $this->taxonomy_labels = array(
            'skill' => array(
                'name'                       => _x( 'Skills', 'taxonomy general name', 'electrifying-engineering-portfolio' ),
                'singular_name'              => _x( 'Skill', 'taxonomy singular name', 'electrifying-engineering-portfolio' ),
                'search_items'               => __( 'Search Skills', 'electrifying-engineering-portfolio' ),
                'popular_items'              => __( 'Popular Skills', 'electrifying-engineering-portfolio' ),
                'all_items'                  => __( 'All Skills', 'electrifying-engineering-portfolio' ),
                'parent_item'                => null,
                'parent_item_colon'          => null,
                'edit_item'                  => __( 'Edit Skill', 'electrifying-engineering-portfolio' ),
                'update_item'                => __( 'Update Skill', 'electrifying-engineering-portfolio' ),
                'add_new_item'               => __( 'Add New Skill', 'electrifying-engineering-portfolio' ),
                'new_item_name'              => __( 'New Skill Name', 'electrifying-engineering-portfolio' ),
                'separate_items_with_commas' => __( 'Separate skills with commas', 'electrifying-engineering-portfolio' ),
                'add_or_remove_items'        => __( 'Add or remove skills', 'electrifying-engineering-portfolio' ),
                'choose_from_most_used'      => __( 'Choose from the most used skills', 'electrifying-engineering-portfolio' ),
                'not_found'                  => __( 'No skills found.', 'electrifying-engineering-portfolio' ),
                'menu_name'                  => __( 'Skills', 'electrifying-engineering-portfolio' ),
            ),
            'publication_type' => array(
    			'name'                       => _x( 'Publication Types', 'taxonomy general name', 'electrifying-engineering-portfolio' ),
    			'singular_name'              => _x( 'Publication Type', 'taxonomy singular name', 'electrifying-engineering-portfolio' ),
    			'search_items'               => __( 'Search Publication Types', 'electrifying-engineering-portfolio' ),
    			'popular_items'              => __( 'Popular Publication Types', 'electrifying-engineering-portfolio' ),
    			'all_items'                  => __( 'All Publication Types', 'electrifying-engineering-portfolio' ),
    			'parent_item'                => null,
    			'parent_item_colon'          => null,
    			'edit_item'                  => __( 'Edit Publication Type', 'electrifying-engineering-portfolio' ),
    			'update_item'                => __( 'Update Publication Type', 'electrifying-engineering-portfolio' ),
    			'add_new_item'               => __( 'Add New Publication Type', 'electrifying-engineering-portfolio' ),
    			'new_item_name'              => __( 'New Publication Type Name', 'electrifying-engineering-portfolio' ),
    			'separate_items_with_commas' => __( 'Separate Publication Types with commas', 'electrifying-engineering-portfolio' ),
    			'add_or_remove_items'        => __( 'Add or remove Publication Types', 'electrifying-engineering-portfolio' ),
    			'choose_from_most_used'      => __( 'Choose from the most used Publication Types', 'electrifying-engineering-portfolio' ),
    			'not_found'                  => __( 'No Publication Types found.', 'electrifying-engineering-portfolio' ),
    			'menu_name'                  => __( 'Publication Types', 'electrifying-engineering-portfolio' ),
    		),
            'related_publication' => array(
    			'name'                       => _x( 'Related Publications', 'taxonomy general name', 'electrifying-engineering-portfolio' ),
    			'singular_name'              => _x( 'Related Publication', 'taxonomy singular name', 'electrifying-engineering-portfolio' ),
    			'search_items'               => __( 'Search Publications', 'electrifying-engineering-portfolio' ),
    			'popular_items'              => __( 'Popular Publications', 'electrifying-engineering-portfolio' ),
    			'all_items'                  => __( 'All Publications', 'electrifying-engineering-portfolio' ),
    			'parent_item'                => null,
    			'parent_item_colon'          => null,
    			'edit_item'                  => __( 'Edit Related Publications', 'electrifying-engineering-portfolio' ),
    			'update_item'                => __( 'Update Related Publications', 'electrifying-engineering-portfolio' ),
    			'add_new_item'               => __( 'Add New Related Publications', 'electrifying-engineering-portfolio' ),
    			'new_item_name'              => __( 'New Related Publications Name', 'electrifying-engineering-portfolio' ),
    			'separate_items_with_commas' => __( 'Separate Related Publications with commas', 'electrifying-engineering-portfolio' ),
    			'add_or_remove_items'        => __( 'Add or remove Related Publications', 'electrifying-engineering-portfolio' ),
    			'choose_from_most_used'      => __( 'Choose from the most used Publications', 'electrifying-engineering-portfolio' ),
    			'not_found'                  => __( 'No Related Publications found.', 'electrifying-engineering-portfolio' ),
    			'menu_name'                  => __( 'Related Publications', 'electrifying-engineering-portfolio' ),
    		),
        );

        $this->styleDefs = array(
            'none' => array(
                'label' => esc_html__('None', 'electrifying-engineering-portfolio'),
                'srcs' => array(),
            ),
            'base' => array(
                'label' => esc_html__('Base', 'electrifying-engineering-portfolio'),
                'srcs' => array(
                    EEPF_PLUGIN_URL . '/assets/css/base.css',
                ),
            ),
            'default' => array(
                'label' => esc_html__('Default', 'electrifying-engineering-portfolio'),
                'srcs' => array(
                    EEPF_PLUGIN_URL . '/assets/css/default.css',
                    EEPF_PLUGIN_URL . '/assets/css/base.css',
                ),
            ),
        );
    }

}
