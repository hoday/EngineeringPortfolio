<?php

namespace ElectrifyingEngineeringPortfolio;

require_once(EEPF_PLUGIN_DIR . '/includes/PublicationTaxonomySyncHandler.php');

class ProjectModel {

    protected $configs = null;

    public $relatedPublications = array();

    public $readMore = array();

    public function __construct($configs, $post_id) {

     $this->configs = $configs;

     $this->post_id = $post_id;

     $this->post = get_post($post_id);

     $this->title = $this->post->post_title;

     $this->content = $this->post->post_content;

     $this->slug = $this->post->post_name;

     $this->subtitle = get_post_meta(
         $post_id,
         $this->configs->cpt_meta['project']['subtitle'],
         true
     );

     $this->credits = get_post_meta(
         $post_id,
         $this->configs->cpt_meta['project']['credits'],
         true
     );

     $this->description = get_post_meta(
         $post_id,
         $this->configs->cpt_meta['project']['description'],
         true
     );

     $this->featured = get_post_meta(
         $post_id,
         $this->configs->cpt_meta['project']['featured'],
         true
     );

    }

    public function loadRelatedPublications() {

        $taxonomy = $this->configs->custom_taxonomy_names['related_publication']['name'];

        $args = array(
         'orderby' => 'menu_order',
         'order' => 'DESC',
         'fields' => 'all'
        );

        $term_datas = wp_get_post_terms(
            $this->post_id,
            $taxonomy,
            $args
        );

        foreach($term_datas as $term_data) {
            $term_id = $term_data->term_id;
            $post_id = get_term_meta(
                $term_id,
                PublicationTaxonomySyncHandler::TERM_META_KEY,
                true
            );

            $publicationModel = new PublicationModel($this->configs, $post_id);
            $this->relatedPublications[$post_id] = $publicationModel;
        }

    }

    public function loadReadMore() {

        $args = array(
            'post_type'     => $this->configs->cpt_names['project'],
            'orderby'       => 'name',
            'order'         => 'ASC',
            'numberposts'   => 3,
        );

        $posts = get_posts($args);

        foreach($posts as $post) {
            $post_id = $post->ID;
            $projectModel = new ProjectModel($this->configs, $post_id);
            $this->readMore[$post_id] = $projectModel;

        }
    }

    public function hasRelatedPublications() {
        return !empty($this->relatedPublications);
    }

    public function getRelatedPublications() {
        return array_keys($this->relatedPublications);
    }

    public function getRelatedPublicationsByType() {

        $publications_by_type = [];


        $publication_type_list = array_keys($this->configs->custom_taxonomy_names['publication_type']['terms']);
        array_push($publication_type_list, 'other');

        foreach ($publication_type_list as $publication_type) {
            $publications_by_type[$publication_type] = [];
        }


        // keys: publication types identifiers
        // values: arrays of publication model objects

        foreach($this->relatedPublications as $relatedPublication) {

            if (empty($relatedPublication->publication_type_array)) {
                $relatedPublication->publication_type_array['other'] = true;
            }
            foreach($relatedPublication->publication_type_array as $publication_type => $bool) {
                array_push($publications_by_type[$publication_type], $relatedPublication);
            }
        }

        return $publications_by_type;
    }



    public function hasCredits() {
        return ($this->credits != '');
    }

    public function getCredits() {
        return $this->credits;
    }

    public function hasReadMore() {
        return !empty($this->readMore);
    }

    public function getReadMore() {
        return ($this->readMore);
    }

    public function getContent() {
        return ($this->post->post_content);
    }

    public function getTitle() {
        return ($this->post->post_title);
    }

    public function getDescription() {
        return ($this->description);
    }

}
