<div class="eep-portfolio">

<?php

    if ($this->model->hasFeaturedProjects()) {
        include 'portfolio-section-projects.php';
    }


    if ($this->model->hasFeaturedPortfolioItems()) {
       include 'portfolio-section-portfolioitems.php';
   }



    if ($this->model->hasFeaturedPublications()) {
        include 'portfolio-section-publications.php';
    }

?>
</div>
