<section id="eep-project-relatedpublications" class="eep-project-section eep-project-relatedpublications">

    <div class="eep-section-container">

        <h2 class="eep-section-subtitle">
            Read More
        </h2>

        <div class="eep-section-contents">

            <?php
                $publications_by_type = $this->model->getRelatedPublicationsByType();

                foreach ($publications_by_type as $type => $publication_array) {
                    if (!empty($publication_array)) {
                        include 'project-relatedpublicationssub-view.php';
                    }
                }
            ?>

        </div>

    </div>


</section>
