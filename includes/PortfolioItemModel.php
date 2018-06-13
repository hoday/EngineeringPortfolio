<?php

namespace ElectrifyingEngineeringPortfolio;

 class PortfolioItemModel {

     protected $configs = null;

     public function __construct($configs, $post_id) {
         $this->configs = $configs;

         $this->details = get_post_meta(
             $post_id,
             $this->configs->cpt_meta['portfolio_item']['details'],
             true
         );

         $this->link = get_post_meta(
             $post_id,
             $this->configs->cpt_meta['portfolio_item']['link'],
             true
         );

         $this->featured = get_post_meta(
             $post_id,
             $this->configs->cpt_meta['portfolio_item']['featured'],
             true
         );
     }


 }
