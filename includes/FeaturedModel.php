<?php

namespace ElectrifyingEngineeringPortfolio;

 class FeaturedModel {

     protected $configs = null;

     public function __construct($configs, $post_id) {
         $this->configs = $configs;

         $this->featured = get_post_meta(
             $post_id,
             $this->configs->cpt_meta['project']['featured'],
             true
         );

         // set default if it is a new post
         if ($this->featured == '') {
             $this->featured = 1;
         }
     }


 }
