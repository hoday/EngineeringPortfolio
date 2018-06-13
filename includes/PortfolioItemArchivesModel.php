<?php

namespace ElectrifyingEngineeringPortfolio;

require_once(EEPF_PLUGIN_DIR . '/includes/ProjectModel.php');


class PortfolioItemArchivesModel {

    protected $configs = null;

    protected $portfolioItems = [];

    public function __construct($configs) {

        $this->configs = $configs;

        $args = array(
            'post_type'     => $this->configs->cpt_names['portfolio_item'],
            'posts_per_page' => -1,
            'order' 		=> 'DESC',
            'orderby' 		=> 'menu_order',
        );
        $this->portfolioItems = get_posts($args);

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

    public function getAllPortfolioItems() {
        return $this->portfolioItems;
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
