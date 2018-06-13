<?php

namespace ElectrifyingEngineeringPortfolio;

 class PublicationModel {

     protected $configs = null;

     public function __construct($configs, $post_id) {
         $this->configs = $configs;

         //$this->title = get_the_title($post_id);
         $this->post = get_post($post_id);
         $this->title = $this->post->post_title;

         $this->authors = get_post_meta(
             $post_id,
             $this->configs->cpt_meta['publication']['authors'],
             true
         );

         $this->details = get_post_meta(
             $post_id,
             $this->configs->cpt_meta['publication']['details'],
             true
         );

         $this->link = get_post_meta(
             $post_id,
             $this->configs->cpt_meta['publication']['link'],
             true
         );

         $this->featured = get_post_meta(
             $post_id,
             $this->configs->cpt_meta['publication']['featured'],
             true
         );

         // get the taxonomy terms for publication type

         $term_object_array = wp_get_post_terms(
             $post_id,
             $this->configs->custom_taxonomy_names['publication_type']['name']
         );

         $this->publication_type_array = [];

         foreach($term_object_array as $term_object) {
            $this->publication_type_array[$term_object->name] = true;
        }

        // get the file attachment

         $args = array(
             'post_type'      => 'attachment',
             'post_mime_type' => array(
                 'application/pdf',
                 'text/plain',
                 'application/vnd.msword',
                 'application/rtf'
             ),
             'post_parent'    => $post_id,
         );
         $attachments  		 = get_posts($args);

         if ($attachments != null && sizeof($attachments) > 0) {
             $this->file_id = $attachments[0]->ID;
             $this->file_name = get_the_title($this->file_id);

         } else {
             $this->file_id = '';
             $this->file_name  = '';
         }

     }

     public function getHref() {

         $hrefInfo = null;

         if ($this->link != '') {
             if (mb_substr($this->link, 0, strlen('http://')) === 'http://' ||
                 mb_substr($this->link, 0, strlen('https://')) === 'https://') {
                 $hrefInfo['href'] = $this->link;
                 $hrefInfo['type'] = 'link';
             } else {
                 $hrefInfo['href'] = 'http://'.$this->link;
                 $hrefInfo['type'] = 'link';
             }
         } else if ($this->file_id != '') {
             $hrefInfo['href'] = wp_get_attachment_url($this->file_id);
             $hrefInfo['type'] = 'file';
         }

         return $hrefInfo;

     }


 }
