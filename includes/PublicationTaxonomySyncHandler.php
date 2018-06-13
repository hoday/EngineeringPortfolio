<?php

namespace ElectrifyingEngineeringPortfolio;

class PublicationTaxonomySyncHandler {

    const TERM_META_KEY = 'eep_post_id_corresponding_publication';
    const POST_META_KEY = 'eep_term_id_corresponding_publication';

    protected $configs = null;
    protected $doingPostToTerm = false;
    protected $doingTermToPost = false;

    protected $post_type = '';
    protected $taxonomy = '';

	public function __construct($configs) {

        $this->configs = $configs;
        $this->taxonomy = $this->configs->custom_taxonomy_names['related_publication']['name'];
        $this->post_type = $this->configs->cpt_names['publication'];

    }

    public function registerCallbacks() {
        // keeps publication cpt synched with publication taxonomy
        // This callback will insert / delete a term for the taxonomy for related publications
        // if a user creates a new publication
        add_action( 'save_post', array($this, 'doPostToTerm'));
        // This callback will insert a blank entries for the cpt for Publications
        // if a user creates a new taxonomy term through the projects or portfolio_item page.
        // NOte that a callback is not necessary for deleting entries for the cpt for publications
        // since users cannot delete taxonomy terms throught he projects or portfolio_item pages.
        add_action( 'create_'. $this->taxonomy, array($this, 'doTermToPost'), 10, 2);

    }

    public function doPostToTerm($post_id) {

        if (get_post_type($post_id) == $this->post_type) {

            if (!$this->doingTermToPost) {


                $this->doingPostToTerm = true;


                if ('trash' == get_post_status( $post_id )) {
                    $this->deletePostToTerm($post_id);
                } else if ('draft' == get_post_status( $post_id ) ||
                           'publish' == get_post_status( $post_id )) {

                    $term_id_corresponding_publication = get_post_meta(
                        $post_id,
                        self::POST_META_KEY,
                        true
                    );

                    if ($term_id_corresponding_publication == null) {
                        $this->insertPostToTerm($post_id);
                    } else {
                        $this->updatePostToTerm($post_id);
                    }

                }

                $this->doingPostToTerm = false;

            }

        }
    }

    function insertPostToTerm($post_id) {
        // creates a new entry in the taxonomy corresponding to this post
        $term = get_the_title($post_id);

        $term_id_and_tt_id = wp_insert_term(
            $term,
            $this->taxonomy
        );
        $term_id = $term_id_and_tt_id['term_id'];

        $insert_was_successful = ($term_id != 0);

        if ($insert_was_successful) {
            $this->updateTermAndPostMeta($term_id, $post_id);
        }

    }

    function updatePostToTerm($post_id) {

        $term = get_the_title($post_id);

        // get term id of corresponding taxonomy term
        $term_id = get_post_meta($post_id, self::POST_META_KEY, true);

        // edit the title of the corresponding taxonomy term
        $update = wp_update_term(
            $term_id,
            $this->taxonomy,
            array(
                'name' => $term
            )
        );

    }

    function deletePostToTerm($post_id) {

        // get term id of corresponding taxonomy term
        $term_id = get_post_meta($post_id, self::POST_META_KEY, true);

        wp_delete_term($term_id, $this->taxonomy);

    }

    function doTermToPost($term_id, $tt_id) {

        if (!$this->doingPostToTerm) {
            //user created a new taxonomy term through the projects or portfolio_item page

            $this->doingTermToPost = true;

            $this->insertTermToPost($term_id);

            $this->doingTermToPost = false;

        }
    }

    function insertTermToPost($term_id) {
        // Get the title of the publication taxonomy term
        // that was jut added
        $term = get_term($term_id, $this->taxonomy)->name;

        // Create a new post of post_type publication cpt
        // with the same title
        $postarr = array(
            'post_title'    => $term,
            'post_status'   => 'publish',
            'post_type'     => $this->post_type,
        );
        $post_id = wp_insert_post($postarr);

        $insert_was_successful = ($post_id != 0);

        if ($insert_was_successful) {
            $this->updateTermAndPostMeta($term_id, $post_id);
        }
    }



    function updateTermAndPostMeta($term_id, $post_id) {
        // Write the term_meta field
        update_term_meta($term_id, self::TERM_META_KEY, $post_id);

        // Write the post_meta fields
        update_post_meta($post_id, self::POST_META_KEY, $term_id);
    }
}
