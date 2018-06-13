<?php

include "project-content-view.php";

if ($this->model->hasCredits()) {
    include "project-credits-view.php";
}

$this->model->loadRelatedPublications();
if ($this->model->hasRelatedPublications()) {
    include "project-relatedpublications-view.php";
}

$this->model->loadReadMore();
if ($this->model->hasReadMore()) {
    include "project-readmore-view.php";
}

/*
$args = array( 'post_type' => 'project', 'posts_per_page' => 10 );
$sub_query = new WP_Query( $args );

if ($sub_query->have_posts()) {
    include "project-readmore-view.php";
}
wp_reset_postdata();
*/
