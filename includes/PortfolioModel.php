<?php

namespace ElectrifyingEngineeringPortfolio;

require_once(EEPF_PLUGIN_DIR . '/includes/ProjectModel.php');
require_once(EEPF_PLUGIN_DIR . '/includes/PublicationModel.php');
require_once(EEPF_PLUGIN_DIR . '/includes/PortfolioItemModel.php');
require_once(EEPF_PLUGIN_DIR . '/includes/FeaturedModel.php');


class PortfolioModel {

    protected $configs = null;

    protected $featured_projects = [];
    protected $featured_portfolio_items = [];
    protected $featured_publications = [];

    public function __construct($configs) {
        $this->configs = $configs;

        $args = array(
            'post_type'     => $this->configs->cpt_names['project'],
            'posts_per_page' => -1,
            'order' 		=> 'DESC',
    		'orderby' 		=> 'menu_order',
    		'meta_key' 		=> $this->configs->cpt_meta['project']['featured'],
    		'meta_value' 	=> '1',
        );
        $this->featured_projects = get_posts($args);


        $args = array(
    		'post_type' 	=> $this->configs->cpt_names['portfolio_item'],
            'posts_per_page' => -1,
    		'order' 		=> 'DESC',
    		'orderby' 		=> 'menu_order',
            'meta_key' 		=> $this->configs->cpt_meta['portfolio_item']['featured'],
    		'meta_value' 	=> '1',
    	);
        $this->featured_portfolio_items = get_posts($args);

       $this->loadFeaturedPublications();

       // load number of columns user setting
       $options = get_option($this->configs->plugin_options_key);
       if ($options) {
           $key = $this->configs->plugin_options_array_keys['eep_field_num_cols'];
           $this->numberColumns = intval($options[$key]);
           if ($this->numberColumns <= 0) {
               $this->numberColumns = 1;
           } elseif ($this->numberColumns >= 4) {
               $this->numberColumns = 4;
           }

       } else {
           $this->numberColumns = 3;
       }

    }

    private function loadFeaturedPublications() {
        $taxonomy = $this->configs->custom_taxonomy_names['related_publication']['name'];

        $this->featuredPublications = [];

        $args = array(
            'post_type' 	=> $this->configs->cpt_names['publication'],
            'posts_per_page' => -1,
            'order' 		=> 'DESC',
            'orderby' 		=> 'menu_order',
            'fields' => 'all',
            'meta_key' 	=> $this->configs->cpt_meta['publication']['featured'],
            'meta_value' 	=> '1',
        );

        $posts = get_posts($args);

        foreach($posts as $post) {

            $post_id = $post->ID;
            $publicationModel = new PublicationModel($this->configs, $post_id);
            $this->featuredPublications[$post_id] = $publicationModel;
        }

    }

    public function getFeaturedPublicationsByType() {

        $publications_by_type = [];


        $publication_type_list = array_keys($this->configs->custom_taxonomy_names['publication_type']['terms']);
        array_push($publication_type_list, 'other');

        foreach ($publication_type_list as $publication_type) {
            $publications_by_type[$publication_type] = [];
        }


        // keys: publication types identifiers
        // values: arrays of publication model objects

        foreach($this->featuredPublications as $featuredPublication) {

            if (empty($featuredPublication->publication_type_array)) {
                $featuredPublication->publication_type_array['other'] = true;
            }
            foreach($featuredPublication->publication_type_array as $publication_type => $bool) {
                array_push($publications_by_type[$publication_type], $featuredPublication);
            }
        }

        return $publications_by_type;
    }

    public function hasFeaturedProjects() {
        return !empty($this->featured_projects);
    }

    public function getFeaturedProjects() {
        return $this->featured_projects;
    }
    public function hasFeaturedPortfolioItems() {
        return !empty($this->featured_portfolio_items);
    }

    public function getFeaturedPortfolioItems() {
        return $this->featured_portfolio_items;
    }

    public function hasFeaturedPublications() {
        return !empty($this->featuredPublications);
    }

    // move this function to the project model and portfolio item model
    public function hasSkills($post_id) {
        $taxonomy = $this->configs->custom_taxonomy_names['skill']['name'];
        $wp_terms = wp_get_post_terms($post_id, $taxonomy);

        return !empty($wp_terms);
    }

    // move this function to the project model and portfolio item model
    public function getSkills($post_id) {
        $taxonomy = $this->configs->custom_taxonomy_names['skill']['name'];
        $wp_terms = wp_get_post_terms($post_id, $taxonomy);

        $skills = [];

        foreach($wp_terms as $wp_term) {
            array_push($skills, $wp_term->name);
        }
        return $skills;
    }

}
